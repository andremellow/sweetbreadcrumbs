<?php

namespace App\Policies;

use App\Enums\CapabilityEnum;
use App\Models\Capability;
use App\Models\Meeting;
use App\Models\User;
use App\Models\Workstream;
use App\Services\UserService;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeetingPolicy
{
    use HandlesAuthorization;

    public function __construct(protected UserService $userService)
    {

    }

    public function viewAny(User $user, Workstream $workstream): bool
    {
        return $this->userService->setUser($user)->can(CapabilityEnum::VIEW_MEETINGS, $workstream);
    }



    public function create(User $user): bool
    {
    }

    public function update(User $user, Meeting $meeting): bool
    {
    }

    public function delete(User $user, Meeting $meeting): bool
    {
    }

    public function restore(User $user, Meeting $meeting): bool
    {
    }

    public function forceDelete(User $user, Meeting $meeting): bool
    {
    }
}
