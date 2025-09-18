<?php namespace App\Services\MenuSources;

use CodeIgniter\Database\BaseConnection;

final class WinzerSource
{
    public function __construct(private BaseConnection $db) {}

    public function getJson(): string
    {
        $rows = $this->db->table('wr_menu_winzer_index')
            ->select('identifier, title')
            ->where('is_active', 1)
            ->orderBy('title ASC')
            ->get()->getResultArray();

        $groups = [];
        foreach ($rows as $r) {
            $letter = $this->letter($r['title']);
            $groups[$letter][] = [
                'title'     => $r['title'],
                'url'       => '/winzer/'.$r['identifier'],
                'identifer' => $r['identifier'],
                'kind'      => 'winzer',
            ];
        }

        $items = [];
        foreach ($groups as $L => $list) {
            $items[] = [
                'title'     => $L,
                'url'       => '/winzer#'.$L,
                'identifer' => $L,
                'kind'      => 'letter',
                'children'  => $list,
            ];
        }

        return json_encode(['items'=>$items], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }

    private function letter(string $t): string
    {
        $c = mb_strtoupper(mb_substr(trim($t), 0, 1));
        return strtr($c, ['Ä'=>'A','Ö'=>'O','Ü'=>'U','ß'=>'S']);
    }
}
