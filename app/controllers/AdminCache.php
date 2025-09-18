<?php namespace App\Controllers;

use App\Libraries\CacheContent;

class AdminCache extends BaseController
{
    protected CacheContent $cache;

    public function __construct()
    {
        $this->cache = new CacheContent();
    }

    private function ensurePost()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return $this->response->setStatusCode(405)->setJSON(['ok'=>false,'error'=>'POST required']);
        }
        return null;
    }
    private function ok($data = []) { return $this->response->setJSON(['ok'=>true] + $data); }
    private function fail($msg, $code = 400) { return $this->response->setStatusCode($code)->setJSON(['ok'=>false,'error'=>$msg]); }
    private function payload(): array
    {
        // unterstützt Form-POST und JSON-POST
        $post = $this->request->getPost() ?? [];
        $json = $this->request->getJSON(true) ?? [];
        return array_merge($post, $json);
    }
    private function intOrNull($v): ?int
    { return ($v === null || $v === '' || !is_numeric($v)) ? null : (int)$v; }

    public function invalidateAll()
    {
        if ($r = $this->ensurePost()) return $r;
        $this->cache->invalidateAll();
        return $this->ok(['message'=>'All cache keys invalidated']);
    }

    public function refreshWeinHead()
    {
        if ($r = $this->ensurePost()) return $r;
        $p = $this->payload();

        $allowedTypes = ['men_Weiss','men_Rot','men_Rose','men_Cremant'];
        $type  = isset($p['type']) && in_array($p['type'], $allowedTypes, true) ? $p['type'] : null;
        $pol1Id = $this->intOrNull($p['pol1Id'] ?? null);

        $data = $this->cache->refreshWeinHead($type, $pol1Id);
        return $this->ok(['key'=>'wein:head','type'=>$type,'pol1Id'=>$pol1Id,'size'=>is_array($data)?count($data):0]);
    }

    public function refreshRegionen()
    {
        if ($r = $this->ensurePost()) return $r;
        $p = $this->payload();

        $price = null;
        if (isset($p['price']) && is_array($p['price'])) {
            $price = [
                'from' => isset($p['price']['from']) ? (float)$p['price']['from'] : null,
                'to'   => isset($p['price']['to'])   ? (float)$p['price']['to']   : null,
            ];
        }

        $allowedTypes = ['men_Weiss','men_Rot','men_Rose','men_Cremant'];
        $type = isset($p['type']) && in_array($p['type'], $allowedTypes, true) ? $p['type'] : null;

        $pol1Id = $this->intOrNull($p['pol1Id'] ?? null);
        $pol2Id = $this->intOrNull($p['pol2Id'] ?? null);
        $pol3Id = $this->intOrNull($p['pol3Id'] ?? null);
        $producerId = $this->intOrNull($p['producerId'] ?? null);

        $data = $this->cache->refreshRegionen($price, $type, $pol1Id, $pol2Id, $pol3Id, $producerId);

        $parts = [];
        foreach (['pol1','pol2','pol3','producer'] as $k) {
            if (isset($data[$k]) && is_array($data[$k])) $parts[$k] = count($data[$k]);
        }

        return $this->ok([
            'key'=>'regionen',
            'pol1Id'=>$pol1Id,'pol2Id'=>$pol2Id,'pol3Id'=>$pol3Id,
            'type'=>$type,'price'=>$price,'producerId'=>$producerId,
            'parts'=>$parts
        ]);
    }

    public function refreshRebsorten()
    {
        if ($r = $this->ensurePost()) return $r;
        $p = $this->payload();

        $pol1Id = $this->intOrNull($p['pol1Id'] ?? null);
        $pol2Id = $this->intOrNull($p['pol2Id'] ?? null);

        $data = $this->cache->refreshRebsorten($pol1Id, $pol2Id);
        $size = isset($data['grapes']) && is_array($data['grapes']) ? count($data['grapes']) : 0;

        return $this->ok(['key'=>'rebsorten','pol1Id'=>$pol1Id,'pol2Id'=>$pol2Id,'size'=>$size]);
    }
}
