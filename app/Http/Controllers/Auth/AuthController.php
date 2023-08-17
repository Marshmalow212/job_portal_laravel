<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Address;
use App\Models\CandidateInfo;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Registration Method
     * @param request array
     *
     */
    public function registration(Request $request){
        $validation = Validator::make($request->all(),[
            'fullname' => 'required | string',
            'username' => 'required | string',
            'email' => ['required','unique:users','email'],
            'password' => 'required | string | min:8',
            'dob' => 'nullable | date',
            'role' => 'required | string',
            'permanent' => 'nullable | string',
            'present' => 'nullable | string',
            'photo' => 'nullable|string'
        ]);

        if($validation->fails()){
            return response()->json(['errors'=> $validation->errors()->all()],400);
        }

        $address = $request->only('permanent','present');
        $user = $request->except('permanent','present');
        $user['role'] = Str::slug($user['role']);

        try {
            DB::beginTransaction();
            if($address = Address::create($address)){
                $user['address_id'] = $address->id;
            }
            $user = User::create($user);
            if($user->role == 'job-seeker'){
                $canidate_info = CandidateInfo::create(['user_id'=>$user->id]);

            }
            DB::commit();

            return response()->json(['data'=>$user],200);

        } catch (\Throwable $th) {
            throw $th;
            DB::rollback();
            Log::error('AuthController registration method at : ',$th->getTrace());
            return response()->json(['message'=>'something wrong! Please try again'],400);
        }

    }

    public function login(Request $request){
        $validation = Validator::make($request->all(),[
            'email' => ['required', 'email'],
            'password' => 'required | string | min:8',
            'role' => 'required | string',
        ]);

        if($validation->fails()){
            return response()->json(['errors'=> $validation->errors()->all()],400);
        }

        $creds = $request->except('role');
        $role = $request->role;
        try {
            if(auth()->attempt($creds)){
                $user = auth()->user();
                $token = $user->createToken('access_token')->plainTextToken;
                if(Str::slug($role)!= $user->role){
                    auth()->logout();
                    return response()->json(['message'=>'Unauthorized User!'],401);
                }
                $data = [
                    'message' => 'Login Successful!',
                    'token' => $token
                ];
                return $this->responseOk(['data'=>$data],200);

            }
            else{
                return $this->responseFailed(['message'=>'No user exists! Please check your email'],400);

            }


        } catch (\Throwable $th) {
            //throw $th;

            Log::error('AuthController login method at : ',$th->getTrace());
            return $this->responseFailed(['message'=>'something wrong! Please try again'],400);
        }

    }
}
