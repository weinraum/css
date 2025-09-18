<?php namespace App\Services;

use CodeIgniter\Database\BaseConnection;

final class MenuIndexer
{
    public function __construct(private BaseConnection $db) {}

    /** Lexikon: aus wr_content (+ wr_content2category + wr_category) → wr_menu_lexikon_index */
    public function reindexLexikon(): int
    {
        // Rohdaten holen
        $rows = $this->db->query("
            SELECT c.identifer,
                   COALESCE(NULLIF(c.title,''), c.teaser) AS title
            FROM wr_content c
            JOIN wr_content2category cc ON cc.content_id = c.id
            JOIN wr_category cat ON cat.id = cc.category_id
            WHERE cat.identifier = 'lexikon' AND c.status = 1
        ")->getResultArray();

        // Clear & bulk insert
        $this->db->table('wr_menu_lexikon_index')->truncate();
        $ins = $this->db->table('wr_menu_lexikon_index');
        $cnt = 0;
        foreach ($rows as $r) {
            $ident = (string)$r['identifer'];
            if ($ident === '') continue;
            $title = (string)($r['title'] ?? $ident);
            $letter = strtoupper(self::normalizeLetter($ident));
            $ins->ignore(true)->insert([
                'identifer' => $ident,
                'title'     => $title,
                'letter'    => $letter,
                'is_active' => 1,
            ]);
            $cnt++;
        }
        return $cnt;
    }

    /** Regionen: aus wr_prodOrdLev_1/2/3 (+ Relevanz) → wr_menu_region_index */
    public function reindexRegionen(): int
    {
        $this->db->table('wr_menu_region_index')->truncate();

        // Länder (level 1)
        $lands = $this->db->query("
            SELECT ID, name_pol1 AS title, name_pol1_url AS ident, COALESCE(pos,0) AS pos, show
            FROM wr_prodOrdLev_1
        ")->getResultArray();

        $tbl = $this->db->table('wr_menu_region_index');
        $count = 0;

        foreach ($lands as $l) {
            if ((int)$l['show'] !== 1) continue;
            $tbl->ignore(true)->insert([
                'level' => 1, 'identifer' => $l['ident'], 'title' => $l['title'],
                'parent_identifer' => null, 'pos' => (int)$l['pos'], 'is_active' => 1
            ]);
            $count++;
        }

        // Regionen (level 2)
        $regions = $this->db->query("
            SELECT r.ID, r.name_pol2 AS title, r.name_pol2_url AS ident,
                   COALESCE(r.pos_pol2_menue,0) AS pos, r.show, p1.name_pol1_url AS parent_ident
            FROM wr_prodOrdLev_2 r
            LEFT JOIN wr_prodOrdLev_1 p1 ON p1.ID = r.pol1_ID
        ")->getResultArray();

        foreach ($regions as $r) {
            if ((int)$r['show'] !== 1) continue;
            $tbl->ignore(true)->insert([
                'level' => 2, 'identifer' => $r['ident'], 'title' => $r['title'],
                'parent_identifer' => $r['parent_ident'], 'pos' => (int)$r['pos'], 'is_active' => 1
            ]);
            $count++;
        }

        // Appellationen (level 3)
        $apps = $this->db->query("
            SELECT a.ID, a.name_pol3 AS title, a.name_pol3_url AS ident,
                   COALESCE(a.pos_pol3_menue,0) AS pos, a.show, r.name_pol2_url AS parent_ident
            FROM wr_prodOrdLev_3 a
            LEFT JOIN wr_prodOrdLev_2 r ON r.ID = a.pol2_ID
        }")->getResultArray();

        foreach ($apps as $a) {
            if ((int)$a['show'] !== 1) continue;
            $tbl->ignore(true)->insert([
                'level' => 3, 'identifer' => $a['ident'], 'title' => $a['title'],
                'parent_identifer' => $a['parent_ident'], 'pos' => (int)$a['pos'], 'is_active' => 1
            ]);
            $count++;
        }

        return $count;
    }

    /** Winzer: aus wr_prod_producer (+ Region-Zuordnung + Sichtbarkeit) → wr_menu_winzer_index */
    public function reindexWinzer(): int
    {
        $this->db->table('wr_menu_winzer_index')->truncate();

        $rows = $this->db->query("
            SELECT p.ID AS producer_id,
                   COALESCE(NULLIF(p.cont2prod_identifier,''), p.identifier_prod) AS ident,
                   COALESCE(NULLIF(p.prod_alphab,''), p.name) AS title,
                   r.name_pol2_url AS region_ident,
                   p.show_prod
            FROM wr_prod_producer p
            LEFT JOIN wr_prodOrdLev_2 r ON r.ID = p.pol2_ID
        ")->getResultArray();

        $tbl = $this->db->table('wr_menu_winzer_index');
        $cnt = 0;
        foreach ($rows as $r) {
            if ((int)$r['show_prod'] !== 1) continue;
            if (empty($r['ident']) || empty($r['region_ident'])) continue;
            $tbl->ignore(true)->insert([
                'producer_id'     => (int)$r['producer_id'],
                'identifer'       => (string)$r['ident'],
                'title'           => (string)$r['title'],
                'region_identifer'=> (string)$r['region_ident'],
                'is_active'       => 1,
            ]);
            $cnt++;
        }
        return $cnt;
    }

    private static function normalizeLetter(string $ident): string
    {
        $c = mb_strtoupper(mb_substr($ident, 0, 1));
        $map = ['Ä'=>'A','Ö'=>'O','Ü'=>'U','ß'=>'S'];
        return $map[$c] ?? preg_replace('/[^A-Z]/', 'X', $c);
    }
}
