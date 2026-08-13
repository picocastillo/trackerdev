<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'by_hours', 'role_id', 'is_active',
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
        'password' => 'hashed',
        'by_hours' => 'boolean',
        'is_active' => 'boolean',
    ];

    // /////////////////
    // RELATIONS
    // ////////////////
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function states()
    {
        return $this->hasMany(State::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function efforts()
    {
        return $this->hasMany(Effort::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // /////////////////
    // METHODS
    // ////////////////

    public function isManager()
    {
        return $this->role->isSenior() || $this->role->isSemiSenior();
    }

    public function isClient()
    {
        return $this->role->isClient();
    }

    public function isDeveloper()
    {
        return $this->role->isSenior() || $this->role->isSemiSenior() || $this->role->isJunior() || $this->role->isProfessional();
    }

    public function isProfessional()
    {
        return $this->role->isProfessional();
    }

    public function canShowTimes()
    {
        if (isManager() || isDeveloper()) {
            return true;
        }
        if ((isClient()) && ($this->by_hours)) {
            return true;
        }

        return false;
    }

    public function isByHours()
    {
        return $this->by_hours;
    }

    public function canChargeTime($task_id)
    {
        $task = Task::findOrFail($task_id);
        if (! $task) {
            return false;
        }
        if ($task->user_id == \Auth::user()->id) {
            return true;
        }

        return false;
    }

    public function getProjectsToClient()
    {
        $res = [];
        $total_hours = 0;
        foreach ($this->projects as $project) {
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
                'iterations' => $data,
            ];
        }
        $res['total_hours'] = $total_hours;

        return $res;
    }

    public function canSeeTask($id)
    {
        if (self::getRole() == 'senior') {
            return true;
        }

        $task = Task::findOrFail((int) $id);
        if (isClient()) {
            return in_array($task->project_id, \Auth::user()->projects()->pluck('projects.id')->toArray());

        }

        return $task->user_id == \Auth::user()->id || $task->watcher_id == $this->id;
    }

    public function getDeposits()
    {
        $res = [];
        $hours_paid = 0;
        foreach ($this->deposits as $deposit) {
            $data = $deposit->getData();
            $hours_paid += $data['hours'];
            $res[] = $data;
        }
        $res['hours_paid'] = $hours_paid;

        return $res;
    }

    public function getRole()
    {
        return $this->role->seniority;
    }

    public function getHoursLastMonth()
    {
        if (! self::isClient()) {
            return false;
        }
        $all = [];
        $from = date('01-m-Y');
        $to = date('t-m-Y', strtotime($from));
        foreach ($this->projects as $project) {
            $iteration = $project->getLastIteration();
            foreach ($iteration->tasks as $task) {
                $all = array_merge($all, $task->efforts()
                    ->where('created_at', '>=', $from)
                    ->where('created_at', '<=', $to)
                    ->get()->toArray());
            }
        }
        // MISSING ADD MANADGEMNT EFFORTS

        return $all;
    }
}
