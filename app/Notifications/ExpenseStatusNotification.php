<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ExpenseStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $status,
        public float $amount,
        public string $projectName
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Expense ' . $this->status . ' — $' . number_format($this->amount),
            'body' => 'Project: ' . $this->projectName,
            'type' => 'budget',
        ];
    }
}