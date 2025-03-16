<?php

namespace App\DTO\Task;

use App\Models\User;
use Spatie\LaravelData\Data;

class DeleteTaskDTO extends Data
{
    public function __construct(
        public User $user,
        public int $task_id,
    ) {}

    public static function rules(): array
    {
        return [
            'task_id' => ['required', 'integer'],
        ];
    }
}
