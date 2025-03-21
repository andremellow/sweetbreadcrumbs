<?php

use App\Actions\Organization\CreateOrganization;
use App\DTO\Organization\CreateOrganizationDTO;
use App\Models\Invite;
use App\Models\User;
use App\Notifications\InviteCreatedNotification;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->organization = (new CreateOrganization)($this->user, new CreateOrganizationDTO('New Organization'));
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

            return $mailMessage->actionUrl = route('invite.accept', ['token' => $this->invite->token]);
        }
    );
});
