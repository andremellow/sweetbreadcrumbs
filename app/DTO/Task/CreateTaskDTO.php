<?php

namespace App\DTO\Task;

use App\Models\Project;
use App\Models\User;
use Spatie\LaravelData\Data;

class CreateTaskDTO extends Data
{
    public function __construct(
        public User $user,
        public Project $project,
        public string $name,
        public ?string $description,
        public ?string $priority_id,
        public ?string $due_date,
    ) {}

    public static function rules(): array
    {
        return [
            'name' => ['required'],
            'description' => ['nullable', 'string'],
            'priority_id' => ['required', 'integer'],
            'due_date' => ['nullable', 'date'],
            
        ];
    }
}
