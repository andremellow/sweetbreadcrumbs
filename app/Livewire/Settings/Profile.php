<?php

namespace App\Livewire\Settings;

use App\Enums\EventEnum;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Profile extends Component
{
    public ?string $first_name = '';

    public ?string $last_name = '';

    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->first_name = Auth::user()->first_name;
        $this->last_name = Auth::user()->last_name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['string', 'max:255'],
        ]);

        $user->fill($validated);

        $user->save();

        $this->dispatch(EventEnum::PROFILE_UPDATED->value, name: $user->name);
    }

    #[Layout('components.layouts.no-sidebar-app')]
    public function render()
    {

        return view('livewire.settings.profile');
    }
}
