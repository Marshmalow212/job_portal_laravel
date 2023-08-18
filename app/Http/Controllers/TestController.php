<?php

namespace App\Http\Controllers;

use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;

class TestController extends Controller
{
    public function fileUpload(Request $request){
        $validat = Validator::make($request->all(),[
            'file'=>'required|mimes:jpg,jpeg,png'
        ]);

        if($validat->fails())return $this->responseFailed(['message'=>$validat->errors()->all()]);

        $file = $validat->validated()['file'];

        return $this->__mediaSaveToStorage($file);


    }

    private function __mediaSaveToStorage($file){
        $path = Storage::putFile('public',$file);
        return $this->__mediaGetFromStorage($path);
    }

    private function __mediaGetFromStorage($path){
        $url = Storage::url($path);
        return asset($url);
    }

    public function fileRetrieve(Request $request){
        $url = Storage::url($request->path);
        return asset($url);
    }
}
