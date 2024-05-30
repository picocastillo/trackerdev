<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'message','is_private', 'task_id', 'user_id'
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

    ///////////////////
    //METHODS
    //////////////////


    function getUser(){
        return $this->user->name;
    }
    function getDate(){
        return $this->created_at->format("d-m-Y H:i:s");
    }
}
