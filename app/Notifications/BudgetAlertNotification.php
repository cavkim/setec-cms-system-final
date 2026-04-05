<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BudgetAlertNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $projectName,
        public string $status,
        public float $amount = 0
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $messages = [
            'submitted' => 'New expense submitted for ' . $this->projectName,
            'approved' => 'Your expense of $' . number_format($this->amount) . ' was approved',
            'rejected' => 'Your expense of $' . number_format($this->amount) . ' was rejected',
        ];

        return [
            'message' => $messages[$this->status] ?? 'Budget update on ' . $this->projectName,
            'body' => 'Project: ' . $this->projectName,
            'type' => 'budget',
        ];
    }
}