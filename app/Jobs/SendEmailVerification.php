<?php

namespace App\Jobs;

use App\Mail\EmailVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SendEmailVerification implements ShouldQueue
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
        $link = $this->__createVerificationLink($this->user);
        $this->__sendVerificationMail($link);
        
    }


    private function __createVerificationLink($user){
        $code = random_int(100000,999999);
        $secret = $user->email.'&'.$code;
        $hash = Hash::make($secret);

        $data = [
            'user_id' => $user->id,
            'secret' => $hash,
            'created_at' => date('Y-m-d H:i:s')
        ];
        DB::table('email_verifications')->insert($data);

        $link = env('FRONTEND_URL').'/verify/mail?token='.$hash;
        return $link;
    }

    private function __sendVerificationMail($link){
        Mail::to(env('MAIL_TO_ADDRESS'),$this->user->fullname)
                ->send(new EmailVerification($link));
    }
}
