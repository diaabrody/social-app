<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract  class CustomTestCase  extends TestCase
{
    use DatabaseMigrations ;
    private  $responseJsonStructure =
        [
            'success' ,
            'code' ,
            'locale' ,
            'message' ,
            'data' =>[]
        ];
    private  $paginationStructure=["links"=>[
        "first",
        "last",
        "prev",
        "next",
    ] , "meta"=>[
        "current_page",
        "from",
        "last_page",
        "path",
        "per_page",
        "to",
        "total"
    ]];

    protected function login($user = null , $token =null){
        $user = $user?:factory('App\User')->create();
        $token = $token?: $this->getUserToken($user);
        $this->withHeader('Authorization' , "Bearer $token");
        $this->actingAs($user);
        return $user;
    }
    protected function getResponseJsonStructure($data= null){
        $responseJsonStructure = $this->responseJsonStructure;
        if ($data)
            $responseJsonStructure['data'] =$data;
        return $responseJsonStructure;
    }

    protected function getResponsePaginationJsonStructure($data=null){
         $result= $this->getResponseJsonStructure($data);
         return  array_merge($this->paginationStructure ,$result);
    }

    /**
     * @param $user
     * @return mixed
     */
    protected function getUserToken($user)
    {
        $token = JWTAuth::fromUser($user);
        return $token;
    }
}
