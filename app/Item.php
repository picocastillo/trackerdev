<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name', 'task_id'
    ];

    ///////////////////
    //RELATIONS
    //////////////////
    function task(){
        return $this->belongsTo(Task::class);
    }

    ///////////////////
    //METHODS
    //////////////////
    function isComplete(){
        return $this->completed;
    }

}
