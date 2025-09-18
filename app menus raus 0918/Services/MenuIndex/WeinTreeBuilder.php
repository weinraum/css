<?php namespace App\Services\MenuIndex;

use CodeIgniter\Database\BaseConnection;

final class WeinTreeBuilder
{
    public function __construct(private BaseConnection $contentDb) {}

    public function rebuild(string $locale = 'de'): void
    {
        // 1) Regionen holen, in Levels gruppieren
        $rows = $this->contentDb->table('wr_menu_region_index')
            ->select('level, identifier, title, parent_identifier, pos')
            ->where('is_active', 1)
            ->orderBy('level ASC, pos ASC, title ASC')
            ->get()->getResultArray();

        $byId = $children = [];
        foreach ($rows as $r) {
            $id = $r['identifier'];
            $byId[$id] = ['id'=>$id,'title'=>$r['title'],'children'=>[]];
            if (!empty($r['parent_identifier'])) {
                $children[$r['parent_identifier']][] = $id;
            }
        }

        // 2) Winzer je Region anhängen (als Blätter)
        $winzerByRegion = [];
        $wq = $this->contentDb->table('wr_menu_winzer_index')
            ->select('identifier, title, region_identifier')
            ->where('is_active', 1)->get()->getResultArray();

        foreach ($wq as $w) {
            $winzerByRegion[$w['region_identifier']][] = [
                'id'    => $w['identifier'],
                'title' => $w['title'],
                'url'   => '/winzer/'.$w['identifier'],
                'type'  => 'producer',
            ];
        }

        // 3) Baum ab Root (level=1) rekursiv bauen
        $tree = [];
        foreach ($rows as $r) {
            if ((int)$r['level'] !== 1) continue;
            $tree[] = $this->buildNode($r['identifier'], $byId, $children, $winzerByRegion);
        }

        // 4) Vorgerendertes HTML (schlank, <ul/li>)
        $html = $this->renderHtml($tree);

        // 5) Snapshot speichern
        $payload = json_encode($tree, JSON_UNESCAPED_UNICODE);
        $hash = sha1($payload);
        $this->contentDb->table('wr_menu_tree')->insert([
            'section'   => 'wein',
            'locale'    => $locale,
            'hash'      => $hash,
            'tree'      => $payload,
            'html'      => $html,
        ]);
    }

    private function buildNode(string $id, array &$byId, array &$children, array &$winzerByRegion): array
    {
        $node = [
            'id'       => $id,
            'title'    => $byId[$id]['title'] ?? $id,
            'url'      => '/wein/region/'.$id,
            'children' => [],
            'type'     => 'region',
        ];
        // Unterregionen
        foreach ($children[$id] ?? [] as $cid) {
            $node['children'][] = $this->buildNode($cid, $byId, $children, $winzerByRegion);
        }
        // Winzer
        foreach ($winzerByRegion[$id] ?? [] as $p) {
            $node['children'][] = $p;
        }
        return $node;
    }

    private function renderHtml(array $tree): string
    {
        $out = '<ul class="menu-wein">';
        foreach ($tree as $n) $out .= $this->renderNode($n);
        return $out.'</ul>';
    }
    private function renderNode(array $n): string
    {
        $html = '<li class="m-node m-'.$n['type'].'"><a href="'.esc($n['url']).'">'.esc($n['title']).'</a>';
        if (!empty($n['children'])) {
            $html .= '<ul>';
            foreach ($n['children'] as $c) $html .= $this->renderNode($c);
            $html .= '</ul>';
        }
        return $html.'</li>';
    }
}
