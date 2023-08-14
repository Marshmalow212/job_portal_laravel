<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class JobListingController extends Controller
{
    public function index(Request $request){
        //filters
        $keywords = $request->get('query') ?? '';
        $location = $request->get('location') ?? '';
        $title = $request->get('title') ?? '';
        $companyId = Company::where('slug',$request->company)->first()?->id;
        $sort = $request->get('sorting') ?? ['title','ASC'];

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


}
