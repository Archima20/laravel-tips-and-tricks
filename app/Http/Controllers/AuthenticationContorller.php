<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMail;
use App\Models\PasswordReset;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthenticationContorller extends Controller
{
    use GeneralTrait;
    public function forgotPassword(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email'
            ];
            $validte = Validator::make($request->all(), $rules);
            if ($validte->fails()) {
                return $this->returnError(202, 'invalid email');
            }
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return $this->returnError(203, 'this user does not exist');
            }
            $token = Str::random(5);
            PasswordReset::create([
                'email' => $request->email,
                'token' => $token
            ]);
            Mail::to($request->email)->send(new ForgotPasswordMail($token));
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError(201, $e->getMessage());
        }
    }

    public function createUser(Request $request)
    {
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError(201, $e->getMessage());
        }
    }

    public function changePassword(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'token' => 'required',
                'password' => 'required',
            ];
            $validte = Validator::make($request->all(), $rules);
            if ($validte->fails()) {
                return $this->returnError(202, 'invalid inputs');
            }
            $token = PasswordReset::where('token', $request->token)->first();
            if (!$token) {
                return $this->returnError(203, 'invaild token');
            }
            $user = User::where('email', $token['email'])->first();
            if (!$user) {
                return $this->returnError(203, 'user does not exist');
            }
            $user->update([
                'password'=> Hash::make($request->password)
            ]);
            PasswordReset::where('email', $user['email'])->delete();
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError(201, $e->getMessage());
        }
    }
}
