<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use \App\Note;
use \App\Item;
use \App\Effort;
use \App\Task;
use \App\Deposit;
use \App\Project;
use \App\User;
use \App\File;
use Validator;
use App\Http\Middleware\CheckPermissions;

class TaskController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    function storeEffort(Request $request){
        $task_id = $request->task_id;
        $task = Task::findOrFail($task_id);
        if ($task->user_id!=\Auth::user()->id){
           abort("no podes cargar en tickets agenos!") ;
        }
        Effort::create([
            'detail' => $request->description,
            'user_id' => \Auth::user()->id,
            'task_id' =>(integer) $request->task_id,
            'amount' =>(float) $request->time,
        ]);
        return redirect('/tasks/' . $task_id)->with('status', 'Se cargaron '.$request->time.' horas')->with("active","times");
    }


    function completeItem(Request $request){

        $item = Item::findOrFail($request->item_id);
        $item->completed = true;
        $item->save();

        return redirect('/tasks/' . $item->task->id)->with('status', 'Item completado')->with("active","description");
    }

    function addMessage(Request $request){
        Note::create([
            'task_id' => (integer) $request->task_id,
            'user_id' => (integer) $request->user_id,
            'message' => $request->message,
            'is_private' => false,
        ]);

        return redirect('/tasks/' . $request->task_id)->with('status', 'Mensage agregado');
    }


    function create(){
        $projects =  Project::select('name','id')->get();
        $devs =  User::where('role_id','!=',4)->select('name','id')->get();
        return view('manager.task.create',['devs' => $devs, 'projects' => $projects, 'isEdit' =>false, 'items' =>[]]);
    }
    function createAChild($task_id){
        $task = Task::FindOrFail($task_id);
        $projects = Project::where('id',$task->project_id)->select('name','id')->get() ;
        $devs =  User::where('role_id','!=',4)->select('name','id')->get();
        return view('manager.task.create',['devs' => $devs, 'projects' => $projects, 'isEdit' =>false, 'items' =>[],'task_id' => $task_id]);
    }

    function edit($task_id){
        if (!\Auth::user()->isManager()){
            abort(401,"No podes");
        }
        $projects =  Project::select('name','id')->get();
        $devs =  User::where('role_id','!=',4)->select('name','id')->get();
        $task = Task::findOrFail($task_id);
        $items = $task->items()->select('name as desc')->get();
        return view('manager.task.create',['devs' => $devs, 'projects' => $projects, 'isEdit' =>$task_id, 'task' => $task, 'items' => $items]);
    }

    function chargeEffort(Request $request){//manualmente
        $request->validate([
            'project_id' => 'required',
            'amount' => 'required',
            'detail' => 'required'
        ]);
        Effort::create([
            'detail' => $request->detail,
            'user_id' => \Auth::user()->id,
            'project_id' =>(integer) $request->project_id,
            'amount' =>(integer) $request->time,
        ]);
        return redirect()->back()->with('alert-success','Tiempo cargado');

    }

    function addWatcher(Request $request){
        if (!\Auth::user()->isManager()){
            abort(401,"No podes");
        }
        $task = Task::findOrFail($request->task_id);
        $task->watcher_id = $request->user_id;
        $task->save();
        return redirect()->back();
    }

    function store(Request $request){
        $request->validate([
            'project_id' => 'required',
            'name' => 'required',
            'description' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $user_id = (integer) $request->user_id;
            $is_private = (integer) $request->is_private;
            $project_id = (integer) $request->project_id;
            $billed = (integer) $request->billed;
            $project = Project::findOrFail($project_id);
            
            $user = $project->getClient();
            if (!isInProject($project_id,$user_id)){
                return redirect()->back()->with('alert-danger',"El usuario no es parte del proyecto");
            }
            if (!$user){
                return redirect()->back()->with('alert-danger',"El proyecto no tiene cliente");
            }
          
            $estimation = (integer) $request->estimation;
            $task_id = $request->task_id ? (integer) $request->task_id : null;
            $task = new Task(
                $request->name,
                $request->description,
                $estimation,
                $billed,
                $project_id,
                $user_id==0 ? null : $user_id, 
                $task_id
            );
            if ($request->items){
                foreach ($request->items as $i){
                    Item::create([
                        'name' => $i,
                        'task_id' => $task->id
                    ]);
                } 
            }

            DB::commit();
            return redirect('/tasks/' . $task->id);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }    
    }

    function delete($id){
        
        try {
            DB::beginTransaction();
            if (!isSenior()){
                abort(401);
            }
            $task = Task::where('id',$id)->delete();
            DB::commit();
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }  
    }

    function addItem(Request $request){
        $this->validate($request, [
            'task_id' => 'required',
            'text' => 'required',
        ]);

        if (isManager() || isInTeam($request->task_id)){
            Item::create([
                'name' => $request->text,
                'task_id' => $request->task_id,
            ]);

        }
        return redirect('tasks/'.$request->task_id);    
    }

    function addTime(Request $request){
        $this->validate($request, [
            'task_id' => 'required',
            'time' => 'required',
        ]);

        if (isManager() ){
            
            $task = Task::FindOrFail($request->task_id);
            if ($task->history_time==""){
                $task->history_time = $request->time;
            }else {
                $aux = explode(',',$task->history_time);
                $aux[] = $request->time;
                $task->history_time = implode(',',$aux);

            }
            $task->estimation = $request->time + $task->estimation;
            $task->save();
            

        }
        return redirect('tasks/'.$request->task_id);    
    }

    function show($id){
        $task = Task::findOrFail($id);
        // if ((!\Auth::user()->isManager()) && ($task->user_id!=\Auth::user()->id) && ($task->watcher_id != \Auth::user()->id)){
        //     abort(401,"Usted no está autorizado para visualizar la tarea");
        // }
        $devs =  User::where('role_id',"!=",4)->select('name','id')->get();
        $childs = Task::where('task_id',$id)->get();

        return view('manager.task.show', compact('task','devs','childs'));
    }


    function assignTo(Request $request){
        $task_id = (integer) $request->task_id;
        $task = Task::findOrFail($task_id);
        if (!$task){
            abort("Error, no se encontro la tarea", 401);
        }
        if ($task->getLastState()==3 || $task->getLastState()==2){//it is testing
            $task->review++;
            $task->save();
        } 
        $user_id = (integer) $request->assign_to;
        $task->assignTo($user_id);
        return redirect('/tasks/' . $task->id)->with('status', 'Tarea Asignada');

    }

    function update(Request $request){
        $this->validate($request, [
            'task_id' => 'required',
            'name' => 'required',
            'user_id' => 'required',
            'project_id' => 'required',
        ]);

        $task = Task::findOrFail($request->task_id);
        
        DB::beginTransaction();
        try {
        
            $user_id = (integer) $request->user_id;
            $project_id = (integer) $request->project_id;
            $items = $task->items;
            /* if (count($items)>0){
                foreach ($items as $item){
                    Item::destroy($item->id);
                }
            }
            if (is_array($request->items) && count($request->items)>0){
                foreach ($request->items as $i){
                    Item::create([
                        'name' => $i,
                        'task_id' => $task->id,
                        'user_id' => \Auth::user()->id,
                    ]);
                } 
                
            } */

            $task->name = $request->name;
            $task->description = $request->description;
            $task->project_id = $project_id;
            $task->billed = $request->billed ? (float) $request->billed : null;
            $task->estimation = $request->estimation ? (float) $request->estimation : null;
            if ($user_id!="0")
                $task->assignTo($request->user_id) ;
            $task->save();

            DB::commit();
            return redirect('/tasks/' . $request->task_id)->with('status', 'Tarea editada');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }    

    }


    function attachFile(Request $request){
        $this->validate($request, [
            'file' => 'mimes:jpeg,jpg,png,gif,pdf|required|max:10000',
        ]);
        $path = $request->file('file')->store('public');//storage/3QzE1rLY91ZFz7CAcAtxQBKNSTA1ghMzXtJtE8iS.pdf
        File::create([
            'user_id' => \Auth::user()->id,
            'task_id' => $request->task_id,
            'real_name' => $request->file('file')->getClientOriginalName(),
            'path' => str_replace('public','storage',$path)
        ]);



       return redirect('/tasks/' . $request->task_id);
    }

    function changeToTesting(Request $request){
        // $this->validate($request, [
        //     'title' => 'required|unique|max:255',
        //     'body' => 'required',
        // ]);
        $task = Task::findOrFail($request->task_id);
        $task->changeToTesting(\Auth::user()->id);

       return redirect('/tasks/' . $request->task_id);
    }
    function changeToFeedback(Request $request){
        $task = Task::findOrFail($request->task_id);
        $task->changeToFeedback(\Auth::user()->id);

       return redirect('/tasks/' . $request->task_id);
    }

    function changeToFinished(Request $request){
        // $this->validate($request, [
        //     'title' => 'required|unique|max:255',
        //     'body' => 'required',
        // ]);
        $task = Task::findOrFail($request->task_id);
        $task->changeToFinish(\Auth::user()->id);

       return redirect('/tasks/' . $request->task_id);
    }

}
