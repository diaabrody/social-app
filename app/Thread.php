<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    //

    public function user(){
        return $this->belongsTo(User::class);
    }

    public static function  persist($thread){
        return self::create([
            'body' =>$thread['body']
        ]);
    }
}
