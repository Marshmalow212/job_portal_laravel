<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index(){
        $company = [];
        $employer_id = null;

        if(auth()->user()){
            $employer = auth()->user();
            $company =  $employer->company;
        }
        else{
            $company = Company::all();
        }

        return response()->json(['data'=>$company],200);
    }

    public function storeOrUpdate(Request $request){
        $company = [];
        $employer_id = auth()->user()->id;
        $company = Company::where('employer_id',$employer_id)->first();

        $validation = Validator::make($request->all(),[
            'name' => 'nullable|string',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'company_logo' => 'nullable|file|mimes:jpg,jpeg,png,svg',
            'moto' => 'nullable|string',
            'vision' => 'nullable|string'
        ]);

        if($validation->fails()){
            return $this->responseFailed(['message'=>$validation->errors()->all()],400);
        }

        if(is_null($company)){
            $company = new Company($validation->validated());
            $company['slug'] = Str::slug($company['name']);
            $company['employer_id'] = $employer_id;
        }
        else{
            $company->fill($validation->validated());
            $company['slug'] = Str::slug($company->toArray()['name']);
        }

        if(isset($request->company_logo) && !is_null($validation->validated()['company_logo'])){
            $company['logo'] = $this->storeFile($validation->validated()['company_logo']);
        }

        try {
            $company->save();
            return $this->responseOk(['data'=>$company],200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('CompanyController store/update method at',$th->getTrace());
            return $this->responseFailed();
        }


    }

}
