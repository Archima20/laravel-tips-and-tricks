<?php

namespace App\Http\Controllers;

// use GuzzleHttp\Psr7\Request;

use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, GeneralTrait;

    public function uploadImage(Request $request)
    {
        try {
            if (!$request->hasFile('file')) {
                return $this->returnError(202, 'file is required');
            }
            $response = cloudinary()->upload($request->file('file')->getRealPath())->getSecurePath();
            return $response;
        } catch (\Exception $e) {
            return $this->returnError(201, $e->getMessage());
        }
    }

    function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        // print_r($data);
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'errNum' => 202,
                'msg' => 'invalid user'
            ]);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $data = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => '',
            'data' => $data
        ]);
    }

    public function test(){
        return 'authenticated user';
    }
}
