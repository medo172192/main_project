<?php

namespace App\Http\Controllers\Api\Auth\Register;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ApiManager\Api;
use App\Models\User;
use App\Notifications\EmailVerification;
use Illuminate\Support\Facades\Notification;

class registerController extends Controller
{

//     /*
//     |--------------------------------------------------------------------------
//     | Register Controller
//     |--------------------------------------------------------------------------
//     |
//     | Here You Can access to Register Page Controller
//     | Use Sanctum To Api Token
//     |
//     */

    public function Register(Request $request)
    {
        DB::beginTransaction();
        try {

            Api::Validator($request->all(),[
                "fname"     =>"required|string",
                "lname"     =>"required|string",
                "address"   =>"required|string",
                "job"       =>"required|string",
                "age"       =>"required|string",
                "email"     =>"required|string|email|unique:users",
                "password"  =>"required|string",
            ]);

            // in Case There is Validation Errors
            if (Api::InValid())
                    return Api::ErrorValidator();

            // - Right Validation
            // Check User Token Exsists
            if (Auth::attempt(request()->only(['email','password'])))
                    return Api::Render([
                        Api::Status()   => Api::Error(),
                        Api::Message()  => Api::getToken()
                    ],Api::ErrorCode());

            // - Token Not Found
            // Create User Account
            $UserData = collect([
                'fname' =>$request->get('fname'),
                'lname' =>$request->get('lname'),
                'address' =>$request->get('address'),
                'job' =>$request->get('job'),
                'age' =>$request->get('age'),
                'email' =>$request->get('email'),
                'password' =>Hash::make($request->get('password')),
            ]);

            $user = User::create($UserData->toArray());

            // Send Mail
            Notification::send($user, new EmailVerification($user));

            // Check User Logined
            if (Auth::guard('sanctum')->check())
                    return Api::RenderWithToken([
                        Api::Status()   => Api::Success(),
                        Api::Message()  => "Make Verification Email ",
                    ],Api::SuccessCode());

            // user sign in and generate token
            Auth::login($user,true);

            Api::GenerateToken($user);
            Api::saveToken(Api::Token());

            DB::commit();
            return Api::RenderWithTokenGenerate([
                Api::Status()   => Api::Success(),
                Api::Message()  => "Make Verification Email ",
            ],Api::SuccessCode());

        } catch (\Exception $e) {
            DB::rollback();
            Api::ServerError();
        }




    }




}
