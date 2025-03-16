<?php

namespace App\Livewire\Sidebar;

use App\Services\UserService;
use Livewire\Attributes\On;
use Livewire\Component;

#[On(['workstream-created', 'workstream-deleted', 'workstream-updated'])]
class FeaturedWorkstreams extends Component
{
    public function render(UserService $userService)
    {
        return view('livewire.sidebar.featured-workstreams', [
            'featuredWorkstreams' => $userService->getWorkstreams(),
        ]);
    }
}
