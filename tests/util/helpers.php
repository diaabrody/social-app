<?php
function make($class , $attributes=[]){
    return factory($class)->make($attributes);
}
function create($class , $attributes =[] , $times = null){
    return factory($class ,$times)->create($attributes);
}

function createUser(){
    $userInfo = [
        'email'=>'diaaOsama850@outlook.com' ,
        'password'=>'brodybrody' ,
        'password_confirmation'=>'brodybrody',
        'name'=>'diaa osama'
    ];
    \App\User::create($userInfo);
    return $userInfo;
}
