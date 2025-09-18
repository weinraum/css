<?php declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * Startseite – sektionenbasiert, strikt ID-gesteuert.
 * Nur zwei Berührungspunkte:
 *   1) sectionsConfig() – Struktur/IDs festlegen
 *   2) View 'home-page-shop' rendert generisch über $sections
 *
 * Services/Models bleiben unberührt. Für Vollartikel wird ContentService::byId() genutzt.
 */
final class Home extends BaseController
{
    private const HOMEPAGE_CACHE_TTL = 900; // 15 Minuten

    public function index()
    {
        $sections = $this->sectionsConfig();

        // alle Content-IDs sammeln
        $allIds = [];
        foreach ($sections as $s) {
            $allIds = array_merge($allIds, (array)($s['content_ids'] ?? []));
        }
        $allIds = array_values(array_unique(array_map('intval', $allIds)));

        // Inhalte in einem Rutsch laden
        $rowsById = $this->fetchContentRows($allIds);

        // Abschnitte bauen (pro Section cachen; Full-Artikel holt zusätzlich Aggregate)
        $builtSections = [];
        $cache = cache();

        foreach ($sections as $section) {
            $cacheKey = $this->cacheKeyForSection($section);
            $type     = (string)($section['type'] ?? 'teaser_row');

            // Aggregate nicht in FileCache legen
            $canCache = ($type !== 'article_full');

            if ($canCache) {
                $cached = $cache->get($cacheKey);
                if (is_array($cached)) {
                    $builtSections[] = $cached;
                    continue;
                }
            }

            $items = [];
            foreach ((array)$section['content_ids'] as $cid) {
                $cid  = (int)$cid;
                $row  = $rowsById[$cid] ?? null;
                $dto  = $this->buildDto($cid, $row, $type);

                // Für Vollartikel zusätzlich das Aggregate laden (für content/show)
                if ($type === 'article_full' && $cid > 0) {
                    try {
                        $agg = service('contentService')->byId($cid);
                        if ((int)($agg->content['status'] ?? 0) === 1) {
                            $dto['agg'] = $agg;
                        }
                    } catch (\Throwable) {}
                }

                if ($dto) $items[] = $dto;
            }

            if (!$items) continue; // leere Section auslassen

            $built = [
                'type'  => $type,
                'title' => $section['title'] ?? null,
                'cols'  => $section['cols'] ?? 3,
                'items' => $items,
                'id'    => $section['id'] ?? null, // z. B. für Carousel-ID
            ];

            if ($canCache) {
                $cache->save($cacheKey, $built, self::HOMEPAGE_CACHE_TTL);
            }
            $builtSections[] = $built;
        }

        return view('home-page-shop', ['sections' => $builtSections]);
    }

    /**
     * Nur hier steuerst du die Startseiten-Struktur (rein ID-basiert, beliebig erweiterbar).
     * Beispiel: eine 3er-Teaser-Zeile + ein Vollartikel (Brusset: 302).
     */
    private function sectionsConfig(): array
    {
        return [
            [
                'type'        => 'teaser_row',
                'title'       => 'Winzer · Region · Lexikon',
                'cols'        => 3,
                'content_ids' => [101, 202, 303],   // <— echte IDs einsetzen
            ],
            [
                'type'        => 'article_full',
                'content_ids' => [302],             // <— z. B. "Domaine Brusset"
            ],
            // weitere Sections jederzeit anhängen (z. B. carousel)
            // [
            //     'type'        => 'carousel',
            //     'id'          => 'hp-top',
            //     'content_ids' => [501, 502, 503, 504],
            // ],
        ];
    }

    /** Holt Grunddaten zu Content-IDs – tolerant bei Spaltennamen. */
    private function fetchContentRows(array $ids): array
    {
        if (!$ids) return [];

        $byId = [];
        try {
            $db = db_connect();
            $rows = $db->table('wr_content')
                ->select('*')                 // robust gegen abweichende Spaltennamen
                ->whereIn('id', $ids)
                ->get()
                ->getResultArray();

            foreach ($rows as $r) {
                $byId[(int)$r['id']] = $r;
            }
        } catch (\Throwable) {
            // optionaler Fallback auf Legacy-Model (falls vorhanden)
            try {
                $rows = model('Content_model')->getByIds($ids);
                foreach ($rows as $r) {
                    $byId[(int)$r['id']] = $r;
                }
            } catch (\Throwable) {}
        }

        return $byId;
    }

    /** Erstes nicht-leeres Feld aus $row. */
    private function pick(array $row, array $candidates): ?string
    {
        foreach ($candidates as $k) {
            if (array_key_exists($k, $row) && (string)$row[$k] !== '') {
                return (string)$row[$k];
            }
        }
        return null;
    }

    /** URL rein aus der Zeile; Fallback /content/id/{cid}. */
private function resolveContentUrl(int $cid, array $row = []): string
{
    $identifier = $row['identifier'] ?? ($row['identifer'] ?? ($row['handle'] ?? null));
    if ($identifier) {
        $blog = $row['blog_handle'] ?? ($row['blog'] ?? null);
        return $blog ? '/blogs/'.$blog.'/'.$identifier : '/content/'.$identifier;
    }
    return '/content/id/'.$cid;
}


    /** Neutrales View-DTO je Content. */
    private function buildDto(int $cid, ?array $row, string $mode): ?array
    {
        if (!$row) {
            return [
                'cid'     => $cid,
                'title'   => 'Inhalt nicht gefunden',
                'url'     => '/content/id/'.$cid,
                'excerpt' => null,
                'meta'    => ['type' => null, 'updated_at' => null],
                'render'  => ['mode' => $mode],
            ];
        }

        $title =
            $this->pick($row, ['title','headline','h1','name','titel']) ?:
            ($this->pick($row, ['identifier','identifer','handle']) ?: '#'.$cid);

        $excerpt = $this->pick($row, ['excerpt','teaser','summary','intro']);
        $type    = $this->pick($row, ['type','content_type','ctype']);
        $updated = $this->pick($row, ['updated_at','updated','date_mod']);

        return [
            'cid'     => $cid,
            'title'   => $title,
            'url'     => $this->resolveContentUrl($cid, $row),
            'excerpt' => $excerpt,
            'meta'    => ['type' => $type, 'updated_at' => $updated],
            'render'  => ['mode' => $mode],
        ];
    }

    /** Stabiler Section-Cache-Key (ohne reservierte Zeichen). */
    private function cacheKeyForSection(array $section): string
    {
        $type = (string)($section['type'] ?? 'row');
        $ids  = array_map('intval', (array)($section['content_ids'] ?? []));
        $sid  = (string)($section['id'] ?? '');
        return 'hp_sec_v3_' . sha1($type.'|'.implode(',', $ids).'|'.$sid);
    }
}
