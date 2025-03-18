<?php

namespace App\Livewire\Workstream;

use App\Enums\EventEnum;
use App\Models\Workstream;
use Livewire\Attributes\On;
use Livewire\Component;

#[On([EventEnum::WORKSTREAM_CREATED->value, EventEnum::WORKSTREAM_UPDATED->value])]
class Dashboard extends Component
{
    public Workstream $workstream;

    public function render()
    {
        return view('livewire.workstream.dashboard');
    }
}
