<?php

namespace App\Http\Controllers;

use App\Models\InterestedJob;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

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
}
