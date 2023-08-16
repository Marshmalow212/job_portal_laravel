<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function index(){
        $candidate = auth()->user();
        $company_id = $candidate->company->id;

        $jobsWithApplication = JobListing::with('applications')
                            ->where('company_id',$company_id)
                            ->get();

        return response()->json(['data'=>$jobsWithApplication],200);
    }


    public function update(Request $request, $id){
        $employer = auth()->user();

        $application = Application::where('id',$id)
                                    ->where('status',true)
                                    ->first();

        if(is_null($application)) return $this->responseFailed(['message'=>'No Application Found!'],400);

        $validation = Validator::make($request->all(),[
            'result' => 'nullable|string'
        ]);

        if($validation->fails()){
            return $this->responseFailed(['message'=>$validation->errors()->all()],400);
        }

        $result = Str::slug($request->result);
        $application->fill(['result'=>$result]);

        try {
            $application->save();
            return $this->responseOk(['message'=>'Application Updated!','data'=>$application],200);
        } catch (\Throwable $th) {
            // throw $th;
            Log::error('ApplicationController update() at',$th->getTrace());
            return $this->responseFailed(['message'=>'Application Update Failed!']);
        }

    }


    public function show($applicationId){
        $employer = auth()->user();

        $application = Application::where('id',$applicationId)
                                    ->where('status',true)
                                    ->with('candidate','job')
                                    ->first();

        if(is_null($application)) return $this->responseFailed(['message'=>'No Application Found!'],400);

        try {
            return $this->responseOk(['message'=>'Application Updated!','data'=>$application],200);
        } catch (\Throwable $th) {
            // throw $th;
            Log::error('ApplicationController show() at',$th->getTrace());
            return $this->responseFailed();
        }

    }


}
