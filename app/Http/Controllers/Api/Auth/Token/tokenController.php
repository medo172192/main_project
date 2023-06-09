<?php

namespace App\Http\Controllers\Api\Auth\Token;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\ApiManager\Api;
use Illuminate\Support\Facades\Auth;

class tokenController extends Controller
{

    public function token(Request $request)
    {
        if (!Api::checkToken())
                return Api::beginTokenListener();
        // Render Token
        return Api::RenderWithToken([
            Api::Status()   => Api::Success(),
            Api::Message()  => "access token.",
        ],Api::SuccessCode());
    }


    public function removeToken()
    {
        if (!Api::checkToken())
                return Api::beginTokenListener();
        // Remove Token
        Auth::guard('sanctum')->user()->currentAccessToken()->delete();
        Api::removeToken();
        // Render JSON
        return Api::Render([
            Api::Status()   => Api::Success(),
            Api::Message()  => "access token.",
        ],Api::SuccessCode());
    }
}
