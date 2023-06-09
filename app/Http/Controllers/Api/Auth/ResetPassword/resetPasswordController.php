<?php

namespace App\Http\Controllers\Api\Auth\ResetPassword;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\ApiManager\Api;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\restPassword;


class resetPasswordController extends Controller
{

//     /*
//     |--------------------------------------------------------------------------
//     |  Reset Password
//     |--------------------------------------------------------------------------
//     |
//     | Reset user Password if  user forgot password
//     |
//     */

    // First Step
    public function sendMail(Request $request)
    {
        // Email Validator
        Api::validator($request->all(),[
            'email' => "required|email|string"
        ]);

        // The Input Rules Not Right
        if(Api::InValid())
            return Api::ErrorValidator();

        // Righe Rules
        // Check Form Email Exists
        if (!Auth::attempt(request()->only(['email']))){
            return Api::Render([
                Api::Status() => Api::Error(),
                Api::Message()=> "The Email Not Found"
            ],Api::ErrorCode());
        }

        $user = User::where('email',$request->input('email'))->first();
        $code = session()->flush('ResetPasswordCode',Hash::make(random_int(2000,10000000)));
        $code = session()->flush('ResetPasswordEmail', $user->email);
        //Send Mail
        Notification::send($user, new restPassword($user, session()->get('ResetPasswordCode')));
        return Api::Render([
            Api::Status() => Api::Success(),
            Api::Message() => "Fetch Code"
        ],Api::SuccessCode());
    }


    // Second Step
    public function ResetPassword(Request $request)
    {
        // Email Validator
        Api::validator($request->all(),[
            'code' => "required|string"
        ]);

        // The Input Rules Not Right
        if(Api::InValid())
            return Api::ErrorValidator();

        // Righe Rules
        $user = User::where('email',session()->get('ResetPasswordEmail'))->first();

        // Check From Code
        if (session()->get('ResetPasswordCode') != $request->input('code')){
            return Api::Render([
                Api::Status()  => Api::Error(),
                Api::Message() => 'The Code not Right!',
                'token'        => ''
            ],Api::ErrorCode());
        }

        // Code Right
        Api::GenerateToken($user);
        session()->flush('SecondStepFromResetPassword',Api::Token());
        return Api::Render([
            Api::Status()  => Api::Error(),
            Api::Message() => 'The Code not Right!',
            'token'        => Api::Token()
        ],Api::SuccessCode());

    }

    // End Step
    public function changePassword(Request $request)
    {
        DB::beginTransaction();
        try {
            Api::Validator($request->all(),[
                'password' => "required|string",
                'confirm_password' => "required|string",
                'token' => "required|string",
            ]);

            if (Api::InValid())
                return Api::ErrorValidator();


            $email = session()->get('ResetPasswordEmail');
            $token = session()->get('SecondStepFromResetPassword');
            $user = User::where('email',$email)->first();

            // Check Confirm Password
            if ($request->get('password') != $request->get('confirm_password'))
                return Api::Render([
                    Api::Status() => Api::Error(),
                    Api::Message()=> 'The password not Confirmed'
                ],Api::ErrorCode());

            // Check Token
            if ($request->input('token') != $token )
                return Api::Render([
                    Api::Status() => Api::Error(),
                    Api::Message()=> 'The Token Not Right!'
                ],Api::ErrorCode());

            // Change Password
            $user->update([
                'password' => Hash::make($request->input('password'))
            ]);

            DB::commit();

            return Api::Render([
                Api::Status() => Api::Success(),
                Api::Message()=> 'Password changed'
            ],Api::SuccessCode());

        } catch (\Exception $e) {
            DB::rollback();
            Api::ServerError();
        }


    }
}
