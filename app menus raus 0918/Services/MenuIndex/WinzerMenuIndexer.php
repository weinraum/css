<?php namespace App\Services\MenuIndex;

use CodeIgniter\Database\BaseConnection;

final class WinzerMenuIndexer
{
    public function __construct(
        private BaseConnection $tradDb,
        private BaseConnection $contentDb
    ) {}

    public function rebuild(): void
    {
        $this->contentDb->transStart();
        $this->contentDb->table('wr_menu_winzer_index')->truncate();

        // Winzer je L3 (Appellation); Fallback auf L2, falls keine L3-Zuordnung
        $q = $this->tradDb->table('wr_prod_producer p')
            ->select("
                p.ID              AS producer_id,
                COALESCE(NULLIF(TRIM(p.identifier_prod),''), CONCAT('prod-',p.ID)) AS identifier,
                p.name_prod       AS title,
                COALESCE(l3.name_pol3_url, l2.name_pol2_url) AS region_identifier,
                p.show_prod       AS active
            ")
            ->join('wr_prodOrdLev_3 l3', 'l3.ID = p.pol3_ID', 'left')
            ->join('wr_prodOrdLev_2 l2', 'l2.ID = p.pol2_ID', 'left')
            ->where('p.show_prod', 1);

        foreach ($q->get()->getResultArray() as $row) {
            if (empty($row['region_identifier'])) {
                continue; // ohne Region kein Eintrag
            }
            $this->contentDb->table('wr_menu_winzer_index')->insert([
                'producer_id'       => (int)$row['producer_id'],
                'identifier'        => $row['identifier'],            // → identifier (kanonisch)
                'title'             => $row['title'],
                'region_identifier' => $row['region_identifier'],      // → region_identifier (kanonisch)
                'is_active'         => (int)$row['active'] ? 1 : 0,
            ]);
        }

        $this->contentDb->transComplete();
    }
}
