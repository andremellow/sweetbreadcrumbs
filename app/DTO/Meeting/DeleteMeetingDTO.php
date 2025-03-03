<?php

namespace App\DTO\Meeting;

use App\Models\Meeting;
use Spatie\LaravelData\Data;

class DeleteMeetingDTO extends Data
{
    public function __construct(
        public Meeting $meeting
    ) {}
}
