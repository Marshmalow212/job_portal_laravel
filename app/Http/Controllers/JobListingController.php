<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Models\Company;
use App\Models\InterestedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\error;

class JobListingController extends Controller
{
    public function index(Request $request){
        //filters
        $keywords = $request->get('query') ?? '';
        $location = $request->get('location') ?? '';
        $title = $request->get('title') ?? '';
        $company= Company::where('slug',$request->company)->first();
        $companyId = $company ? $company->id: 0;
        $sort = $request->get('sorting') ?? ['title','ASC'];

        if(is_string($sort)) $sort = explode(',',$sort);


        $jobs = JobListing::where('status',true)
                            ->where(function($query) use ($keywords){
                                $query->where('title','like','%'.$keywords.'%')
                                    ->orWhere('location','LIKE','%'.$keywords.'%')
                                    ->orWhere('description','LIKE','%'.$keywords.'%')
                                    ->orWhere('responsibilities','LIKE','%'.$keywords.'%')
                                    ->orWhere('requirements','LIKE','%'.$keywords.'%')
                                    ->orWhere('type','LIKE','%'.$keywords.'%');
                            })
                            ->where(function($query) use ($title){
                                $query->orWhere('title','like','%'.$title.'%');
                            })
                            ->where(function($query) use ($location){
                                $query->orWhere('location','like','%'.$location.'%');
                            })
                            ->orWhere('company_id',$companyId)
                            ->orderBy($sort[0],$sort[1])
                            ->get();

        return $this->responseOk(['data'=>$jobs]);
    }

    public function setInterest($id){
        $user = auth()->user();
        if($user->role != 'job-seeker'){
            return $this->responseFailed(['message'=>'Invalid Operation!']);
        }

        $job_id = $id;

        
        try {
            $data = InterestedJob::create([
                'user_id'=>$user->id,
                'job_id'=> $job_id
            ]);
            return $this->responseOk(['message'=>'Added to Interest!'],200);
        } catch (\Throwable $th) {
            // throw $th;
            Log::error('JobListingController setInterest() at',$th->getTrace());
            return $this->responseFailed();
        }

    }
    
    public function unsetInterest($id){
        $user = auth()->user();
        if($user->role != 'job-seeker'){
            return $this->responseFailed(['message'=>'Invalid Operation!']);
        }

        $job_id = $id;

        
        try {
            $data = InterestedJob::where('user_id',$user->id)
                                    ->where('job_id',$job_id)
                                    ?->delete();
            return $this->responseOk(['message'=>'Removed from Interest!'],200);
        } catch (\Throwable $th) {
            // throw $th;
            Log::error('JobListingController unsetInterest() at',$th->getTrace());
            return $this->responseFailed();
        }

    }


}
