<?php declare(strict_types=1);

namespace App\Services;

use App\Domain\ContentAggregate;
use CodeIgniter\Cache\CacheInterface;
use CodeIgniter\Database\BaseConnection;

final class ContentService
{
    public function __construct(
        private BaseConnection $dbTrad,   // kommt aus db_connect('trad')
        private CacheInterface $cache
    ) {}

    /** Vollständiges Aggregate (content+categories+slices) aus 'trad'. */
    public function byId(int $contentId): ContentAggregate
    {
$ckey = "content_agg_{$contentId}";
        $cached = $this->cache->get($ckey);
        if (is_array($cached)) {
            return new ContentAggregate(
                $cached['content'] ?? [],
                $cached['categories'] ?? [],
                $cached['slices'] ?? []
            );
        }

        // wr_content
        $content = $this->dbTrad->table('wr_content')
            ->where('id', $contentId)
            ->get()->getRowArray() ?: [];

        if (!$content) {
            return new ContentAggregate([], [], []);
        }

        // Kategorien
        $categories = $this->dbTrad->table('wr_content2category cc')
            ->select('c.id, c.parent_id, c.identifier, c.status, c.position')
            ->join('wr_category c', 'c.id = cc.category_id')
            ->where('cc.content_id', $contentId)
            ->orderBy('c.position', 'ASC')
            ->get()->getResultArray();

        // Slices
        $slicesRows = $this->dbTrad->table('wr_content_slice')
            ->select('id, content_id, position, type')
            ->where('content_id', $contentId)
            ->orderBy('position', 'ASC')
            ->get()->getResultArray();

        $sliceIds = array_column($slicesRows, 'id');
        $dataById = [];
        if ($sliceIds) {
            $dataRows = $this->dbTrad->table('wr_content_slice_data')
                ->select('id, content_slice_id, name, value, position')
                ->whereIn('content_slice_id', $sliceIds)
                ->orderBy('position', 'ASC')
                ->get()->getResultArray();

            // Map auf name=>value (letzte Position gewinnt)
            foreach ($dataRows as $r) {
                $cid = (int)$r['content_slice_id'];
                $dataById[$cid] ??= [];
                $dataById[$cid][$r['name']] = $r['value'];
            }
        }

        $slices = [];
        foreach ($slicesRows as $sl) {
            $sid = (int)$sl['id'];
            $slices[] = [
                'id'       => $sid,
                'type'     => (string)($sl['type'] ?? ''),
                'position' => (int)($sl['position'] ?? 0),
                'data'     => $dataById[$sid] ?? [],
            ];
        }

        $aggArr = ['content' => $content, 'categories' => $categories, 'slices' => $slices];
        $this->cache->save($ckey, $aggArr, 600);

        return new ContentAggregate($content, $categories, $slices);
    }
}
