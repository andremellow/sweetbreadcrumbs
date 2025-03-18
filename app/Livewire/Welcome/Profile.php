<?php

namespace App\Livewire\Welcome;

use App\Services\UserService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Profile extends Component
{
    public string $first_name;

    public string $last_name;

    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function update(UserService $userService)
    {
        $validated = $this->validate();

        $user = auth()->user();

        $user->fill($validated);

        $user->save();

        $organization = $userService->getCurrentOrganization();

        if ($organization === null) {
            return $this->redirect(route('welcome.organization'));
        }

        return $this->redirect(route('dashboard', ['organization' => $organization->slug]));
    }

    #[Layout('components.layouts.welcome')]
    public function render()
    {
        return view('livewire.welcome.profile');
    }
}
