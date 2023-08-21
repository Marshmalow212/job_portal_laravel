<?php

namespace App\Jobs;

use App\Mail\ApplyNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ApplicationSubmitted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $application;
    /**
     * Create a new job instance.
     */
    public function __construct($application)
    {
        $this->application = $application;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->sendNotificationMail($this->application);
    }

    private function sendNotificationMail($application){

        Mail::to(env('MAIL_TO_ADDRESS'),'Job Portal Admin')
                ->send(new ApplyNotification($application));
    }
}
