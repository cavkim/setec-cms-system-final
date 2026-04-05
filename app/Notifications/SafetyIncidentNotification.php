<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SafetyIncidentNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $description,
        public string $severity,
        public string $location = ''
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'New safety incident reported',
            'body' => ucfirst($this->severity) . ' incident: ' . \Illuminate\Support\Str::limit($this->description, 60),
            'type' => 'safety',
        ];
    }
}