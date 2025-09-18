<?php declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * "Ein Content pro Winzer": Lookup über wr_content.producer_ID.
 * Optionaler Aufruf per wr_prod_producer.identifier_prod.
 */
final class Winzer extends BaseController
{
    /** Aufruf per numerischer Winzer-ID */
    public function show(int $producerId)
    {
        $cid = $this->resolveContentIdForProducer($producerId);
        if (!$cid) throw PageNotFoundException::forPageNotFound();

        $agg = service('contentService')->byId($cid);
        if ((int)($agg->content['status'] ?? 0) !== 1) throw PageNotFoundException::forPageNotFound();

        return view('content/show', ['agg' => $agg]);
    }

    /** Optional: Aufruf per identifier_prod */
    public function ident(string $identifierProd)
    {
        $db = db_connect('content');
        $prod = $db->table('wr_prod_producer')
            ->select('ID')
            ->where('identifier_prod', $identifierProd)
            ->get()->getRowArray();
        if (!$prod) throw PageNotFoundException::forPageNotFound();
        return $this->show((int)$prod['ID']);
    }

    private function resolveContentIdForProducer(int $producerId): ?int
    {
        $db = db_connect('content');
        $row = $db->table('wr_content')
            ->select('id')
            ->where('status', 1)
            ->where('producer_ID', $producerId)
            ->orderBy('date_mod', 'DESC')
            ->get(1)->getRowArray();

        return $row ? (int)$row['id'] : null;
    }
}
