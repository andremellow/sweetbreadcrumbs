<?php

namespace App\DTO\Meeting;

use App\Models\Workstream;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class CreateMeetingDTO extends Data
{
    public function __construct(
        public Workstream $workstream,
        public string $name,
        public string $description,
        public Carbon $date,
    ) {}

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:50'],
            'description' => ['required', 'string', 'min:2'],
            'date' => ['required', 'date'],
        ];
    }
}
