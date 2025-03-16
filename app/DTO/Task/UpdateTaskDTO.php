<?php

namespace App\DTO\Task;

use App\Models\Workstream;
use App\Models\User;
use Spatie\LaravelData\Data;

class UpdateTaskDTO extends Data
{
    public function __construct(
        public User $user,
        public int $task_id,
        public string $name,
        public string $priority_id,
        public ?string $description = null,
        public ?string $due_date = null,
    ) {}

    public static function rules(): array
    {
        return [
            'task_id' => ['required', 'integer'],
            'name' => ['required'],
            'description' => ['nullable', 'string'],
            'priority_id' => ['required', 'integer'],
            'due_date' => ['nullable', 'date'],

        ];
    }
}
