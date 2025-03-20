<?php

namespace App\Livewire\Task;

use App\Enums\ConfigEnum;
use App\Enums\EventEnum;
use App\Livewire\Forms\TaskForm;
use App\Models\Workstream;
use App\Services\ConfigService;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Livewire\Attributes\On;
use Livewire\Component;

class TaskModal extends Component
{
    public TaskForm $form;

    public $showTaskFormModal = false;

    public function mount(ConfigService $configService, Workstream $workstream)
    {
        $this->form->workstream = $workstream;
        $this->form->priority_id = $this->form->defaultPriorityId = $configService->get(ConfigEnum::TASK_DEFAULT_PRIORITY_ID);
    }

    #[On(['load-task-form-modal'])]
    public function load(?int $taskId = null)
    {
        $this->form->maybeLoadTask($taskId);

        $this->showTaskFormModal = true;
    }

    public function onModalClose(Request $request)
    {
        $this->form->resetForm();
    }

    public function save(TaskService $taskService)
    {
        if ($this->form->id === null) {
            $task = $this->form->add($taskService);

            $this->dispatch(EventEnum::TASK_CREATED->value, taskId: $task->id);
        } else {
            $task = $this->form->edit($taskService);
            $this->dispatch(EventEnum::TASK_UPDATED->value, taskId: $task->id);
            $this->dispatch(EventEnum::TASK_UPDATED->value.".{$task->id}");
        }

        $this->form->resetForm();
        $this->showTaskFormModal = false;
    }

    public function render()
    {
        return view('livewire.task.task-modal');
    }
}
