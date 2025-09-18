<?php namespace App\Repositories;

interface ContentRepositoryInterface
{
    public function findContentIdByProducerId(int $producerId): ?int;             // status = 1
    public function loadContentMeta(int $contentId): ?array;                        // wr_content row
    public function loadSlicesForContent(int $contentId): array;                    // ordered by position
    public function loadSliceDataForSlices(array $sliceIds): array;                 // grouped by content_slice_id
}
