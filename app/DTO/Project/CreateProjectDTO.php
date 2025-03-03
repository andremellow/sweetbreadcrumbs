<?php

namespace App\DTO\Project;

use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class CreateProjectDTO extends Data
{
    public function __construct(
        public Organization $organization,
        public string $name,
        public ?int $priority_id,
        public ?int $toggle_on_by_release_id,
        public ?string $release_plan,
        public ?string $technical_documentation,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y/m/d')]
        public ?Carbon $needs_to_start_by,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y/m/d')]
        public ?Carbon $needs_to_deployed_by,
    ) {}

    public static function rules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:50'],
            'priority_id' => ['nullable', 'integer'],
            'toggle_on_by_release_id' => ['nullable', 'integer'],
            'release_plan' => ['nullable', 'string'],
            'technical_documentation' => ['nullable', 'string'],
            'needs_to_start_by' => ['nullable', Rule::date()->format('Y/m/d')],
            'needs_to_deployed_by' => ['nullable', Rule::date()->format('Y/m/d')],
        ];
    }
}
