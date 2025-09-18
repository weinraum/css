<?php declare(strict_types=1);

namespace App\Services;

use CodeIgniter\Database\BaseConnection;

final class HomeTeaserService
{
    public function __construct(private BaseConnection $db) {}

    public function winzerTeaser(int $producerId): ?array
    {
        $prod = $this->db->table('wr_prod_producer')
            ->select('ID, identifier_prod, producer')
            ->where('ID', $producerId)
            ->get()->getRowArray();
        if (!$prod) return null;

        $contentId = $this->resolveContentIdByProducerMapping($producerId);
        if (!$contentId) return null;

        $c = $this->db->table('wr_content')
            ->select('id, title, identifier')
            ->where('id', $contentId)
            ->get()->getRowArray();

        return [
            'title' => (string)($c['title'] ?? $prod['producer']),
            'url'   => '/winzer/ident/' . (string)$prod['identifier_prod'],
            'img'   => $this->firstTeaserImage($contentId),
            'id'    => (int)$contentId,
            'identifier' => (string)($c['identifier'] ?? ''),
        ];
    }

    public function regionTeaser(int $level, int|string $key): ?array
    {
        $table  = "wr_prodOrdLev_{$level}";
        $name   = "name_pol{$level}";
        $urlcol = "name_pol{$level}_url";

        $qb = $this->db->table($table)->select("ID, {$name} AS name, {$urlcol} AS url, `related-cont-ID` AS rel");
        is_int($key) ? $qb->where('ID', $key) : $qb->where($urlcol, (string)$key);

        $r = $qb->get()->getRowArray();
        if (!$r) return null;

        $contentId = (int)($r['rel'] ?? 0);

        return [
            'title' => (string)$r['name'],
            'url'   => "/regionen/pol{$level}/" . (string)$r['url'],
            'img'   => $contentId ? $this->firstTeaserImage($contentId) : null,
            'id'    => $contentId ?: null,
            'identifier' => null,
        ];
    }

    public function lexikonTeaser(string $identifier): ?array
    {
        $c = $this->db->table('wr_content')
            ->select('id, title, identifier, status')
            ->where('status', 1)
            ->where('identifier', $identifier)
            ->get()->getRowArray();
        if (!$c) return null;

        return [
            'title' => (string)$c['title'],
            'url'   => '/lexikon/' . (string)$c['identifier'],
            'img'   => $this->firstTeaserImage((int)$c['id']),
            'id'    => (int)$c['id'],
            'identifier' => (string)$c['identifier'],
        ];
    }

    public function resolveContentIdByProducerMapping(int $producerId): ?int
    {
        $prod = $this->db->table('wr_prod_producer')
            ->select('ID, identifier_prod')
            ->where('ID', $producerId)
            ->get()->getRowArray();
        if (!$prod) return null;

        $ident = (string)$prod['identifier_prod'];

        // 1) bevorzugt: Content.identifier == identifier_prod
        $c = $this->db->table('wr_content')
            ->select('id')
            ->where('status', 1)
            ->where('identifier', $ident)
            ->orderBy('date_mod', 'DESC')
            ->get(1)->getRowArray();
        if ($c) return (int)$c['id'];

        // 2) Fallback: producer_ID
        $c2 = $this->db->table('wr_content')
            ->select('id')
            ->where('status', 1)
            ->where('producer_ID', (int)$prod['ID'])
            ->orderBy('date_mod', 'DESC')
            ->get(1)->getRowArray();
        return $c2 ? (int)$c2['id'] : null;
    }

// app/Services/HomeTeaserService.php
private function firstTeaserImage(int $contentId): ?string
{
    // Kandidaten ohne area-Filter, Bild-Typen priorisieren
    $slices = $this->db->table('wr_content_slice s')
        ->select('s.id AS sid, LOWER(COALESCE(s.type,"")) AS type')
        ->where('s.content_id', $contentId)
        ->orderBy("CASE LOWER(COALESCE(s.type,'')) 
                      WHEN 'hero' THEN 0 
                      WHEN 'image' THEN 1 
                      WHEN 'img' THEN 1
                      WHEN 'image_text' THEN 2 
                      WHEN 'text_image' THEN 2 
                      ELSE 9 END", 'ASC', false)
        ->orderBy('s.position','ASC')
        ->get()->getResultArray();

    $fileFields = ['image','img','bild','imagebreitflach','imagebreit','imagefile','file','filename','src'];

    foreach ($slices as $s) {
        $sid = (int)$s['sid'];

        $rows = $this->db->table('wr_content_slice_data')
            ->select('LOWER(name) AS name, value, position')
            ->where('content_slice_id', $sid)
            ->orderBy('position','ASC')
            ->get()->getResultArray();

        $file = null;
        foreach ($rows as $d) {
            if (in_array($d['name'], $fileFields, true)) {
                $val = trim((string)$d['value']);
                // JSON->filename zulassen
                if ($val !== '' && $val[0] === '{') {
                    $j = json_decode($val, true);
                    if (is_array($j) && !empty($j['filename'])) $val = (string)$j['filename'];
                }
                if ($val !== '') { $file = ltrim($val, '/'); break; }
            }
        }
        if ($file === null) continue;

        // Größenpräfix ergänzen, wenn keins vorhanden
        if (!preg_match('/^(xss|xs|sm|md|lg|llg|hxs|hmd|hxorg)_/i', $file)) {
            $file = 'lg_'.$file;
        }

        // Varianten sicherstellen
        try { service('imagePipelineService')->ensure($contentId, $sid); } catch (\Throwable $e) {}

        // Neues Schema bevorzugen, sonst Altstruktur
        $pNew = "/_data/{$contentId}/{$sid}/output/{$file}";
        $pOld = "/_data/{$contentId}/output/{$file}";
        return is_file(FCPATH.$pNew) ? $pNew : $pOld;
    }
    return null;
}



}
