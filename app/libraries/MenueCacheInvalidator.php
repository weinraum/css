<?php namespace App\Libraries;

use App\Libraries\CacheContent;
use Config\Database;

class MenuCacheInvalidator
{
    private CacheContent $cache;
    private $db;

    public function __construct(?CacheContent $cache = null)
    {
        $this->cache = $cache ?? new CacheContent();
        $this->db    = Database::connect();
    }

    /** Wenn ein einzelner Wein geändert/angelegt/gelöscht wurde */
    public function wineChanged(int $productId): void
    {
        $row = $this->db->table('wr_product')
            ->select('pol1_ID, pol2_ID, pol3_ID, type, online, stock_act, invoiced, producer_ID')
            ->where('ID', $productId)
            ->get()->getRowArray();

        if (!$row) {
            // Falls Wein gelöscht wurde: konservativ alles Relevante
            $this->cache->invalidateWein();
            $this->cache->invalidateRegionen();
            $this->cache->invalidateRebsorten();
            return;
        }

        $pol1 = (int)($row['pol1_ID'] ?? 0) ?: null;
        $pol2 = (int)($row['pol2_ID'] ?? 0) ?: null;
        $pol3 = (int)($row['pol3_ID'] ?? 0) ?: null;

        // 1) Head-Menü (Typ + Land/pol1)
        if ($mt = $this->mapTypeToMenu($row['type'] ?? null)) {
            // feingranular wenn möglich
            if (method_exists($this->cache, 'invalidateWein')) {
                $this->cache->invalidateWein($mt, $pol1);
            } else {
                $this->cache->invalidateWeinHead($mt, $pol1);
            }
        } else {
            $this->cache->invalidateWein();
        }

        // 2) Regionen-Menü (Counts/Producer-Listen)
        if (method_exists($this->cache, 'invalidateRegionen')) {
            // optional granular, falls du Parameter unterstützt
            $this->cache->invalidateRegionen($pol1, $pol2, $pol3);
        } else {
            $this->cache->invalidateRegionen();
        }

        // 3) Rebsorten-Menü (abhängig von pol1/pol2)
        if (method_exists($this->cache, 'invalidateRebsorten')) {
            $this->cache->invalidateRebsorten($pol1, $pol2);
        } else {
            $this->cache->invalidateRebsorten();
        }
    }

    /** Wenn Grapes-Mapping (wr_grapes2wine) für einen Wein geändert wurde */
    public function grapesChangedForWine(int $productId): void
    {
        $row = $this->db->table('wr_product')
            ->select('pol1_ID, pol2_ID')
            ->where('ID', $productId)
            ->get()->getRowArray();

        $pol1 = isset($row['pol1_ID']) ? (int)$row['pol1_ID'] : null;
        $pol2 = isset($row['pol2_ID']) ? (int)$row['pol2_ID'] : null;

        $this->cache->invalidateRebsorten($pol1, $pol2);
        // optional: Region neu zählen
        $this->cache->invalidateRegionen($pol1, $pol2, null);
    }

    /** Wenn ein Winzer (Producer) geändert/angelegt/gelöscht wurde */
    public function producerChanged(int $producerId): void
    {
        // Alle betroffenen pol1/pol2/pol3 holen (Distinct), dann Regionen-Teil invalidieren
        $rows = $this->db->table('wr_product')
            ->select('DISTINCT pol1_ID, pol2_ID, pol3_ID')
            ->where('producer_ID', $producerId)
            ->get()->getResultArray();

        if (empty($rows)) {
            $this->cache->invalidateRegionen(); // konservativ
            return;
        }
        foreach ($rows as $r) {
            $p1 = (int)($r['pol1_ID'] ?? 0) ?: null;
            $p2 = (int)($r['pol2_ID'] ?? 0) ?: null;
            $p3 = (int)($r['pol3_ID'] ?? 0) ?: null;
            $this->cache->invalidateRegionen($p1, $p2, $p3);
        }
    }

    /** Wenn Content/Taxonomie (Ordnerlevel, Menütitel/Positionen) geändert wurde */
    public function contentMenuChanged(?int $pol1Id = null, ?int $pol2Id = null, ?int $pol3Id = null): void
    {
        $this->cache->invalidateRegionen($pol1Id, $pol2Id, $pol3Id);
        // Rebsorten-Menü ist ebenfalls vom Navigationsbaum abhängig (pol1/pol2)
        if ($pol1Id !== null || $pol2Id !== null) {
            $this->cache->invalidateRebsorten($pol1Id, $pol2Id);
        }
    }

    /** Bulk, falls du mehrere IDs auf einmal änderst */
    public function winesChanged(array $productIds): void
    {
        $productIds = array_values(array_filter(array_map('intval', $productIds)));
        if (empty($productIds)) return;
        foreach ($productIds as $id) $this->wineChanged($id);
    }

    // ---- helper ----
    private function mapTypeToMenu(?string $type): ?string
    {
        if ($type === null) return null;
        // Rot
        if (in_array($type, ['RF','RS','RK','RT'], true)) return 'men_Rot';
        // Weiß
        if (in_array($type, ['WF','WV','WA','WE'], true)) return 'men_Weiss';
        // Rosé
        if ($type === 'RO') return 'men_Rose';
        // Schaum/Perl/Champagner/Crémant
        if (in_array($type, ['S','FR','CH','CHR','CR','CRR','PR'], true)) return 'men_Cremant';
        return null;
    }
}
