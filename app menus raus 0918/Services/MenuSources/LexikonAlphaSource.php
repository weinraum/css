<?php namespace App\Services\MenuSources;

use CodeIgniter\Database\BaseConnection;

final class LexikonAlphaSource
{
    public function __construct(private BaseConnection $db) {}

    public function getJson(): string
    {
        $rows = $this->db->table('wr_menu_lexikon_index')
            ->select('identifer, title, letter')
            ->where('is_active', 1)
            ->orderBy('letter ASC, title ASC')
            ->get()->getResultArray();

        $groups = [];
        foreach ($rows as $r) {
            $L = $r['letter'];
            $groups[$L][] = [
                'title'     => $r['title'],
                'url'       => '/lexikon/'.$r['identifer'],
                'identifer' => $r['identifer'],
                'kind'      => 'term',
            ];
        }

        $items = [];
        foreach ($groups as $L => $terms) {
            $items[] = [
                'title'     => $L,
                'url'       => '/lexikon#'.$L,
                'identifer' => $L,
                'kind'      => 'letter',
                'children'  => $terms,
            ];
        }
        return json_encode(['items' => $items], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }
}
