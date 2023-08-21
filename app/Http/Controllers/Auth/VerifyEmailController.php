<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VerifyEmailController extends Controller
{
    public function mailVerify(Request $request){
        if($request->has('token')){
            $user = DB::table('email_verifications')
                    ->where('secret',$request->token)
                    ->first();
            
            try {
                User::where('id',$user->user_id)
                        ->update(['email_verified_at'=>date('Y-m-d')]);

                DB::table('email_verifications')
                ->where('secret',$request->token)
                ->delete();
                return $this->responseOk(['message'=>'Email Verification Successful!']);
            } catch (\Throwable $th) {
                //throw $th;
                Log::error('VerifyEmailController mailVerify method: ',$th->getTrace());
                return $this->responseFailed(['message'=>'Email Verification Failed!']);

            }   
        }
    }
}
