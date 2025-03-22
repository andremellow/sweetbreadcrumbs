<?php

namespace App\Livewire\Workstream;

use App\Enums\EventEnum;
use App\Models\Workstream;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

#[On([EventEnum::WORKSTREAM_CREATED->value, EventEnum::WORKSTREAM_UPDATED->value])]
class Dashboard extends Component
{
    public Workstream $workstream;

    public function render(): View
    {
        return view('livewire.workstream.dashboard');
    }
}
