<?php declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

final class Lexikon extends BaseController
{
    /**
     * Zeigt einen Lexikonartikel – Parameter ist entweder ID (Zahl) oder identifier (String)
     * Beispiel-Routen (behalten, falls vorhanden):
     *   GET /lexikon/artikel/ester
     *   GET /lexikon/artikel/445
     */
    public function artikel(string $identifierOrId)
    {
        $svc = service('contentService');

        $agg = ctype_digit($identifierOrId)
            ? $svc->byId((int)$identifierOrId)
            : ($svc->byIdentifier($identifierOrId) ?? new \App\Domain\ContentAggregate(['status'=>0], [], []));

        if ((int)($agg->content['status'] ?? 0) !== 1) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('content/show', ['agg' => $agg]);
    }

    /**
     * Optional: Übersicht, z. B. alle Inhalte unter Kategorie 'lexikon'
     * Nur wenn wr_category.identifier = 'lexikon' existiert
     */
    public function index()
    {
        $db   = db_connect('content');
        $cat  = $db->table('wr_category')->select('id')->where('identifier','lexikon')->get()->getRowArray();
        $list = [];
        if ($cat) {
            $list = $db->table('wr_content2category c2c')
                ->select('c.id,c.title,c.identifier,c.date_mod,c.status')
                ->join('wr_content c','c.id=c2c.content_id')
                ->where('c2c.category_id',$cat['id'])
                ->where('c.status',1)
                ->orderBy('c.date_mod','DESC')
                ->get()->getResultArray();
        }
        return view('lexikon/index', ['items'=>$list]);
    }
}
