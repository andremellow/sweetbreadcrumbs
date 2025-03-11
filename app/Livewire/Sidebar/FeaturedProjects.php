<?php

namespace App\Livewire\Sidebar;

use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

#[On(['project-created', 'project-deleted', 'project-updated'])] 
class FeaturedProjects extends Component
{
    public function render(UserService $userService)
    {
        return view('livewire.sidebar.featured-projects', [
            'featuredProjects' => $userService->getProjects()
        ]);
    }
}
