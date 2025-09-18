<?php namespace App\Repositories;

use CodeIgniter\Database\BaseConnection;

final class DbContentRepository implements ContentRepositoryInterface
{
    public function __construct(private BaseConnection $db) {}

    public function findContentIdByProducerId(int $producerId): ?int
    {
        $row = $this->db->table('wr_content')
            ->select('id')
            ->where('status', 1)
            ->where('producer_ID', $producerId)
            ->orderBy('date_mod', 'DESC')
            ->get()->getRowArray();
        return $row['id'] ?? null;
    }

    public function loadContentMeta(int $contentId): ?array
    {
        return $this->db->table('wr_content')->where('id', $contentId)->get()->getRowArray() ?: null;
    }

    public function loadSlicesForContent(int $contentId): array
    {
        return $this->db->table('wr_content_slice')
            ->where('content_id', $contentId)
            ->orderBy('position', 'ASC')
            ->get()->getResultArray();
    }

    public function loadSliceDataForSlices(array $sliceIds): array
    {
        if (!$sliceIds) return [];
        $rows = $this->db->table('wr_content_slice_data')
            ->whereIn('content_slice_id', $sliceIds)
            ->orderBy('position', 'ASC')
            ->get()->getResultArray();

        $by = [];
        foreach ($rows as $r) $by[$r['content_slice_id']][] = $r;
        return $by;
    }
}
