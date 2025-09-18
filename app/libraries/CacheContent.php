<?php namespace App\Libraries;

use CodeIgniter\Cache\CacheInterface;
use Config\Services;
use App\Models\Wine_model;

class CacheContent
{
    protected CacheInterface $cache;
    protected int $ttl;

    public function __construct(?CacheInterface $cache = null)
    {
        $this->cache = $cache ?? Services::cache();
        $this->ttl   = (int) (env('cache.menuTTL') ?? 1800); // 30min
    }

    protected function getOrSet(string $key, \Closure $producer, ?int $ttl = null)
    {
        $ttl ??= $this->ttl;
        $val = $this->cache->get($key);
        if ($val !== null) return $val;
        $val = $producer();
        if ($val !== null && $val !== false) $this->cache->save($key, $val, $ttl);
        return $val;
    }

    protected function kWeinHead(?string $type, ?int $pol1Id): string
    {
        return 'menu:wein:head:' . strtolower($type ?? 'all') . ':' . ($pol1Id ?? -1);
    }

    protected function kRegionen(array $args): string
    {
        ksort($args);
        return 'menu:regionen:' . md5(json_encode($args));
    }

    protected function kRebsorten(?int $pol1Id, ?int $pol2Id): string
    {
        return 'menu:rebsorten:' . ($pol1Id ?? -1) . ':' . ($pol2Id ?? -1);
    }

    // 1) Head-Menü mobil
    public function getWeinHeadMenue_Mobile(?string $menType = null, ?int $pol1Id = null): array
    {
        $key = $this->kWeinHead($menType, $pol1Id);
        return $this->getOrSet($key, function () use ($menType, $pol1Id) {
            $m = new Wine_model();
            $wherePol1 = $pol1Id !== null ? ['wr_prodOrdLev_2.pol1_ID' => (int)$pol1Id] : null;
            $data = $m->get_head_menue($menType, $wherePol1);
            return is_array($data) ? $data : [];
        });
    }

    // 2) Regionen-Menü
    public function getRegionenMenue(
        ?array $price = null,
        ?string $type = null,
        ?int $pol1Id = null,
        ?int $pol2Id = null,
        ?int $pol3Id = null,
        ?int $producerId = null
    ): array {
        $key = $this->kRegionen(compact('price', 'type', 'pol1Id', 'pol2Id', 'pol3Id', 'producerId'));
        return $this->getOrSet($key, function () use ($price, $type, $pol1Id, $pol2Id, $pol3Id, $producerId) {
            $m = new Wine_model();
            $wPol1 = $pol1Id !== null ? ['wr_prodOrdLev_2.pol1_ID' => (int)$pol1Id] : null;
            $wPol2 = $pol2Id !== null ? ['wr_prodOrdLev_3.pol2_ID' => (int)$pol2Id] : null;
            $wPol3 = $pol3Id !== null ? ['pol3_ID' => (int)$pol3Id] : null;
            $wProd = $producerId !== null ? ['wr_prod_producer.ID' => (int)$producerId] : null;
            $data = $m->get_menue($price, $type, $wPol1, $wPol2, $wPol3, $wProd);
            return is_array($data) ? $data : [];
        });
    }

    // 3) Rebsorten-Menü (Counts)
    public function getRebsortenMenu(?int $pol1Id = null, ?int $pol2Id = null): array
    {
        $key = $this->kRebsorten($pol1Id, $pol2Id);
        return $this->getOrSet($key, function () use ($pol1Id, $pol2Id) {
            $db = \Config\Database::connect();

            $b = $db->table('wr_product')
                ->select('ID, pol1_ID, pol2_ID, stock_act, invoiced')
                ->where('online', 1)
                ->where('stock_act >', 0);

            if ($pol1Id !== null) $b->where('pol1_ID', (int)$pol1Id);
            if ($pol2Id !== null) $b->where('pol2_ID', (int)$pol2Id);

            $prod = $b->get()->getResultArray();
            if (empty($prod)) return ['grapes' => []];

            $pids = array_map(fn($r) => (int)$r['ID'], $prod);
            $stockAvail = [];
            foreach ($prod as $r) $stockAvail[(int)$r['ID']] = ((int)$r['stock_act'] - (int)$r['invoiced']);

            $rows = $db->table('wr_grapes2wine')
                ->select('wr_grapes2wine.grape_ID, wr_grapes2wine.product_ID, wr_grapes.grape')
                ->join('wr_grapes', 'wr_grapes.ID = wr_grapes2wine.grape_ID')
                ->whereIn('wr_grapes2wine.product_ID', $pids)
                ->get()->getResultArray();

            $out = [];
            foreach ($rows as $r) {
                $gid = (int)$r['grape_ID']; $pid = (int)$r['product_ID'];
                $out[$gid] ??= ['ID'=>$gid, 'grape'=>$r['grape'], 'noProdActive'=>0, 'noProdStock'=>0];
                $out[$gid]['noProdActive']++;
                if (($stockAvail[$pid] ?? 0) > 1) $out[$gid]['noProdStock']++;
            }
            ksort($out);
            return ['grapes' => $out];
        });
    }

    // Invalidate / Refresh
    public function invalidateAll(): void
    {
        if (method_exists($this->cache, 'deleteMatching')) {
            $this->cache->deleteMatching('menu:wein:*');
            $this->cache->deleteMatching('menu:regionen:*');
            $this->cache->deleteMatching('menu:rebsorten:*');
        }
    }
    public function invalidateWein(?string $menType = null, ?int $pol1Id = null): void
    { $this->cache->delete($this->kWeinHead($menType, $pol1Id)); }
    public function invalidateRegionen(?array $price=null, ?string $type=null, ?int $pol1Id=null, ?int $pol2Id=null, ?int $pol3Id=null, ?int $producerId=null): void
    { $this->cache->delete($this->kRegionen(compact('price','type','pol1Id','pol2Id','pol3Id','producerId'))); }
    public function invalidateRebsorten(?int $pol1Id = null, ?int $pol2Id = null): void
    { $this->cache->delete($this->kRebsorten($pol1Id, $pol2Id)); }

    public function refreshWeinHead(?string $menType = null, ?int $pol1Id = null): array
    { $this->invalidateWein($menType,$pol1Id); return $this->getWeinHeadMenue_Mobile($menType,$pol1Id); }
    public function refreshRegionen(?array $price=null, ?string $type=null, ?int $pol1Id=null, ?int $pol2Id=null, ?int $pol3Id=null, ?int $producerId=null): array
    { $this->invalidateRegionen($price,$type,$pol1Id,$pol2Id,$pol3Id,$producerId); return $this->getRegionenMenue($price,$type,$pol1Id,$pol2Id,$pol3Id,$producerId); }
    public function refreshRebsorten(?int $pol1Id = null, ?int $pol2Id = null): array
    { $this->invalidateRebsorten($pol1Id,$pol2Id); return $this->getRebsortenMenu($pol1Id,$pol2Id); }
}
