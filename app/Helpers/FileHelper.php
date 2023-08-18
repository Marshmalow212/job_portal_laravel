<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

trait FileHelper{
    public function storeFile($file){
        $path = Storage::putFile('public',$file);
        return $this->__mediaGetFromStorage($path);
    }

    private function __mediaGetFromStorage($path){
        $url = Storage::url($path);
        return asset($url);
    }
}