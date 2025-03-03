<?php

namespace App\DTO\Project;

use App\Models\Organization;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class UpdateProjectDTO extends Data
{
    public function __construct(
        public Organization $organization,
        public int $project_id,
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
            'project_id' => ['nullable', 'integer'],
            ...CreateProjectDTO::rules(),
        ];
    }
}
