<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Effort extends Model
{
    protected $fillable = [
        'detail','amount', 'task_id', 'user_id','project_id'
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

    function getHours(){
        return number_format($this->amount/60, 2);
    }
}
