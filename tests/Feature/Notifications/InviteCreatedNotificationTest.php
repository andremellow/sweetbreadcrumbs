<?php

use App\Models\Invite;
use App\Notifications\InviteCreatedNotification;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    [$user, $organization] = createOrganization();
    $this->user = $user;
    $this->organization = $organization;
    $this->invite = Invite::factory()->for($this->organization)->for($this->user, 'inviter')->withRole($this->organization)->create();

    Context::add('current_organization', $this->organization);

});

it('sends an email', function () {
    // Fake notifications
    Notification::fake();

    // Send the notification
    $this->invite->notify(new InviteCreatedNotification);

    // Assert that the email content is as expected
    Notification::assertSentTo(
        [$this->invite],
        InviteCreatedNotification::class,
        function ($notification) {
            $mailMessage = $notification->toMail($this->invite);

            return $mailMessage->actionUrl = route('invite.accept', ['invite' => $this->invite->token]);
        }
    );
});
