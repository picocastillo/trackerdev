<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Iteration extends Model
{
    protected $fillable = [
        'objetives','billed_hours','delivery'
    ];

    function __construct($project_id=null,$objetives=null,$billed_hours=null,$time=null,$title=null){
        
        if (!$project_id or !is_integer($project_id)) return;
        $this->project_id = $project_id;
        $this->billed_hours = $billed_hours;
        $this->delivery = date("Ymd",strtotime($time . " day"));
        $this->objetives = json_encode($objetives);
        $this->title = $title;

        $this->save();
  
  
  
    }

    ///////////////////
    //RELATIONS
    //////////////////
    function project(){
        return $this->belongsTo(Project::class);
    }
    function tasks(){
        return $this->hasMany(Task::class);
    }

    ///////////////////
    //METHODS
    //////////////////
    

    function getDelivery(){
        return date("d-m-Y",strtotime($this->delivery));
    }
    function getDate(){
        return date("d-m-Y",strtotime($this->created_at));
    }

    function getDataToClients(){
        $objs = [];
        foreach (json_decode($this->objetives) as $obj){
            $objs[] = $obj;
        }
        $tasks = [];
        foreach ($this->tasks as $task){
            $tasks[] = $task->getDataToClient();
        }
        $delivery = $this->delivery;
        if ($delivery){
            $delivery =  date("d-m",strtotime($delivery));
        }
        return [
            'objetives' => $objs,
            'billed_hours' => self::getBilledHours(),
            'estimated_hours' => $this->estimated_hours,
            'is_closed' => $this->is_closed,
            'tasks' => $tasks,
            'percentage' => self::getPercentage(),
            'delivery' => $delivery,
            'belong_to_by_hours_user' => self::getClient() ? self::getClient()->isByHours() : 0
        ];
    }

    function getBilledHours(){
        if ($this->billed_hours)
            return $this->billed_hours;
        else {
            $total = 0;
            foreach ($this->tasks as $key => $value) {
                $total += $value->getEfforts();
            }
            return $total;

        }

    }

    function getEHoursOfTask(){
        $total = 0;
        foreach ($this->tasks as $key => $value) {
            $total += $value->estimation;
        }
        return $total;
    }
    function getFHoursOfTask(){
        $total = 0;
        foreach ($this->tasks as $key => $value) {
            $total += $value->billed;
        }
        return $total;
    }

    function getClient(){
        $user = $this->project->users()->where('role_id',1)->count();
        if ($user>0)
            return $this->project->users()->where('role_id',1)->get()->first();
        return 0;    
    }
    function getObjetives(){
        return $this->objetives;
    }
    
    function getPercentage(){
        $tasks = $this->tasks;
        $n = count($tasks);
        if (!$n){
            return 100;
        }
        $sum = 0;
        foreach ($tasks as $task){
            $sum = $sum + $task->getPercentage();
        }
        return $sum / $n;
    }


}
