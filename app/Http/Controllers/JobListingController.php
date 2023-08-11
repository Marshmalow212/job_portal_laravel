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
        $keywords = $request->query ?? '';
        $location = $request->location ?? '';
        $title = $request->title ?? '';

        $jobs = JobListing::where('status',true)
                            ->where(function($query) use ($keywords,$location,$title){
                                $query->where('title','LIKE',"%{$keywords}%")
                                    ->orWhere('location','LIKE',"%{$keywords}%")
                                    ->orWhere('description','LIKE',"%{$keywords}%")
                                    ->orWhere('responsibilities','LIKE',"%{$keywords}%")
                                    ->orWhere('requirements','LIKE',"%{$keywords}%")
                                    ->orWhere('title','LIKE',"%{$title}%")
                                    ->orWhere('location','LIKE',"%{$location}%");
                            })
                            ->get();

        return response()->json(['data'=>$jobs],200);
    }

    public function indexByCompany(Request $request){
        //filters
        $keywords = $request->query ?? '';
        $location = $request->location ?? '';
        $title = $request->title ?? '';

        $jobs = JobListing::where('status',true)
                            ->where(function($query) use ($keywords,$location,$title){
                                $query->where('title','LIKE',"%{$keywords}%")
                                    ->orWhere('location','LIKE',"%{$keywords}%")
                                    ->orWhere('description','LIKE',"%{$keywords}%")
                                    ->orWhere('responsibilities','LIKE',"%{$keywords}%")
                                    ->orWhere('requirements','LIKE',"%{$keywords}%")
                                    ->orWhere('title','LIKE',"%{$title}%")
                                    ->orWhere('location','LIKE',"%{$location}%");
                            })
                            ->get();

        return response()->json(['data'=>$jobs],200);
    }


}
