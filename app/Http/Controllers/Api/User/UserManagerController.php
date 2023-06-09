<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\UserService\UserApi;
use Illuminate\Support\Facades\Auth;

class UserManagerController extends Controller
{
   public function getData()
   {
        return UserApi::getUserData();
   }
}
