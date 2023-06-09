<?php

namespace App\Http\Services\ApiManager;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Closure;


trait Api{

//     /*
//     |--------------------------------------------------------------------------
//     | Api Manager
//     |--------------------------------------------------------------------------
//     |
//     | This File Using in Helper To Api Files
//     | Support Api Helper Functions
//     |
//     */


    public static $ValidatorStatus;
    public static $ErrorValidator =[];
    public static $token="";





    public static function Validator(array $request,array $data) : Object|bool
    {
        $validate = Validator::make($request,$data);

        if ( $validate->fails() ){
            $render = self::Render([
                "status"    => self::Error(),
                "message"   => $validate->errors()
            ],self::ErrorCode());

            self::$ValidatorStatus = true;
            self::$ErrorValidator =  $render;
            return $render;
        }

        self::$ValidatorStatus = false;
        return false;
    }

    public function ServerError()
    {
        return Api::Render([
            Api::Status()   => Api::Error(),
            Api::Message()  => 'server error',
        ],Api::ErrorCode());
    }

    public static function Render(array $value) : Object
    {
       return response()->json($value);
    }

    public static function RenderWithToken(array $value) : Object
    {
       return response()->json(Api::makeToken()->merge($value));
    }

    public static function RenderWithTokenGenerate(array $value) : Object
    {
       return response()->json(Api::makeTokenGenerate()->merge($value));
    }

    public  static function Error() : String
    {
       return "error";
    }

    public static function Success() : String
    {
       return "success";
    }

    public static function ErrorCode() : Int
    {
       return 401;
    }

    public  static function SuccessCode() : Int
    {
       return 200;
    }

    public static function GenerateToken($user,$tokenName = 'TokenGenerator')
    {
        $token = $user->createToken($tokenName);
        self::$token = $token->plainTextToken;
        return self::$token ;
    }

    public static function token()
    {
       return self::$token;
    }

    public static function InValid()
    {
       if (self::$ValidatorStatus == true)
            return true;
       return false;
    }

    public static function ErrorValidator()
    {
       return self::$ErrorValidator;
    }


    public static function Status()
    {
       return 'status';
    }

    public static function Message()
    {
       return 'message';
    }


    private static function makeToken()
    {
      return collect(["token"=>Api::getToken()]);
    }


    private static function makeTokenGenerate()
    {
      return collect(["token"=>Api::Token()]);
    }

    public static  function CheckToken()
    {
        return Auth::guard('sanctum')->check();
    }


    public static function saveToken(string $token)
    {
       session()->flush('PersonSaveToken',$token);
    }

    public static function getToken()
    {
        return session()->get('PersonSaveToken');
    }

    public static function removeToken()
    {
        return session()->forget('PersonSaveToken');

    }


    public static function beginTokenListener()
    {
        return  Api::Render([
            Api::Status()   => Api::Error(),
            Api::Message()  => "unauthentication",
        ],Api::ErrorCode());
    }

}
