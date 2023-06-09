<?php

namespace App\Http\Controllers\Api\Auth\Logout;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\ApiManager\Api;
use Illuminate\Support\Facades\Auth;


class logoutController extends Controller
{
    public function logout()
    {
        if(!Api::checkToken())
                return Api::beginTokenListener();

        // Remove Token
        Api::removeToken();
        Auth::guard('sanctum')->user()->currentAccessToken()->delete();
        // Logout
        Auth::logout();
        // Render JSON
        return Api::Render([
            Api::Status()   => Api::Success(),
            Api::Message()  => "logout",
        ],Api::SuccessCode());

    }
}
