<?php

namespace App\Http\Services\UserService;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Http\Services\ApiManager\Api;
use Laravel\Sanctum\PersonalAccessToken;
trait UserApi{
    use Api;


    public static function getUserData()
    {
        if (!Api::checkToken())
                return Api::beginTokenListener();

       $user = Auth::guard('sanctum')->user();
       return Api::RenderWithToken([
           Api::Status()   => Api::Success(),
           Api::Message()  => $user
       ],Api::SuccessCode());

    }



}
