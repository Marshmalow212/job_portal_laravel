<?php

namespace App\Jobs;

use App\Mail\PasswordResetSuccess;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPasswordResetSuccessMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->__sendResetSuccessMail($this->user);
    }

    private function __sendResetSuccessMail($user){
        $link = env('FRONTEND_URL').'/login';
        $data = [
            'user'=>$user,
            'link'=>$link
        ];

        Mail::to(env('MAIL_TO_ADDRESS'),env('APP_NAME'))
                ->send(new PasswordResetSuccess($data));
    }
}
