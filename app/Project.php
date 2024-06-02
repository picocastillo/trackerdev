<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','description'
    ];

    function getDate(){
        return date('d/m h:i',strtotime($this->created_at));
    }
    function getEffortsByProject(){
        return Effort::where('project_id',$this->id)->sum('amount');
    }
    function getHoursByProject(){
        return Task::where('project_id',$this->id)->where('paid',false)->sum('billed');
    }
    
    
    ///////////////////
    //RELATIONS
    //////////////////
    function deposits(){
        return $this->hasMany(Deposit::class);
    }

    function tasks(){
        return $this->hasMany(Task::class);
    }

    function users(){
        return $this->belongsToMany(User::class);
    }
    
    
    ///////////////////
    //METHODS
    //////////////////

    function getName(){
        return  $this->name;
    }

    function getIterationsToClient(){
        $res = [];
        $billed_hours = 0;
        foreach ($this->iterations as $iteration){
            $data = $iteration->getDataToClients();
            $res[] = $data; 
            $billed_hours += $data['billed_hours'];

            // $billed_hours += Effort::join('tasks','tasks.id','=','efforts.task_id')
            // ->join('projects','projects.id','=','tasks.project_id')
            // ->where('project_id','=',$project->id)
            // ->where('tasks.iteration_id','=',null)
            // ->where('efforts.user_id','=',2)//JUST SUM MY HOURS ! ! ! !
            // ->sum('efforts.amount');
        }
        //mantenences
        $task =  Project::findOrFail($this->id)->tasks()->where('iteration_id',null)->first();
        if ($task){
            $billed_hours = $billed_hours + $task->efforts->sum('amount');

        }

        if ($billed_hours)
            $res['billed_hours'] = $billed_hours;
        return $res;
    }

    function getLastIteration(){
        if ($this->iterations()->count()==0) return false;
       return $this->iterations()->orderBy('created_at','DESC')->take(1)->first();
    }

    function getClient(){
        foreach ($this->users as $u){
            if ($u->isClient()){
                return $u;
            }
        }
        return false;
    }
}
