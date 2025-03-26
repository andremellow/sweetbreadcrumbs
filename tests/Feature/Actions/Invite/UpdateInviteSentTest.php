<?php

use App\Actions\Invite\UpdateInviteSent;
use App\DTO\Invite\UpdateInviteSentDTO;
use App\Models\Invite;
use Carbon\Carbon;

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
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
