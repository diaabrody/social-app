<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Thread::class, function (Faker $faker) {
    return [
        'body'=>$faker->text(),
        'user_id'=>function(){
            return (factory('App\User')->create())->id;
        }
    ];
});
