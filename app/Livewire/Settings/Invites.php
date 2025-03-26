<?php

namespace App\Livewire\Settings;

use App\DTO\Invite\AcceptInviteDTO;
use App\DTO\Invite\DeleteInviteDTO;
use App\Exceptions\CreateInviteException;
use App\Services\InviteService;
use App\Services\UserService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Invites extends Component
{
    public function accept(UserService $userService, InviteService $inviteService, int $id): void
    {
        try {
            $inviteService->acceptInvite(new AcceptInviteDTO(
                user: Auth::user(),
                invite: $userService->getInviteById(id: $id)
            ));

            Flux::toast(text: __('Invite accepted'));
        } catch (CreateInviteException $e) {
            Flux::toast(variant: 'danger', text: __(config('app.error_message')).' - '.$e->getMessage());
        }
    }

    public function decline(UserService $userService, InviteService $inviteService, int $id): void
    {
        $invite = $userService->getInviteById(id: $id);
        $inviteService->delete(new DeleteInviteDTO(
            user: Auth::user(),
            organization: $invite->organization,
            invite_id: $id
        ));

        Flux::toast(variant: 'success', text: __('Invite declined'));
    }

    #[Layout('components.layouts.no-sidebar-app')]
    public function render(UserService $userService): View
    {
        return view('livewire.settings.invites', [
            'invites' => $userService->getInvites(),
        ]);
    }
}
