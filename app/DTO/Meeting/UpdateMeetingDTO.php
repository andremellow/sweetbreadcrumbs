<?php

namespace App\DTO\Meeting;

use App\Models\Workstream;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class UpdateMeetingDTO extends Data
{
    public function __construct(
        public Workstream $workstream,
        public int $meeting_id,
        public string $name,
        public string $description,
        #[WithCast(DateTimeInterfaceCast::class)]
        public Carbon $date,
    ) {}

    public static function rules(): array
    {
        return [
            'meeting_id' => ['required', 'integer'],
            ...CreateMeetingDTO::rules(),
        ];
    }
}
