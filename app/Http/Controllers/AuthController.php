<?php

namespace App\Http\Controllers;

use App\ApiCode;
use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login' , 'register']);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login()
    {
        $credentials= request()->validate(['email' =>'required|email' , 'password' =>'required']);
        if (!$token  = auth()->attempt($credentials)){
           return $this->respondUnAuthorizedRequest(ApiCode::INVALID_CREDENTIALS);
        }
        return $this->respondWithToken($token);
    }

    /**
     * @param $token
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function respondWithToken($token){
        return $this->respond([
            'token' => $token ,
            'token_type'=>'bearer',
            'expires'=> auth()->factory()->getTTL() * 60

        ] , 'Login Successful'  );

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logout()
    {
        auth()->logout();
        return $this->respondWithMessage('user successfully logout');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function refresh()
    {
        $token =auth()->refresh();
        return $this->respondWithToken($token);

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function me()
    {
       return $this->respond(auth()->user());
    }


    /**
     *
     */
    public function register(RegisterRequest $request){
        try {
            $validParams = $request->getAttributes();
            User::create($validParams);
            return $this->respondWithToken(auth()->attempt([
                'email' =>$validParams['email'],
                'password'=>$validParams['password']
            ]));
        }catch (\Exception $e){
            var_dump($e->getMessage());
            report($e);
           return $this->respondWithUnexpectedError('An Error Occured While Register User');
        }
    }
}
