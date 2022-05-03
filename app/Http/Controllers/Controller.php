<?php

namespace App\Http\Controllers;

// use GuzzleHttp\Psr7\Request;

use App\Traits\GeneralTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, GeneralTrait;

    public function uploadImage(Request $request){
        try{
            if(! $request->hasFile('file')){
                return $this->returnError(202, 'file is required');
            }
            $response = cloudinary()->upload($request->file('file')->getRealPath())->getSecurePath();
            return $response;
        }catch(\Exception $e){
            return $this->returnError(201, $e->getMessage());
        }
    }
}
