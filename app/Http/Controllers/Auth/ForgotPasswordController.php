<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendPasswordRecoveryMail;
use App\Jobs\SendPasswordResetSuccessMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email'
        ]);

        if($validator->fails()){
            return $this->responseFailed(['message' => $validator->errors()->all()]);
        }

        $user = User::where('email',$request->email)->first();
        $job = new SendPasswordRecoveryMail($user);
        dispatch($job);
        return $this->responseOk(['message'=>'Password recovery mail has been sent!']);
    }


    public function resetPassword(Request $request){
        if($request->has('token')){
            $resetData = DB::table('password_reset_tokens')
                            ->where('token',$request->token)
                            ->first();

            $now = Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'));
            $reqTime = Carbon::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s',strtotime($resetData->created_at)));

            if($now->diffInSeconds($reqTime) > 120 ){
                error_log($now->diffInSeconds($reqTime));
                DB::table('password_reset_tokens')
                    ->where('token',$request->token)
                    ->delete();
                return $this->responseFailed(['message' => 'Reset link expired! Try again.']);
            }
            else{
                $validation = Validator::make($request->only('new_password','confirm_password'),[
                    'new_password' => 'required|string|min:8',
                    'confirm_password' => 'required|string|same:new_password|min:8'
                ]);
                if($validation->failed())return $this->responseFailed(['message'=>$validation->errors()->all()]);

                $data = [
                    'password' => $validation->validated()['new_password']
                ];

                try {
                    $user = User::where('email',$resetData->email)->first();
                    $user->update($data);
                    $job = new SendPasswordResetSuccessMail($user);
                    dispatch($job);
                    DB::table('password_reset_tokens')
                            ->where('token',$request->token)
                            ->delete();
                    return $this->responseOk(['message'=>'Password Reset Successful! Please login']);
                } catch (\Throwable $th) {
                    //throw $th;
                    Log::error('ForgotPasswordController resetPassword method: ',$th->getTrace());
                    return $this->responseFailed(['message'=>'Password Reset Failed! Contact your administrator']);
                }
            }
        }

        return $this->responseFailed(['message'=>'Invalid Request'],404);
    }
}
