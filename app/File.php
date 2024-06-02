<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'path','real_name', 'task_id', 'user_id'
    ];
    

    function task(){
        return $this->belongsTo(Task::class);
    }
    function user(){
        return $this->belongsTo(User::class);
    }

    function getDate(){
        return $this->created_at->format("d-m-Y H:i:s");
    }
}
