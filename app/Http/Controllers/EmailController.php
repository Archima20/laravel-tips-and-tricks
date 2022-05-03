<?php

namespace App\Http\Controllers;

use App\Mail\Email;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    use GeneralTrait;
    public function send(Request $request){
        try{
            $rules = [
                'email' => 'required|email',
                'msg' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            Mail::to($request->email)->send(new Email($request->msg));
            return $this->returnSuccessMessage('success');
        }catch(\Exception $e){
            return $this->returnError(201, $e->getMessage());
        }
    }
}
