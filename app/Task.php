<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name','description','estimation','risk','billed','is_private'
    ];
    
    function __construct($name=null,$description=null,$estimation=null,$billed=null,$iteration_id=null,$project_id=null, $user_id=null, $is_private=null,$task_id=null){
        if (!$project_id or !is_integer($project_id)) return;
        DB::beginTransaction();
        try {
            $this->name = $name;
            $this->description = $description;
            $this->estimation = $estimation;
            $this->billed = $billed;
            $this->iteration_id = $iteration_id;
            if ($is_private!=null)
                $this->is_private = $is_private;
            $this->project_id = $project_id;
            $this->task_id = $task_id;
            $this->save();
            State::create([
                'name' => 1,
                'task_id' => $this->id,
                'user_id' => \Auth::user()->id
            ]);
            if ($user_id){

                $this->user_id = $user_id;
                State::create([
                    'name' => 2,
                    'task_id' => $this->id,
                    'user_id' => \Auth::user()->id
                ]);

            }

            $this->save();
            

            DB::commit();
  
        } catch (\Exception $e) {
    
            DB::rollback();
            dd($e);
        }

    }

    ///////////////////
    //RELATIONS
    //////////////////
    function iteration(){
        return $this->belongsTo(Iteration::class);
    }
    function project(){
        return $this->belongsTo(Project::class);
    }
    function user(){
        return $this->belongsTo(User::class);
    }
    function task(){
        return $this->belongsTo(Task::class);
    }
    function tasks(){
        return $this->hasMany(Task::class);
    }
    function items(){
        return $this->hasMany(Item::class);
    }
    function notes(){
        return $this->hasMany(Note::class);
    }
    function states(){
        return $this->hasMany(State::class);
    }
    function efforts(){
        return $this->hasMany(Effort::class);
    }

    function files(){
        return $this->hasMany(File::class);
    }


    ///////////////////
    //METHODS
    //////////////////


    function getProductivity($user_id){
        $has_two_efforts = false;
        $total_efforts = 0;
        if (!$this->estimation){
            return 1;
        }
        foreach ($this->efforts as  $value) {
            $total_efforts += $value->amount;
            if ($value->user_id != $user_id)
            $has_two_efforts =  true;
        }

        if ($has_two_efforts){
            $total_efforts = self::getEfforts();
            $factor = $total_efforts;
        }else {
            if (!$total_efforts)
                return 0;
            return $this->estimation / $total_efforts;
        }
        
    }

    function getProductivity2($user_id){ //productivity by effort
        $has_two_efforts = false;
        $total_efforts = self::getEfforts();
        
        if (!$total_efforts)
            return 0;
        return $this->estimation / $total_efforts;
    }
        
    function getChildsProgress(){
        $total = count($this->tasks);
        $res = "";
        $total_testing = 0;
        $total_finished = 0;
        $total_assigned = 0;
        foreach ($this->tasks as $key => $value) {
            switch ($value->getLastState()) {
                case 2: //assigned
                    $total_testing++;
                    break;
                
                case 3: //testing
                    $total_testing++;
                    break;
                
                case 4: //finished
                    $total_finished++;
                    break;
                
                default:
                    # code...
                    break;
            }
        }
        
        return $total_testing."/".$total." T, ".$total_finished."/".$total." F, ".$total_assigned."/".$total." A ";
    }

    function watcher(){
        return $this->belongsTo(User::class,'watcher_id');
    }


    function isFather(){
        return $this->tasks()->count() != 0;
    }
    function getDate(){
        return $this->created_at->format("d-m-Y H:i:s");
    }

    function getDataToClient(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->name,
            'risk' => $this->risk,
            'percentage' => self::getPercentage(),
            'objetives' => $this->objs,
            'invoced' => $this->billed!==0.0,
            'estimation' => $this->billed===0.0 ? $this->estimation : $this->billed,
            'created' => self::getDaysCreatedAt(),
            'is_private' => $this->is_private,
        ];
    }
    function totalHours(){
        return $this->efforts()->sum('amount');
    }
    function totalHoursByUser($user_id){
        return $this->efforts()->where('user_id',$user_id)->sum('amount');
    }
    function getEfforts(){
        $total = 0;
        foreach ($this->efforts as $key => $value) {
            $total += $value->amount * $value->user->role->weight;
        }
        return $total;
    }
    function getEffortsByUser($user_id){
        $total = 0;

        foreach ($this->efforts as $key => $value) {
            if ($value->user_id == $user_id)
                $total += $value->amount * $value->user->role->weight;
        }
        
        return $total;
    }

    function getPercentage(){
        if ( $this::getLastState()==4)
            return 100;
        $n = $this->items()->count();
        if (!$n) return 0;

        $completed = 0;
        foreach ($this->items as $item){
            if ($item->isComplete()){
                $completed++;
            }
        }
        return $completed/$n * 100;
        
    }

    function isComplete(){
        foreach ($this->items as $item){
            if (!$item->isComplete()){
                return false;
            }
        }
        return true;
    }

    function getDaysCreatedAt(){
        $now = date("Y-m-d H:i:s");
        $created = $this->created_at;
        
        $interval = $created->diff($now);
        return $interval->format('%a');
    }

    function getLastState(){
        $states = $this->states;
        if (!count($states))
            return false;
        return $this->states()->orderby('id','DESC')->first()->name;

    }

    function isToTest(){
        return ((self::getLastState()==3) && ($this->states()->count()>2));
    }
    function getNotes(){
        return $this->notes()->orderby('created_at','DESC')->get();
    }

    function assignTo($user_id){
        DB::beginTransaction();
        try {
            $this->user_id=$user_id;
            if (self::getLastState()!=3 && self::getLastState()!=2){// is not in testing and is not asigned
                State::create([
                    'name' => 2,
                    'task_id' => $this->id,
                    'user_id' => \Auth::user()->id
                ]);
            }
            $this->save();
            DB::commit();
        } catch (\Exception $e) {
        
            DB::rollback();
            dd($e);
        }
    }

    function assignedTo(){
        $user = $this->user;
        if(!$user)
            return false;
        return $user->name;
    }

    function changeToTesting(){
        DB::beginTransaction();
        try {
            State::create([
                'name' => 3,
                'task_id' => $this->id,
                'user_id' => \Auth::user()->id
            ]);
            DB::commit();
        } catch (\Exception $e) {
        
            DB::rollback();
            dd($e);
        }
    }

    function changeToFeedback(){
        DB::beginTransaction();
        try {
            State::create([
                'name' => 5,
                'task_id' => $this->id,
                'user_id' => \Auth::user()->id
            ]);
            DB::commit();
        } catch (\Exception $e) {
        
            DB::rollback();
            dd($e);
        }
    }

    function changeToFinish(){
        DB::beginTransaction();
        try {
            $this->is_active = false;
            State::create([
                'name' => 4,
                'task_id' => $this->id,
                'user_id' => \Auth::user()->id
            ]);
            DB::commit();
        } catch (\Exception $e) {
        
            DB::rollback();
            dd($e);
        }
    }

    function getTitle(){
        return str_pad($this->id+1111, 5, '0', STR_PAD_LEFT)." - ".$this->name;
    }

}
