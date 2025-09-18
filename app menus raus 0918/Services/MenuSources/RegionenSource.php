<?php namespace App\Services\MenuSources;

use CodeIgniter\Database\BaseConnection;

/**
 * Baut eine Regionen-Quelle aus der Index-Tabelle wr_menu_region_index.
 * Optionaler Prefix (z. B. "/wein/regionen/" oder "/regionen/").
 * Ergebnisformat: {"items":[ {"identifier": "...", "title": "...", "url": "..."} , ... ]}
 */
final class RegionenSource
{
    public function __construct(private BaseConnection $db) {}

    public function getJson(string $locale = 'de', string $prefix = '/wein/regionen/'): string
    {
        $rows = $this->db->table('wr_menu_region_index')
            ->select('identifier, title')
            ->where('is_active', 1)
            ->orderBy('title', 'ASC')
            ->get()->getResultArray();

        $items = [];
        foreach ($rows as $r) {
            $ident = (string)$r['identifier'];
            $items[] = [
                'identifier' => $ident,
                'title'      => (string)$r['title'],
                'url'        => rtrim($prefix, '/').'/'.$ident,
            ];
        }

        return json_encode(['items' => $items], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
}
