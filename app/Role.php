<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'seniority'
    ];

    ///////////////////
    //RELATIONS
    //////////////////
    function users(){
        return $this->hasMany(User::class);
    }

    function isClient(){
        return $this->seniority=="stackeholder";
    }
    function isSemiSenior(){
        return $this->seniority=="semi-senior";
    }
    function isSenior(){
        return $this->seniority=="senior";
    }
    function isJunior(){
        return $this->seniority=="junior";
    }
}
