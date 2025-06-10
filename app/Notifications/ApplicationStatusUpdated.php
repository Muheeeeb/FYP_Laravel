<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class ApplicationStatusUpdated extends Notification implements ShouldQueue
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
        $status = $this->application->status;
        $jobTitle = $this->application->jobPosting->title;
        $feedback = $this->application->hod_feedback ?? $this->application->hr_feedback;

        $message = (new MailMessage)
            ->subject("Your Application Status Updated - {$jobTitle}")
            ->greeting("Hello {$this->application->name}!")
            ->line("Your application for the position of {$jobTitle} has been updated.");

        if ($status === JobApplication::STATUS_ACCEPTED) {
            $message->line('Congratulations! You have been shortlisted for this position.')
                   ->line('Our HR department will contact you soon with further details about the interview process.');
        } elseif ($status === JobApplication::STATUS_INTERVIEW_SCHEDULED) {
            $interviewDate = Carbon::parse($this->application->interview_date)->format('l, F j, Y');
            $interviewTime = Carbon::parse($this->application->interview_time)->format('g:i A');
            
            $message->line('We are pleased to inform you that your interview has been scheduled.')
                   ->line('Interview Details:')
                   ->line("📅 Date: {$interviewDate}")
                   ->line("⏰ Time: {$interviewTime}")
                   ->line("📍 Location: {$this->application->interview_location}");

            if ($this->application->interview_instructions) {
                $message->line("")
                       ->line("📝 Additional Instructions:")
                       ->line($this->application->interview_instructions);
            }

            $message->line("")
                   ->line("🔍 Track your application status:")
                   ->action('Track Application', url('/track-application'));

        } elseif ($status === JobApplication::STATUS_REJECTED) {
            $message->line('Thank you for your interest in joining our team.')
                   ->line('After careful consideration, we regret to inform you that we will not be moving forward with your application at this time.')
                   ->line('We encourage you to apply for future positions that match your qualifications.');
        }

        if ($feedback && $status !== JobApplication::STATUS_INTERVIEW_SCHEDULED) {
            $message->line("")
                   ->line('Feedback:')
                   ->line($feedback);
        }

        return $message;
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
            'status' => $this->application->status,
            'feedback' => $this->application->hod_feedback ?? $this->application->hr_feedback
        ];
    }
} 