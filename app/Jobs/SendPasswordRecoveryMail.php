<?php

namespace App\Jobs;

use App\Mail\PasswordRecoveryMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SendPasswordRecoveryMail implements ShouldQueue
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
        $link = $this->__createPasswordRecoveryLink($this->user);
        $this->__sendPasswordRecoveryMail($link);
    }

    private function __createPasswordRecoveryLink($user){
        $code = random_int(100000,999999);
        $secret = $user->email.'&'.$code;
        $hash = Hash::make($secret);

        $data = [
            'email' => $user->email,
            'token' => $hash,
            'created_at' => date('Y-m-d H:i:s')
        ];
        DB::table('password_reset_tokens')->insert($data);

        $link = env('FRONTEND_URL').'/reset/password?token='.$hash;
        return $link;
    }

    private function __sendPasswordRecoveryMail($link){
        Mail::to(env('MAIL_TO_ADDRESS'),env('APP_NAME'))
                ->send(new PasswordRecoveryMail($link));

    }
}
