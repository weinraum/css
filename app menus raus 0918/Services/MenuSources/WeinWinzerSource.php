<?php namespace App\Services\MenuSources;

use CodeIgniter\Database\BaseConnection;

final class WeinWinzerSource
{
    public function __construct(private BaseConnection $db) {}

    public function getJson(): string
    {
        $regions = $this->db->table('wr_menu_region_index')
            ->select('identifer, title')
            ->where(['level'=>2,'is_active'=>1])
            ->orderBy('pos ASC, title ASC')
            ->get()->getResultArray();

        $winzer = $this->db->table('wr_menu_winzer_index')
            ->select('identifer, title, region_identifer')
            ->where('is_active', 1)
            ->orderBy('title ASC')
            ->get()->getResultArray();

        $byRegion = [];
        foreach ($winzer as $w) $byRegion[$w['region_identifer']][] = $w;

        $items = [];
        foreach ($regions as $r) {
            $children = [];
            foreach ($byRegion[$r['identifer']] ?? [] as $w) {
                $children[] = [
                    'title'     => $w['title'],
                    'url'       => '/winzer/'.$w['identifer'],
                    'identifer' => $w['identifer'],
                    'kind'      => 'winzer',
                ];
            }
            if ($children) {
                $items[] = [
                    'title'     => $r['title'],
                    'url'       => '/wein/regionen/'.$r['identifer'],
                    'identifer' => $r['identifer'],
                    'kind'      => 'region',
                    'children'  => $children,
                ];
            }
        }

        return json_encode(['items'=>$items], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
}
