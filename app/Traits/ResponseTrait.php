<?php

namespace App\Traits;

trait ResponseTrait{
    public function responseOk($data=[], $code = 200){
        $data['message'] = $data['message'] ?? 'Operation Successful!';
        $data['time'] = date('Y-m-d H:i:s',time());

        return response()->json($data,$code);
    }

    public function responseFailed($data, $code = 400){
        $data['message'] = $data['message'] ?? 'Operation Failed!';
        $data['time'] = date('Y-m-d H:i:s',time());

        return response()->json($data,$code);
    }
}
