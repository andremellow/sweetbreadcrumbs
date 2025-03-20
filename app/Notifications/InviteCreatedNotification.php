<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct() {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('You\'ve Been Invited by'.$notifiable->inviter->full_name.'!')
            ->greeting('Hello,') // Greeting using the user's name
            ->line('We are excited to invite you to join our organization.')
            ->line('As part of our team, you\'ll have access to exclusive features, resources, and more!')
            ->line('Simply click the button below to accept your invitation and get started.')
            ->action('Accept Invitation', route('invite.accept', ['token' => $notifiable->token])) // Action button to accept the invite
            ->line('If you have any questions or need help, don\'t hesitate to reach out.')
            ->line('Be sure to use this email address to access the platform.')
            ->salutation('Best regards,')
            ->line('The '.$notifiable->organization->name.' Team') // Customize with your organization’s name
            ->line('This invitation is valid for 7 days. Don\'t miss out!');

    }
}
