<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EmployerController extends Controller
{
    public function jobListByEmployer(){
        $employer = auth()->user();
        $jobs = JobListing::where('company_id',$employer->company->id)
                            ->get();

        return response()->json(['message'=>"{$employer->company->name} jobs!",
                                    'data'=>$jobs],200);
    }

    public function jobCreate(Request $request){
        $employer = auth()->user();

        $validation = Validator::make($request->all(),[
            'title' => 'required|string',
            'description' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'location' => 'nullable|string',
            'requirements' => 'nullable|string',
            'facilities' => 'nullable|string',
            'type' => 'nullable|string',
            'salary' => 'nullable|integer',
            'deadline' => 'nullable|date',
            'status' => 'nullable|boolean',
        ]);

        if($validation->fails()){
            return $this->responseFailed(['message'=>$validation->errors()->all()],400);
        }

        $job = $validation->validated();
        $job['slug'] = Str::slug($job['title']);
        $job['type'] = Str::slug($job['type']);
        $job['company_id'] = $employer->company->id;

        try {
            $data = JobListing::create($job);
            return $this->responseOk(['message'=>'Create Success!','data'=>$data],200);
        } catch (\Throwable $th) {
            // throw $th;
            Log::error('EmployeeController jobCreate() at',$th->getTrace());
            return $this->responseFailed();
        }


    }

    public function jobUpdate(Request $request, $id){
        $employer = auth()->user();
        $job = JobListing::where('id',$id)->first();

        if($job->company_id != $employer->company->id){
            return $this->responseFailed(['message'=>'Unauthorized User'],401);
        }

        $validation = Validator::make($request->all(),[
            'description' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'location' => 'nullable|string',
            'requirements' => 'nullable|string',
            'facilities' => 'nullable|string',
            'type' => 'nullable|string',
            'salary' => 'nullable|integer',
            'deadline' => 'nullable|date',
            'status' => 'nullable|boolean',
        ]);

        if($validation->fails()){
            return $this->responseFailed(['message'=>$validation->errors()->all()],400);
        }

        $job->fill($validation->validated());
        $job['slug'] = Str::slug($job['title']);
        $job['type'] = Str::slug($job['type']);

        try {
            $job->save();
            return $this->responseOk(['message'=>'Update Success!','data'=>$job],200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('EmployeeController jobUpdate() at',$th->getTrace());
            return $this->responseFailed();
        }

    }


    public function jobDelete($id){
        $employer = auth()->user();
        $job = JobListing::where('id',$id)->where('status',true)->first();

        if(!$job){
            return $this->responseFailed(['message'=>'No Such Job Exists!'],400);
        }

        if($job->company_id != $employer->company->id){
            return $this->responseFailed(['message'=>'Unauthorized User'],401);
        }

        try {
            $data = $job;
            $job->delete();
            return $this->responseOk(['message'=>'Delete Success!','data'=>$data],200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('EmployeeController jobDelete() at',$th->getTrace());
            return $this->responseFailed();
        }

    }
}
