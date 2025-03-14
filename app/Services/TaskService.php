<?php

namespace App\Services;

use App\Actions\Task\CloseTask;
use App\Actions\Task\CreateTask;
use App\Actions\Task\DeleteTask;
use App\Actions\Task\OpenTask;
use App\Actions\Task\UpdateTask;
use App\DTO\Task\CloseTaskDTO;
use App\DTO\Task\CreateTaskDTO;
use App\DTO\Task\DeleteTaskDTO;
use App\DTO\Task\OpenTaskDTO;
use App\DTO\Task\UpdateTaskDTO;
use App\Enums\SortDirection;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService
{
    /**
     * Task Service.
     *
     * @param OrganizationService $organizationService
     * @param CloseTask           $closeTask
     * @param OpenTask            $openTask
     * @param CreateTask          $createTask
     * @param DeleteTask          $deleteTask
     */
    public function __construct(
        protected OrganizationService $organizationService,
        protected CloseTask $closeTask,
        protected OpenTask $openTask,
        protected CreateTask $createTask,
        protected UpdateTask $updateTask,
        protected DeleteTask $deleteTask,
    ) {}

    public function list(
        Project $project,
        ?string $search = null,
        ?int $priorityId = null,
        ?string $status = null,
        ?Carbon $dateStart = null,
        ?Carbon $dateEnd = null,
        ?string $sortBy = 'due_date',
        ?SortDirection $sortDirection = SortDirection::DESC,
        ?int $pageSize = null
    ): LengthAwarePaginator {

        if (! in_array($sortBy, ['name', 'due_date', 'priority'])) {
            $sortBy = 'name';
        }

        switch ($sortBy) {
            case 'priority':
                $sortBy = 'priorities.order';
                break;
        }

        return $project->tasks()->with('priority')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.id')
            ->when($search, function ($query, $search) {
                $query->whereLike('tasks.name', "%$search%")
                    ->orWhereLike('tasks.description', "%$search%");
            })
            ->when($priorityId, function ($query, $priorityId) {
                return $query->where('tasks.priority_id', '=', $priorityId);
            })
            ->when($status, function ($query, $status) {
                if ($status === 'open') {
                    return $query->whereNull('tasks.completed_at');
                } elseif ($status === 'closed') {
                    return $query->whereNotNull('tasks.completed_at');
                }
            })
            ->when($dateStart, function ($query, $dateStart) {
                return $query->whereDate('tasks.due_date', '>=', $dateStart);
            })
            ->when($dateEnd, function ($query, $dateEnd) {
                return $query->whereDate('tasks.due_date', '<=', $dateEnd);
            })
            ->orderBy($sortBy, $sortDirection->value)
            ->select('tasks.*') // make sure to select projects columns
            ->paginate($pageSize ?? config('app.pagination_items'));
    }

    public function listForCard(
        Project $project,
        ?int $priorityId = null,
        ?Carbon $dateStart = null,
        ?Carbon $dateEnd = null,
        // ?string $sortBy = 'due_date',
        // ?SortDirection $sortDirection = SortDirection::DESC,
        ?int $pageSize = null
    ): LengthAwarePaginator {

        // if (! in_array($sortBy, ['name', 'due_date', 'priority'])) {
        //     $sortBy = 'name';
        // }

        // switch ($sortBy) {
        //     case 'priority':
        //         $sortBy = 'priorities.order';
        //         break;
        // }

        return $project->tasks()->with('priority')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.id')
            ->when($priorityId, function ($query, $priorityId) {
                return $query->where('tasks.priority_id', '=', $priorityId);
            })
            ->whereNull('tasks.completed_at')
            // ->when($dateStart, function ($query, $dateStart) {
            //     return $query->whereDate('tasks.due_date', '>=', $dateStart);
            // })
            // ->when($dateEnd, function ($query, $dateEnd) {
            //     return $query->whereDate('tasks.due_date', '<=', $dateEnd);
            // })
            ->orderBy('priorities.order')
            ->orderBy('due_date')
            ->select('tasks.*') // make sure to select projects columns
            ->paginate($pageSize ?? config('app.pagination_items'));
    }

    // public function lastMeeings(
    //     Project $project,
    //     int $take = 5
    // ) {

    //     return $project->tasks()
    //             ->orderBy('date', SortDirection::DESC->value)
    //             ->take($take)
    //             ->get();
    // }

    /**
     * Creates a new task.
     *
     * @param User          $user,
     * @param CreateTaskDTO $createTaskDTO,
     *
     * @return Task
     */
    public function create(
        CreateTaskDTO $createTaskDTO
    ): Task {
        return ($this->createTask)($createTaskDTO);
    }

    /**
     * Update an existing task.
     *
     * @param UpdateTaskDTO $updateTaskDTO
     *
     * @return Task
     */
    public function update(
        UpdateTaskDTO $updateTaskDTO
    ): Task {
        return ($this->updateTask)(
            $updateTaskDTO
        );
    }

    /**
     * Close a Task.
     *
     * @param CloseTaskDTO $closeTaskDTO
     *
     * @return Task
     */
    public function close(
        CloseTaskDTO $closeTaskDTO
    ): Task {
        return ($this->closeTask)(
            $closeTaskDTO
        );
    }

    /**
     * Open a Task.
     *
     * @param OpenTaskDTO $openTaskDTO
     *
     * @return Task
     */
    public function open(
        OpenTaskDTO $openTaskDTO
    ): Task {
        return ($this->openTask)(
            $openTaskDTO
        );
    }

    /**
     * Delete a new task.
     *
     * @param User          $user,
     * @param DeleteTaskDTO $deleteTaskDTO,
     *
     * @return void
     */
    public function delete(
        DeleteTaskDTO $deleteTaskDTO
    ): void {
        ($this->deleteTask)($deleteTaskDTO);
    }
}
