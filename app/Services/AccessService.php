<?php

namespace App\Services;

use App\Actions\Task\CloseTask;
use App\Actions\Task\CreateTask;
use App\Actions\Task\DeleteTask;
use App\Actions\Task\OpenTask;
use App\Actions\Task\UpdateTask;
use App\Contracts\AccessibleContract;
use App\DTO\Task\CloseTaskDTO;
use App\DTO\Task\CreateTaskDTO;
use App\DTO\Task\DeleteTaskDTO;
use App\DTO\Task\OpenTaskDTO;
use App\DTO\Task\UpdateTaskDTO;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AccessService
{
    public function __construct(
        protected UserService $userService,
        //        protected CloseTask $closeTask,
        //        protected OpenTask $openTask,
        //        protected CreateTask $createTask,
        //        protected UpdateTask $updateTask,
        //        protected DeleteTask $deleteTask,
    ) {}

    public function list(
        AccessibleContract $accessible,
    ): LengthAwarePaginator {

        return $accessible->accesses()->with('user', 'role')
            ->leftJoin('users', 'accesses.user_id', '=', 'users.id')
            ->orderBy('users.first_name')
            ->orderBy('users.last_name')
            ->select('accesses.*') // make sure to select workstreams columns
            ->paginate(config('app.pagination_items'));
    }

    public function listAutoComplete(AccessibleContract $accessible, string $search): Collection
    {

        return $this->userService->getCurrentOrganization()
            ->users()
            ->whereNotExists(function ($query) use ($accessible) {
                $query->select(DB::raw(1))
                    ->from('accesses')
                    ->whereColumn('accesses.user_id', 'users.id')
                    ->where('accesses.accessible_id', '=', $accessible->id)
                    ->where('accesses.accessible_type', '=', $accessible::class);
            })->whereAny([
                'first_name',
                'last_name',
                'email',
            ], 'like', "%$search%")
            ->limit(10)
            ->get();

    }

    //    /**
    //     * Creates a new task.
    //     *
    //     * @param User          $user,
    //     * @param CreateTaskDTO $createTaskDTO,
    //     *
    //     * @return Task
    //     */
    //    public function create(
    //        CreateTaskDTO $createTaskDTO
    //    ): Task {
    //        return ($this->createTask)($createTaskDTO);
    //    }
    //
    //    /**
    //     * Update an existing task.
    //     *
    //     * @param UpdateTaskDTO $updateTaskDTO
    //     *
    //     * @return Task
    //     */
    //    public function update(
    //        UpdateTaskDTO $updateTaskDTO
    //    ): Task {
    //        return ($this->updateTask)(
    //            $updateTaskDTO
    //        );
    //    }
    //
    //    /**
    //     * Close a Task.
    //     *
    //     * @param CloseTaskDTO $closeTaskDTO
    //     *
    //     * @return Task
    //     */
    //    public function close(
    //        CloseTaskDTO $closeTaskDTO
    //    ): Task {
    //        return ($this->closeTask)(
    //            $closeTaskDTO
    //        );
    //    }
    //
    //    /**
    //     * Open a Task.
    //     *
    //     * @param OpenTaskDTO $openTaskDTO
    //     *
    //     * @return Task
    //     */
    //    public function open(
    //        OpenTaskDTO $openTaskDTO
    //    ): Task {
    //        return ($this->openTask)(
    //            $openTaskDTO
    //        );
    //    }
    //
    //    /**
    //     * Delete a new task.
    //     *
    //     * @param User          $user,
    //     * @param DeleteTaskDTO $deleteTaskDTO,
    //     *
    //     * @return void
    //     */
    //    public function delete(
    //        DeleteTaskDTO $deleteTaskDTO
    //    ): void {
    //        ($this->deleteTask)($deleteTaskDTO);
    //    }
}
