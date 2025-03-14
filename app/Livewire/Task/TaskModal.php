<?php

namespace App\Livewire\Task;

use App\Enums\ConfigEnum;
use App\Livewire\Forms\TaskForm;
use App\Models\Project;
use App\Services\ConfigService;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Livewire\Attributes\On;
use Livewire\Component;

class TaskModal extends Component
{
    public TaskForm $form;

    public $showTaskFormModal = false;

    public function mount(ConfigService $configService, Project $project)
    {
        $this->form->project = $project;
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
            $this->dispatch('task-created', taskId: $task->id);
        } 
        // else {
        //     $this->form->edit($taskService);
        //     $this->dispatch('task-updated', taskId: $this->form->id);
        // }

        $this->form->resetForm();
        $this->showTaskFormModal = false;
    }

    public function render()
    {
        return view('livewire.task.task-modal');
    }
}
