<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\JobPosting;

class NewJobPosted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $jobPosting;

    /**
     * Create a new notification instance.
     */
    public function __construct(JobPosting $jobPosting)
    {
        $this->jobPosting = $jobPosting;
    }

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
            ->subject('New Job Opening at SZABIST')
            ->greeting('Hello!')
            ->line('A new job position has been posted that might interest you:')
            ->line('Position: ' . $this->jobPosting->title)
            ->line('Department: ' . optional($this->jobPosting->jobRequest->department)->name)
            ->action('View Job Details', url('/jobs/' . $this->jobPosting->id))
            ->line('If you wish to unsubscribe from these notifications, click here:')
            ->action('Unsubscribe', url('/unsubscribe/' . $notifiable->email));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'job_id' => $this->jobPosting->id,
            'title' => $this->jobPosting->title,
            'department' => optional($this->jobPosting->jobRequest->department)->name,
        ];
    }
}