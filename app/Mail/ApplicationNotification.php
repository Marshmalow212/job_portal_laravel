<?php

namespace App\Mail;

use App\Models\JobListing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationNotification extends Mailable
{
    use Queueable, SerializesModels;

    private $application;
    private $job;
    /**
     * Create a new message instance.
     */
    public function __construct($application)
    {
        $this->application = $application;
        $job = JobListing::where('id',$this->application->job_id)->first();
        $this->job = $job;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application : '.$this->job->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'templates.UpdateApplication',
            with: [
                "application" => $this->application,
                "job" => $this->job
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
