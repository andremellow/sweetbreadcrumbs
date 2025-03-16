<?php

namespace App\DTO\Task;

use App\Models\Workstream;
use App\Models\User;
use Spatie\LaravelData\Data;

class CreateTaskDTO extends Data
{
    public function __construct(
        public User $user,
        public Workstream $taskable,
        public string $name,
        public string $priority_id,
        public ?string $description = null,
        public ?string $due_date = null,
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
