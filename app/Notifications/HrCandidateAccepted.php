<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HrCandidateAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    /**
     * Create a new notification instance.
     */
    public function __construct(JobApplication $application)
    {
        $this->application = $application;
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
        $jobTitle = $this->application->jobPosting->title;
        $candidateName = $this->application->name;
        $hodFeedback = $this->application->hod_feedback;
        $department = $this->application->jobPosting->jobRequest->department->name;

        $message = (new MailMessage)
            ->subject("New Candidate Accepted - {$jobTitle}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("A new candidate has been accepted by the HOD for the position of {$jobTitle} in {$department}.")
            ->line("Candidate Details:")
            ->line("Name: {$candidateName}")
            ->line("Email: {$this->application->email}")
            ->line("Phone: {$this->application->phone}")
            ->action('Schedule Interview', url('/hr/applications/' . $this->application->id));

        if ($hodFeedback) {
            $message->line("")
                   ->line("HOD's Feedback:")
                   ->line($hodFeedback);
        }

        return $message->line("")
                      ->line("Please schedule an interview with the candidate at your earliest convenience.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'job_title' => $this->application->jobPosting->title,
            'candidate_name' => $this->application->name,
            'department' => $this->application->jobPosting->jobRequest->department->name,
            'hod_feedback' => $this->application->hod_feedback
        ];
    }
} 