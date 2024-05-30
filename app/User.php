<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','by_hours','role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    ///////////////////
    //RELATIONS
    //////////////////
    public function role(){
        return $this->belongsTo(Role::class);
    }
    function notes(){
        return $this->hasMany(Note::class);
    }
    function states(){
        return $this->hasMany(State::class);
    }
    function projects(){
        return $this->belongsToMany(Project::class);
    }
    function deposits(){
        return $this->hasMany(Deposit::class);
    }
    function efforts(){
        return $this->hasMany(Effort::class);
    }


    ///////////////////
    //METHODS 
    //////////////////

    function isManager(){
        return $this->role->isSenior() || $this->role->isSemiSenior();
    }
    function isClient(){
        return $this->role->isClient();
    }
    function isDeveloper(){
        return $this->role->isSenior() || $this->role->isSemiSenior() || $this->role->isJunior();

    }

    function canShowTimes(){
        if (isManager() || isDeveloper())
            return true;
        if ((isClient()) && ($this->by_hours))
            return true;
        return false;    
    }

    function isByHours(){
        return $this->by_hours;
    }

    function canChargeTime($task_id){
        $task = Task::findOrFail($task_id);
        if (!$task)
            return false;
        if ($task->user_id==\Auth::user()->id)
            return true;
        return false;        
    }

    

    function getProjectsToClient(){
        $res = [];
        $total_hours = 0;
        foreach ($this->projects as $project){
            $data = $project->getIterationsToClient();
            $total_hours += isset($data['billed_hours']) ? $data['billed_hours'] : 0;
            // $total_hours += Effort::join('tasks','tasks.id','=','efforts.task_id')
            // ->join('projects','projects.id','=','tasks.project_id')
            // ->where('project_id','=',$project->id)
            // ->where('tasks.iteration_id','=',null)
            // ->where('efforts.user_id','=',2)//JUST SUM MY HOURS ! ! ! !
            // ->sum('efforts.amount');
            $res[] = [
                'name' => $project->getName(),
                'iterations' => $data
            ];
        }
        $res["total_hours"] = $total_hours;
        return $res;
    } 

    function canSeeTask($id){
        if (self::getRole() == "senior")
            return true;

        $task = Task::findOrFail((integer) $id);
        return $task->user_id == \Auth::user()->id || $task->watcher_id == $this->id;
    }

    function getDeposits(){
        $res = [];
        $hours_paid = 0;
        foreach ($this->deposits as $deposit){
            $data = $deposit->getData();
            $hours_paid += $data['hours'];
            $res[] = $data;
        }
        $res['hours_paid'] = $hours_paid;
        return $res;
    } 

    function getRole(){
        return $this->role->seniority;
    }

    function getHoursLastMonth(){
        if (!self::isClient()){
            return false;
        }
        $all = [];
        $from = date('01-m-Y');
        $to = date("t-m-Y", strtotime($from));
        foreach ($this->projects as $project){
            $iteration = $project->getLastIteration();
            foreach ($iteration->tasks as $task){
               $all = array_merge($all,$task->efforts()
               ->where('created_at','>=',$from)
               ->where('created_at','<=',$to)
               ->get()->toArray());
            }
        }
        //MISSING ADD MANADGEMNT EFFORTS

        return $all;
    }

}
