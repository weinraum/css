<?php
namespace App\Services;

use CodeIgniter\Database\ConnectionInterface;

final class WeinMenuSnapshotBuilder
{
    private string $outDir;
    private string $htmlFile;
    private string $jsonFile;
    private string $etagFile;
    private string $lockFile;

    public function __construct(
        private ConnectionInterface $db,
        string $writablePath = WRITEPATH . 'cache/menus'
    ) {
        $this->outDir   = rtrim($writablePath, '/');
        $this->htmlFile = "{$this->outDir}/wein.html";
        $this->jsonFile = "{$this->outDir}/wein.json";
        $this->etagFile = "{$this->outDir}/wein.etag";
        $this->lockFile = "{$this->outDir}/wein.lock";
    }

    /** Baue Snapshot (HTML + JSON) atomar mit File-Lock. */
    public function build(bool $writeJson = true): void
    {
        if (!is_dir($this->outDir)) {
            @mkdir($this->outDir, 0775, true);
        }
        $fh = fopen($this->lockFile, 'c+');
        if (!$fh) {
            throw new \RuntimeException("Lock-Datei nicht schreibbar: {$this->lockFile}");
        }

        try {
            if (!flock($fh, LOCK_EX | LOCK_NB)) {
                // Läuft bereits – ruhig aussteigen.
                return;
            }

            [$tree, $meta] = $this->loadAndAssembleTree();

            $html = $this->renderHtml($tree);
            $json = $writeJson ? json_encode(['meta' => $meta, 'tree' => $tree], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null;

            $hashBasis = $writeJson ? ($html . '|' . $json) : $html;
            $etag = sha1($hashBasis);

            $oldEtag = is_file($this->etagFile) ? trim((string)@file_get_contents($this->etagFile)) : '';
            if ($etag !== $oldEtag) {
                $this->atomicWrite($this->htmlFile, $html);
                if ($writeJson && $json !== null) {
                    $this->atomicWrite($this->jsonFile, $json);
                }
                $this->atomicWrite($this->etagFile, $etag);
            }
        } finally {
            @flock($fh, LOCK_UN);
            @fclose($fh);
        }
    }

    /**
     * L1/L2/L3/Winzer laden, „tote Hunde“ ausfiltern:
     * - Nur L1 mit show=1.
     * - L2 nur, wenn show=1 UND pol1_ID in sichtbaren L1.
     * - L3 nur, wenn show=1 UND pol2_ID in sichtbaren L2.
     * - Winzer nur, wenn deren tiefste zuordenbare Ebene sichtbar ist.
     * Zusätzlich: Dedupe L2/L3 per URL.
     */
    private function loadAndAssembleTree(): array
    {
        // --- L1 ---
        $l1Rows = $this->db->query(
            "SELECT ID, name_pol1 AS name, name_pol1_url AS url, pos AS pos
             FROM wr_prodOrdLev_1
             WHERE show = 1
             ORDER BY pos, name_pol1"
        )->getResultArray();

        // Sichtbare L1-IDs
        $visibleL1 = array_column($l1Rows, 'ID');

        // --- L2 (nur unter sichtbaren L1) ---
        $l2RowsAll = $this->db->query(
            "SELECT ID, pol1_ID, name_pol2 AS name, name_pol2_url AS url, pos_pol2_menue AS pos, show
             FROM wr_prodOrdLev_2
             ORDER BY pos_pol2_menue, name_pol2"
        )->getResultArray();

        $l2Rows = [];
        foreach ($l2RowsAll as $r) {
            if ((int)$r['show'] !== 1) continue;
            if (!in_array((int)$r['pol1_ID'], $visibleL1, true)) continue; // „tote Hunde“ raus
            $l2Rows[] = $r;
        }

        // --- L3 (nur unter sichtbaren L2) ---
        $l3RowsAll = $this->db->query(
            "SELECT ID, pol1_ID, pol2_ID, name_pol3 AS name, name_pol3_url AS url, pos_pol3_menue AS pos, show
             FROM wr_prodOrdLev_3
             ORDER BY pos_pol3_menue, name_pol3"
        )->getResultArray();

        // Dedup-L2 vorbereiten, bevor wir visible L2-IDs bilden
        $canonL2 = []; $mapL2 = [];
        foreach ($l2Rows as $r) {
            $u = trim(mb_strtolower((string)($r['url'] ?? '')));
            if ($u === '') continue;
            if (!isset($canonL2[$u])) $canonL2[$u] = $r;
            $mapL2[$r['ID']] = $canonL2[$u]['ID'];
        }
        $visibleL2 = array_values(array_unique(array_column($canonL2, 'ID')));

        $l3Rows = [];
        foreach ($l3RowsAll as $r) {
            if ((int)$r['show'] !== 1) continue;
            $rawL2 = (int)$r['pol2_ID'];
            $canonL2Id = $mapL2[$rawL2] ?? $rawL2;
            if (!in_array($canonL2Id, $visibleL2, true)) continue; // „tote Hunde“ raus
            $l3Rows[] = $r;
        }

        // Dedup-L3
        $canonL3 = []; $mapL3 = [];
        foreach ($l3Rows as $r) {
            $u = trim(mb_strtolower((string)($r['url'] ?? '')));
            if ($u === '') continue;
            if (!isset($canonL3[$u])) $canonL3[$u] = $r;
            $mapL3[$r['ID']] = $canonL3[$u]['ID'];
        }
        $visibleL3 = array_values(array_unique(array_column($canonL3, 'ID')));

        // --- Winzer (nur aktive Produzenten) ---
        $winzerAll = $this->db->query(
            "SELECT id, name, identifier AS url, position AS pos,
                    pol1_id, pol2_id, pol3_id, producer_id, status
             FROM wr_category
             WHERE producer_id > 0 AND status = 1
             ORDER BY position IS NULL, position, name"
        )->getResultArray();

        $winzerRows = [];
        foreach ($winzerAll as $w) {
            // tiefstmögliche sichtbare Ebene suchen
            $placedLevel = null;
            if (!empty($w['pol3_id'])) {
                $raw = (int)$w['pol3_id'];
                $canon = $mapL3[$raw] ?? $raw;
                if (in_array($canon, $visibleL3, true)) {
                    $placedLevel = ['level' => 'l3', 'id' => $canon];
                }
            }
            if (!$placedLevel && !empty($w['pol2_id'])) {
                $raw = (int)$w['pol2_id'];
                $canon = $mapL2[$raw] ?? $raw;
                if (in_array($canon, $visibleL2, true)) {
                    $placedLevel = ['level' => 'l2', 'id' => $canon];
                }
            }
            if (!$placedLevel && !empty($w['pol1_id'])) {
                if (in_array((int)$w['pol1_id'], $visibleL1, true)) {
                    $placedLevel = ['level' => 'l1', 'id' => (int)$w['pol1_id']];
                }
            }
            if ($placedLevel) {
                $w['__placed'] = $placedLevel;
                $winzerRows[] = $w;
            }
            // Falls kein sichtbarer Pfad existiert -> Winzer fällt raus.
        }

        // --- Baumstruktur aufbauen ---
        $idxL1 = [];
        foreach ($l1Rows as $r) {
            $idxL1[(int)$r['ID']] = [
                'id'   => (int)$r['ID'],
                'name' => (string)$r['name'],
                'url'  => (string)$r['url'],
                'pos'  => (int)$r['pos'],
                'type' => 'l1',
                'children' => []
            ];
        }

        $idxL2 = [];
        foreach ($canonL2 as $r) {
            $l1id = (int)$r['pol1_ID'];
            if (!isset($idxL1[$l1id])) continue;
            $node = [
                'id'   => (int)$r['ID'],
                'name' => (string)$r['name'],
                'url'  => (string)$r['url'],
                'pos'  => (int)$r['pos'],
                'type' => 'l2',
                'children' => []
            ];
            $idxL1[$l1id]['children'][] = $node;
            $idxL2[$node['id']] = &$idxL1[$l1id]['children'][array_key_last($idxL1[$l1id]['children'])];
        }

        $idxL3 = [];
        foreach ($canonL3 as $r) {
            $rawL2 = (int)$r['pol2_ID'];
            $l2id  = $mapL2[$rawL2] ?? $rawL2;
            if (!isset($idxL2[$l2id])) continue;
            $node = [
                'id'   => (int)$r['ID'],
                'name' => (string)$r['name'],
                'url'  => (string)$r['url'],
                'pos'  => (int)$r['pos'],
                'type' => 'l3',
                'children' => []
            ];
            $idxL2[$l2id]['children'][] = $node;
            $idxL3[$node['id']] = &$idxL2[$l2id]['children'][array_key_last($idxL2[$l2id]['children'])];
        }

        foreach ($winzerRows as $w) {
            $n = [
                'id'   => (int)$w['id'],
                'name' => (string)$w['name'],
                'url'  => (string)$w['url'],
                'pos'  => (int)($w['pos'] ?? 0),
                'type' => 'producer'
            ];
            $placed = $w['__placed'];
            if ($placed['level'] === 'l3' && isset($idxL3[$placed['id']])) {
                $idxL3[$placed['id']]['children'][] = $n;
            } elseif ($placed['level'] === 'l2' && isset($idxL2[$placed['id']])) {
                $idxL2[$placed['id']]['children'][] = $n;
            } elseif ($placed['level'] === 'l1' && isset($idxL1[$placed['id']])) {
                $idxL1[$placed['id']]['children'][] = $n;
            }
        }

        // Rekursive Sortierung
        $sortFn = function (&$arr) use (&$sortFn) {
            usort($arr, function ($a, $b) {
                $pa = $a['pos'] ?? 0; $pb = $b['pos'] ?? 0;
                if ($pa === $pb) return strnatcasecmp($a['name'], $b['name']);
                return $pa <=> $pb;
            });
            foreach ($arr as &$n) {
                if (!empty($n['children'])) $sortFn($n['children']);
            }
        };

        $tree = array_values($idxL1);
        $sortFn($tree);

        $meta = [
            'generated_at' => date('c'),
            'source'       => 'h737390_trad',
            'counts'       => [
                'l1' => count($tree),
                'l2' => array_sum(array_map(fn($n)=>count($n['children'] ?? []), $tree)),
            ],
        ];
        return [$tree, $meta];
    }

    /** Erzeugt komplettes HTML inkl. Wrapper. Du musst nichts ergänzen. */
    private function renderHtml(array $tree): string
    {
        $esc = static fn(string $s) => htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        $render = function ($nodes) use (&$render, $esc): string {
            $html = "<ul class=\"weinmenu\">";
            foreach ($nodes as $n) {
                $title = $esc($n['name']);
                switch ($n['type']) {
                    case 'l1':
                        $href = '/regionen/' . $esc($n['url']);
                        $html .= "<li class=\"l1\"><a href=\"{$href}\">{$title}</a>";
                        $html .= !empty($n['children']) ? $render($n['children']) : '';
                        $html .= "</li>";
                        break;
                    case 'l2':
                        $href = '/regionen/' . $esc($n['url']);
                        $html .= "<li class=\"l2\"><a href=\"{$href}\">{$title}</a>";
                        $html .= !empty($n['children']) ? $render($n['children']) : '';
                        $html .= "</li>";
                        break;
                    case 'l3':
                        $href = '/regionen/' . $esc($n['url']);
                        $html .= "<li class=\"l3\"><a href=\"{$href}\">{$title}</a>";
                        $html .= !empty($n['children']) ? $render($n['children']) : '';
                        $html .= "</li>";
                        break;
                    case 'producer':
                        $href = '/winzer/' . $esc($n['url']);
                        $html .= "<li class=\"producer\"><a href=\"{$href}\">{$title}</a></li>";
                        break;
                }
            }
            $html .= "</ul>";
            return $html;
        };

        // <nav> kommt aus dem Builder. Du fügst nichts hinzu/weg.
        return "<nav class=\"weinmenu-wrapper\">" . $render($tree) . "</nav>";
    }

    private function atomicWrite(string $file, string $content): void
    {
        $tmp = $file . '.' . uniqid('tmp', true);
        if (file_put_contents($tmp, $content) === false) {
            throw new \RuntimeException("Kann Datei nicht schreiben: {$tmp}");
        }
        @chmod($tmp, 0664);
        if (!@rename($tmp, $file)) {
            @unlink($tmp);
            throw new \RuntimeException("Kann Datei nicht ersetzen: {$file}");
        }
    }
}
