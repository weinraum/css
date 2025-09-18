<?php declare(strict_types=1);

namespace App\Domain;

final class ContentAggregate
{
    public function __construct(
        public array $content = [],     // row aus wr_content (inkl. 'identifier', 'status', ...)
        public array $categories = [],  // rows aus wr_category (id, parent_id, identifier, status, position)
        public array $slices = []       // [['id','type','area','position','data'=>[name=>value]]...]
    ) {}
}
