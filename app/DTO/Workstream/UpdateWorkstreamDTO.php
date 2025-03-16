<?php

namespace App\DTO\Workstream;

use App\Models\Organization;
use Spatie\LaravelData\Data;

class UpdateWorkstreamDTO extends Data
{
    public function __construct(
        public Organization $organization,
        public int $workstream_id,
        public string $name,
        public ?int $priority_id
    ) {}

    public static function rules(): array
    {
        return [
            'workstream_id' => ['nullable', 'integer'],
            ...CreateWorkstreamDTO::rules(),
        ];
    }
}
