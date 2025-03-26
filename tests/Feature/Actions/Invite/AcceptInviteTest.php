<?php

use App\Actions\Invite\AcceptInvite;
use App\DTO\Invite\AcceptInviteDTO;
use App\Exceptions\CreateInviteException;
use App\Models\Invite;
use App\Models\User;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;

    $this->invitee = User::factory()->create();

    $this->invite = Invite::factory()->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create([
        'email' => $this->invitee->email,
    ]);
    // actingAs($this->invitee);
    $this->organizationService = app(OrganizationService::class);
    $this->roleId = $this->organization->roles()->first()->id;
});

it('cannot accept the invite if is already member', function () {

    $this->organizationService->attachUser($this->organization, $this->invitee, $this->roleId);

    expect(DB::table('organization_user')
        ->where('user_id', $this->invitee->id)
        ->where('user_id', $this->organization->id)
        ->count()
    )->toBe(1);

    (new AcceptInvite)(
        new AcceptInviteDTO(
            user: $this->invitee,
            invite: $this->invite
        ), app(OrganizationService::class)
    );

    expect(DB::table('organization_user')
        ->where('user_id', $this->invitee->id)
        ->where('user_id', $this->organization->id)
        ->count()
    )->toBe(1);
})->throws(CreateInviteException::class, 'E-mail is already a member of the organization');

it('validates invite role exists in the organization', function () {

    $this->invite = Invite::factory()->for($this->organization)->for($this->user, 'inviter')->create([
        'email' => $this->invitee->email,
        'role_id' => 1,
    ]);

    (new AcceptInvite)(
        new AcceptInviteDTO(
            user: $this->invitee,
            invite: $this->invite
        ), app(OrganizationService::class)
    );

})->throws(CreateInviteException::class, 'Invite role does not belongs to the organization');

it('cannot accept someone elses invite', function () {

    $user = User::factory()->create();
    actingAs($user);

    (new AcceptInvite)(
        new AcceptInviteDTO(
            user: $user,
            invite: $this->invite
        ), app(OrganizationService::class)
    );

})->throws(CreateInviteException::class, 'Invite email does not belongs to authenticated user');

it('accepts the invite', function () {

    (new AcceptInvite)(
        new AcceptInviteDTO(
            user: $this->invitee,
            invite: $this->invite
        ), app(OrganizationService::class)
    );

    expect(
        $this->invitee->organizations()
            ->where('organization_id', $this->organization->id)
            ->exists()
    )->toBe(true);
});
