<?php

namespace App\Livewire\Welcome;

use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Profile extends Component
{
    public string $first_name;

    public string $last_name;

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function update(UserService $userService) // @pest-ignore-type
    {
        $validated = $this->validate();

        $user = Auth::user();

        $user->fill($validated);

        $user->save();

        $organization = $userService->getCurrentOrganization();

        if ($organization) {
            return $this->redirect(route('dashboard', ['organization' => $organization->slug]));
        }

        return $this->redirect(route('welcome.organization'));
    }

    #[Layout('components.layouts.welcome')]
    public function render(): View
    {
        return view('livewire.welcome.profile');
    }
}
