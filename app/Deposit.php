<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = [
        'amount','hours'
    ];

    function __construct($amount=null, $hours=null, $user_id=null){
        if ((!$user_id) || !is_integer($user_id)) return;

        $this->amount = $amount;
        $this->hours = $hours;
        $this->user_id = $user_id;

        $this->save();
    }

    ///////////////////
    //RELATIONS
    //////////////////
    function user(){
        return $this->belongsTo(User::class);
    }


    ///////////////////
    //METHODS
    //////////////////
    function getData(){
        return [
            'amount' => $this->amount,
            'hours' => $this->hours,
            'date' => date('d/m/Y', strtotime($this->created_at)),
        ];
    }
}
