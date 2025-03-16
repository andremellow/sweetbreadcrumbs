<?php

namespace App\DTO\Workstream;

use App\Models\Workstream;
use Spatie\LaravelData\Data;

class DeleteWorkstreamDTO extends Data
{
    public function __construct(
        public Workstream $workstream
    ) {}
}
