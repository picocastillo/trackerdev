<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'efforts', 'user_id', 'productivity', 'rate', 'detail', 'from', 'to','total'
    ];

    function expence(){
        return $this->hasOne(Expense::class);
    }

    function getDate(){
        return $this->from ." - ".$this->to;
    }

}
