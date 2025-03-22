<?php

namespace App\Livewire\Sidebar;

use App\Enums\EventEnum;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

#[On([EventEnum::WORKSTREAM_CREATED->value, EventEnum::WORKSTREAM_DELETED->value, EventEnum::WORKSTREAM_UPDATED->value])]
class FeaturedWorkstreams extends Component
{
    public function render(UserService $userService): View
    {
        return view('livewire.sidebar.featured-workstreams', [
            'featuredWorkstreams' => $userService->getWorkstreams(),
        ]);
    }
}
