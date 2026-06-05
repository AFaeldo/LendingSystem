<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLendingNotification extends Notification
{
    use Queueable;

    // Maghahanda tayo ng public variable para hawakan ang mensahe ng notif
    public $message;
    public $type;

    /**
     * Create a new notification instance.
     * Tatanggap ito ng custom message at type kapag tinawag sa engine/controller natin
     */
    public function __construct(string $message, string $type = 'info')
    {
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     * Pinalitan natin mula ['mail'] patungong ['database'] para ma-save sa tables natin
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     * Dito sine-save ang impormasyon na babasahin ng ating Bell Icon Dropdown UI
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'type' => $this->type,
            'action_url' => route('reports.index'), // Dadalhin sila sa reports kapag clinick
        ];
    }
}
