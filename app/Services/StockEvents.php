<?php
// app/Services/StockEvents.php
namespace App\Services;

use Config\Services;
use CodeIgniter\Database\BaseConnection;

final class StockEvents
{
    public function __construct(private BaseConnection $db) {}

    public function onInventoryChange(int $productId): void
    {
        // 1) alten & neuen Bestand holen (vorher in Temp/Log schreiben ODER hier direkt)
        $row = $this->db->table('wr_product')
            ->select('ID, stock_qty, low_stock_threshold, winzer_id, region_id, style_id')
            ->where('ID', $productId)->get()->getRowArray();
        if (!$row) return;

        $th   = (int)($row['low_stock_threshold'] ?? 6);
        $newB = stockBucket((int)$row['stock_qty'], $th);

        // alten Bucket aus leichtgewichtigem Shadow-Table lesen (oder aus Cache)
        $old = $this->db->table('wr_product_stock_shadow')
            ->select('bucket')->where('product_id',$productId)->get()->getRowArray();
        $oldB = $old['bucket'] ?? null;

        if ($newB === $oldB) {
            // nur Shadow aktualisieren, nichts busten
            $this->saveShadow($productId, $newB);
            return;
        }

        // 2) Shadow aktualisieren
        $this->saveShadow($productId, $newB);

        // 3) gezielt invalidieren
        $cache = Services::cache();

        // Produktseite
        $cache->delete("page:product:{$productId}");
        $cache->delete("jsonld:product:{$productId}");
        $cache->delete("fragments:product:badges:{$productId}");

        // betroffene Listen/Facetten
        if (!empty($row['winzer_id'])) $cache->delete("list:winzer:{$row['winzer_id']}");
        if (!empty($row['region_id'])) $cache->delete("list:region:{$row['region_id']}");
        if (!empty($row['style_id']))  $cache->delete("list:style:{$row['style_id']}");

        // globale „Neu/Verfügbar“-Kacheln, falls vorhanden
        $cache->delete('list:available:latest');
        $cache->delete('list:lowstock:teaser');
    }

    private function saveShadow(int $pid, string $bucket): void
    {
        // upsert
        $this->db->table('wr_product_stock_shadow')->ignore(true)->insert([
            'product_id' => $pid,
            'bucket'     => $bucket,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $this->db->table('wr_product_stock_shadow')
            ->where('product_id',$pid)->update([
                'bucket'     => $bucket,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
}
