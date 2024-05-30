<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'from',
        'to',
        'user_id' ,
        'tasks' ,
        'efforts' ,
        'productivity' ,
        'billed_hours' ,
        'rate' ,
        'detail'
    ];

    ///////////////////
    //RELATIONS
    //////////////////
    function user(){
        return $this->belongsTo(User::class);
    }


    ///////////////////
    //METHODS
    //////////////////
    
}
