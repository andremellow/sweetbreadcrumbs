<?php

namespace App\Livewire\Welcome;

use App\DTO\Invite\AcceptInviteDTO;
use App\DTO\Invite\DeleteInviteDTO;
use App\Exceptions\CreateInviteException;
use App\Models\Invite as ModelsInvite;
use App\Services\InviteService;
use App\Services\UserService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class AcceptInvite extends Component
{
    public string $first_name;

    public string $last_name;

    public bool $showForm = false;

    public bool $inviteBelongstoAuthenticatedUser = false;

    public ModelsInvite $invite;

    public function mount(UserService $userService, InviteService $inviteService): void
    {
        $this->inviteBelongstoAuthenticatedUser = $this->invite->email === Auth::user()->email;

        if ($userService->hasOrganization($this->invite->organization_id)) {
            $inviteService->delete(new DeleteInviteDTO(
                user: Auth::user(),
                organization: $this->invite->organization,
                invite_id: $this->invite->id
            ));

            $this->redirectToDashboad();
        }

        $this->showForm = empty(Auth::user()->first_name);
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function accept(InviteService $inviteService): void
    {
        try {
            $user = Auth::user();
            if ($this->showForm) {
                $validated = $this->validate();

                $user->fill($validated);

                $user->save();
            }

            $inviteService->acceptInvite(new AcceptInviteDTO(
                $user,
                $this->invite
            ));

            $this->redirectToDashboad();
        } catch (CreateInviteException $th) {
            Flux::toast(variant: 'danger', text: __(config('app.error_message')));
        }

    }

    public function redirectToDashboad()  // @pest-ignore-type
    {
        return $this->redirect(route('dashboard', ['organization' => $this->invite->organization->slug]));
    }

    #[Layout('components.layouts.welcome')]
    public function render(): View
    {
        $this->invite->load('organization', 'inviter');

        return view('livewire.welcome.accept-invite', [
            'organizationName' => $this->invite->organization->name,
            'inviterName' => $this->invite->inviter->fullName,
        ]);
    }
}
