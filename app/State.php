<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = [
        'name','task_id','user_id'
    ];

    ///////////////////
    //RELATIONS
    //////////////////
    function task(){
        return $this->belongsTo(Task::class);
    }
    function user(){
        return $this->belongsTo(User::class);
    }
}
