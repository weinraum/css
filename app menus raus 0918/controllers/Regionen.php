<?php declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * "Ein Content pro Region": primär über wr_prodOrdLev_*.`related-cont-ID`
 * Fallback: wr_content.pol{1,2,3}_ID
 * Hinweis: Spalte `related-cont-ID` bleibt mit Bindestrich -> immer quoten.
 */
final class Regionen extends BaseController
{
    public function pol1(string $slugOrId)
    {
        $cid = $this->resolveRegionContentId(1, $slugOrId);
        if (!$cid) throw PageNotFoundException::forPageNotFound();

        $agg = service('contentService')->byId($cid);
        if ((int)($agg->content['status'] ?? 0) !== 1) throw PageNotFoundException::forPageNotFound();

        return view('content/show', ['agg' => $agg]);
    }

    public function pol2(string $slugOrId)
    {
        $cid = $this->resolveRegionContentId(2, $slugOrId);
        if (!$cid) throw PageNotFoundException::forPageNotFound();

        $agg = service('contentService')->byId($cid);
        if ((int)($agg->content['status'] ?? 0) !== 1) throw PageNotFoundException::forPageNotFound();

        return view('content/show', ['agg' => $agg]);
    }

    public function pol3(string $slugOrId)
    {
        $cid = $this->resolveRegionContentId(3, $slugOrId);
        if (!$cid) throw PageNotFoundException::forPageNotFound();

        $agg = service('contentService')->byId($cid);
        if ((int)($agg->content['status'] ?? 0) !== 1) throw PageNotFoundException::forPageNotFound();

        return view('content/show', ['agg' => $agg]);
    }

    private function resolveRegionContentId(int $level, string $slugOrId): ?int
    {
        $db = db_connect('content');

        // Primary: via related-cont-ID + *_url Lookup
        if ($level === 1) {
            $row = ctype_digit($slugOrId)
                ? $db->table('wr_prodOrdLev_1')->select('`related-cont-ID` AS rel')->where('ID', (int)$slugOrId)->get()->getRowArray()
                : $db->table('wr_prodOrdLev_1')->select('`related-cont-ID` AS rel')->where('name_pol1_url', $slugOrId)->get()->getRowArray();
        } elseif ($level === 2) {
            $row = ctype_digit($slugOrId)
                ? $db->table('wr_prodOrdLev_2')->select('`related-cont-ID` AS rel')->where('ID', (int)$slugOrId)->get()->getRowArray()
                : $db->table('wr_prodOrdLev_2')->select('`related-cont-ID` AS rel')->where('name_pol2_url', $slugOrId)->get()->getRowArray();
        } else {
            $row = ctype_digit($slugOrId)
                ? $db->table('wr_prodOrdLev_3')->select('`related-cont-ID` AS rel')->where('ID', (int)$slugOrId)->get()->getRowArray()
                : $db->table('wr_prodOrdLev_3')->select('`related-cont-ID` AS rel')->where('name_pol3_url', $slugOrId)->get()->getRowArray();
        }

        $cid = (int)($row['rel'] ?? 0);
        if ($cid > 0) return $cid;

        // Fallback: neuester online-wr_content mit passender polX_ID
        $b = $db->table('wr_content')->select('id')->where('status', 1)->orderBy('date_mod','DESC');
        if ($level === 1) {
            if (ctype_digit($slugOrId)) $b->where('pol1_ID', (int)$slugOrId);
        } elseif ($level === 2) {
            if (ctype_digit($slugOrId)) $b->where('pol2_ID', (int)$slugOrId);
        } else {
            if (ctype_digit($slugOrId)) $b->where('pol3_ID', (int)$slugOrId);
        }

        $fallback = $b->get(1)->getRowArray();
        return $fallback ? (int)$fallback['id'] : null;
    }
}
