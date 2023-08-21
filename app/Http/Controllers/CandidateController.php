<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CandidateController extends Controller
{
    public function applicationListByCandidate(){
        $candidate = auth()->user();
        $applications = Application::where('candidate_id',$candidate->id)
                                    ->where('status',true)
                                    ->get();

        return response()->json(['data'=>$applications],200);
    }

    public function store(Request $request, $jobId){
        $candidate = auth()->user();
        $job = !is_null($jobId)? JobListing::where('id',$jobId)->first():null;
        $application = Application::where('candidate_id',$candidate->id)->first();

        if(is_null($job)) return $this->responseFailed(['message'=>'No Job Found!'],400);

        $validation = Validator::make($request->all(),[
            'cover_letter' => 'nullable|string',
            'cv' => 'nullable|string',
        ]);

        if($validation->fails()){
            return $this->responseFailed(['message'=>$validation->errors()->all()],400);
        }

        $application = new Application($validation->validated());
        $slug = $candidate->username.' '.$job->title;
        $application['slug'] = Str::slug($slug);
        $application['job_id'] = $jobId;
        $application['candidate_id'] = $candidate->id;
        $application['submission_date'] = date('Y-m-d',strtotime('now'));

        try {
            $data = $application->save();
            return $this->responseOk(['message'=>'Application Successful!','data'=>$data],200);
        } catch (\Throwable $th) {
            // throw $th;
            Log::error('CandidateController store() at',$th->getTrace());
            return $this->responseFailed();
        }


    }


    public function update(Request $request, $id){
        $candidate = auth()->user();
        $application = Application::where('candidate_id',$candidate->id)
                                    ->where('id',$id)
                                    ->where('status',true)
                                    ->first();

        if(is_null($application)) return $this->responseFailed(['message'=>'No Application Found!'],400);

        $validation = Validator::make($request->all(),[
            'education' => 'nullable',
            'experience' => 'nullable',
            'skills' => 'nullable',
            'cover_letter' => 'nullable|string',
            'cv' => 'nullable|string',
            'photo' => 'nullable|string',
        ]);

        if($validation->fails()){
            return $this->responseFailed(['message'=>$validation->errors()->all()],400);
        }

        $application->fill($validation->validated());

        try {
            $application->save();
            return $this->responseOk(['message'=>'Application Successful!','data'=>$application],200);
        } catch (\Throwable $th) {
            // throw $th;
            Log::error('CandidateController update() at',$th->getTrace());
            return $this->responseFailed();
        }


    }


    public function destroy($id){
        $candidate = auth()->user();
        $application = Application::where('candidate_id',$candidate->id)
                                    ->where('id',$id)
                                    ->first();

        if(!$application){
            return $this->responseFailed(['message'=>'Application not found!'],400);
        }

        try {
            $data = $application;
            $application->delete();
            return $this->responseOk(['message'=>'Delete Success!','data'=>$data],200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('CandidateController destroy() at',$th->getTrace());
            return $this->responseFailed();
        }

    }
    
}
