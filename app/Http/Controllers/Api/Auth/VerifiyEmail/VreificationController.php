<?php

namespace App\Http\Controllers\Api\Auth\VerifiyEmail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\ApiManager\Api;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class VreificationController extends Controller
{
   public function VerifiyEmail(Request $request)
   {
        if (request()->method() != 'POST')
                return Api::Render([
                    Api::Status() => Api::Error(),
                    Api::Message() => "you cant Access "
                ],Api::ErrorCode());

        $user  = User::where('id',$request->get('id'))->first();
        Api::GenerateToken($user);
        Auth::login($user,true);

        return Api::Render([
            Api::Status() => Api::Success(),
            Api::Message() => "Done",
            "token"        => Api::Token()
        ],Api::SuccessCode());
   }
}
