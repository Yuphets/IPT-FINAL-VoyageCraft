<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeEmail extends Notification
{
    use Queueable;

    public function __construct(public User $user) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to Travel Itinerary Planner!')
            ->greeting('Hello ' . $this->user->name . '!')
            ->line('Thank you for joining our travel planning community.')
            ->line('Start creating your first itinerary and share it with friends.')
            ->action('Create Itinerary', url('/dashboard'))
            ->line('Happy travels!');
    }
}
