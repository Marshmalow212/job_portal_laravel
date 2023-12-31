<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CandidateInfo;
use App\Models\InterestedJob;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function profile(){
        return auth()->user();
    }

    public function getInterestedJobs(){
        $candidate = auth()->user();
        $jobIds = InterestedJob::where('user_id',$candidate->id)
                                ->pluck('job_id')
                                ->toArray();

        $jobs = JobListing::whereIn('id',$jobIds)
                            ->where('status',true)
                            ->get();

        try {
            return $this->responseOk(['data'=>$jobs],200);
        } catch (\Throwable $th) {
            // throw $th;
            Log::error('JobListingController getInterestedJobs() at',$th->getTrace());
            return $this->responseFailed();
        }                 
                            
    }

    public function profileUpdate(Request $request){
        $auth = auth()->user();

        $user = User::find($auth->id);

        $validation = Validator::make($request->all(),[
            'photo' => 'nullable|file|mimes:jpg,jpeg,png',
            'current_password' => 'nullable|string|min:8',
            'new_password' => 'nullable|string|min:8'

        ]);

        if($validation->fails()){
            return $this->responseFailed(['message'=>$validation->errors()->all()],400);
        }

        $data = $validation->validated();    
        
        if(isset($data['photo']) && !is_null($data['photo'])){
            $data['photo'] = $this->storeFile($request->photo);
        }

        if(!is_null($data['current_password'])){
            if(Hash::check($data['current_password'],$user->password)){
                $data['password'] = Hash::make($data['new_password']);
            }
            else{
                return $this->responseFailed(['message'=>'Current Password Incorrect!']);
            }
        }

        try {
            $user->update($data);
            return $this->responseOk(['message'=>'Profile Updated!','data'=>$user],200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('UserController profileUpdate at',$th->getTrace());
            return $this->responseFailed();
        }

    }


    public function addressUpdate(Request $request){
        $user = auth()->user();

        $address = Address::where('id',$user->address_id)->first();

        $validation = Validator::make($request->all(),[
            'present' => 'required|string',
            'permanent' => 'required|string'
        ]);

        if($validation->fails()){
            return $this->responseFailed(['message'=>$validation->errors()->all()],400);
        }

        try {
            $address->update($validation->validated());
            return $this->responseOk(['message'=>'Address Updated!','data'=>$user],200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('UserController addressUpdate at',$th->getTrace());
            return $this->responseFailed();
        }

    }


    public function candidateInfoUpdate(Request $request){
        $user = auth()->user();
        $candidateInfo = CandidateInfo::where('user_id',$user->id)->first();

        $validation = Validator::make($request->all(),[
            'education'=>'nullable',
            'experience'=>'nullable',
            'skills'=>'nullable',
            'certifications'=>'nullable'
        ]);

        if($validation->fails()){
            return $this->responseFailed(['message'=>$validation->errors()->all()],400);
        }

        try {
            $candidateInfo->update($validation->validated());
            return $this->responseOk(['message'=>'Detailes Updated!','data'=>$user],200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('UserController candidateInfoUpdate at',$th->getTrace());
            return $this->responseFailed();
        }

    }
}
