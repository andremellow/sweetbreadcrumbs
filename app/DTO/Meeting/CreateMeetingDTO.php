<?php

namespace App\DTO\Meeting;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class CreateMeetingDTO extends Data
{
    public function __construct(
        public Project $project,
        public string $name,
        public string $description,
        public Carbon $date,
    ) {}

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:50'],
            'description' => ['required', 'string', 'min:2'],
            'date' => ['required', Rule::date()],
        ];
    }
}
