<?php

namespace App\Http\Controllers\Api\Auth\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\ApiManager\Api;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EmailVerification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class loginController extends Controller
{
//     /*
//     |--------------------------------------------------------------------------
//     | Login Controller
//     |--------------------------------------------------------------------------
//     |
//     | Here You Can access to Login Page Controller
//     | Use Sanctum To Api Token
//     |
//     */

    public function Login(Request $request)
    {
        // Login Validation
        Api::Validator($request->all(),[
            'email' =>"required|string|email",
            'password'=>"required|string",
        ]);

        // Check From Validation
        if(Api::InValid())
                return Api::ErrorValidator();

        // -Right Validation
        // Check User Exists
        if (!Auth::attempt(request()->only(['email','password'])))
                return Api::Render([
                    Api::Status()   => Api::Error(),
                    Api::Message()  => "The Email Or Password InValid"
                ],Api::ErrorCode());

        // -This User Exists
        // find user
        $user  = User::where('email',$request->input('email'))->first();

        // Check Email Verification
        if (!$user->hasVerifiedEmail())
                // Send Verify User Mail
                Notification::send($user, new EmailVerification($user));
                return Api::RenderWithToken([
                    Api::Status()   => Api::success(),
                    Api::Message()  => "Make Verification Email ",
                ],Api::SuccessCode());


        // user sign in and generate token
        Auth::login($user,true);
        Api::GenerateToken($user);
        Api::saveToken(Api::Token());

        // success
        return Api::RenderWithTokenGenerate([
            Api::Status()   => Api::Success(),
            Api::Message()  =>[],
        ],Api::SuccessCode());

    }


}
