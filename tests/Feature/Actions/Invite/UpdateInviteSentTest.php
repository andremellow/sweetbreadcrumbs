<?php

use App\Actions\Invite\UpdateInviteSent;
use App\Actions\Organization\CreateOrganization;
use App\DTO\Invite\UpdateInviteSentDTO;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Invite;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization Name'));
    $this->invite = Invite::factory()->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create();
});

it('updates a workstream with all fields', function () {
    $updatedInvite = (new UpdateInviteSent)(
        UpdateInviteSentDTO::from([
            'user' => $this->user,
            'organization' => $this->organization,
            'invite_id' => $this->invite->id,
        ])
    );

    $updatedInvite->refresh();

    expect($updatedInvite->id)->toBe($this->invite->id);
    expect($updatedInvite->sent_at->format('Y-m-d H:i'))->toBe(Carbon::now()->format('Y-m-d H:i'));

});
