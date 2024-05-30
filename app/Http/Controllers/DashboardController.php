<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Project;
use \App\Task;
use \App\Effort;
use \App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;
use DB;
use \App\Report;


class DashboardController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (isSenior()){
            if ($request->has('state_name')){
                // $tasks = Task::where('is_active',true)->orderby('id','desc')->paginate(10);

                $tasks = Task::select('tasks.*','states.name as state')->join(
                    DB::raw(
                    '(SELECT o.id as task_id, max(ss.id) as state_id from tasks o
                    inner join states ss
                    on o.id=ss.task_id
                    group by o.id) q'), 
                    function($join)
                    {
                       $join->on('q.task_id', '=', 'tasks.id');
                    })->join('states','states.id','=','q.state_id')
                    ->orderBy('updated_at','DESC')
                    ->where('is_active',true)
                    ->where('states.name',$request->state_name)
                    ->paginate(10);

            }else if($request->has('state_name')){
                $tasks = Task::where('is_active',true)->where('project_id',$request->project_id)->orderby('id','desc')->paginate(10);
            }else if($request->has('user_id')){
                $tasks = Task::where('is_active',true)->where('user_id',$request->user_id)->orderby('id','desc')->paginate(10);
            }else {
                $tasks = Task::where('is_active',true)->orderby('id','desc')->paginate(10);

            }
            if ($request->has('user_id')){
                $tasks = Task::where('is_active',true)->where('user_id',$request->user_id)->orderby('id','desc')->paginate(10);
            }
            if ($request->has('project_id')){
                $tasks = Task::where('is_active',true)->where('project_id',$request->project_id)->orderby('id','desc')->paginate(10);
            }
            
            $devs = User::where('role_id','!=',4)->get();
            $projects = Project::all();
            return view('home',compact('tasks','devs','projects'));

        }else if (isDeveloper()){
            $tasks = Task::where('is_active',true)->where('user_id',\Auth::user()->id)->orderby('id','desc')->paginate(10);
            $devs = User::where('role_id','!=',4)->get();
            $projects = \Auth::user()->projects;
            return view('home',compact('tasks','devs','projects'));
        }
        //is client
        $reports = Report::where('user_id',\Auth::user()->id)->paginate(10);
        return view('reports.index',compact('reports'));
        
    }

 
    // public function index()
    // {
    //     $user = \Auth::user();
    //     $projects = null;
    //     $deposits = null;
    //     $devs =  User::where('role_id',2)->orWhere('role_id',3)->select('name','id')->get();
    //     if ($user->isManager()){
    //         $projects =  Project::select('name','id')->get();
    //         $tasks = Task::where('invoiced','!=',0)
    //                 ->orderBy('tasks.created_at','desc')
    //                 ->get();
    //         return view('home', [ 'projects' => $projects, 'tasks' => $tasks, 'devs' => $devs]);
            
    //     }
    //     if ($user->isDeveloper()){
    //         $day = date('w');
    //         $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
    //         $week_end = date('Y-m-d', strtotime('+'.(7-$day).' days'));

    //         $projects =  $user->projects;
    //         $tasks = Task::where('invoiced','!=',0)
    //                 ->where('user_id',$user->id)
    //                 ->get();
            
    //         $times = $user->efforts()->whereBetween('created_at', [$week_start, date('Y-m-d', strtotime('+6 days'))])->get();
    //         $week_hours = $user->efforts()->whereBetween('created_at', [$week_start, $week_end])->sum('amount');
    //         return view('home', [ 'projects' => $projects, 'tasks' => $tasks, 'devs' => $devs, 'times' => $times, 'week_hours' => $week_hours ]);
            
    //     }
    //     if ($user->isClient()){
    //         $deposits = $user->getDeposits();
    //         $projects = $user->getProjectsToClient();
    //         $tasks = [];
    //         $tasks_aux = Task::where('iteration_id',null)->get();
    //         foreach ($tasks_aux as $task){
    //             foreach($projects as $p){
    //                 if ($task->project->toArray()['name']==$p['name']){
    //                     $tasks[] = $task;
    //                     break;
    //                 }

    //             }
    //         }

    //         return view('home', ['projects' => $projects, 'deposits' => $deposits, 'tasks' => $tasks]);
    //     }
        
    //     abort(401,"Error with your user");
    // }

    public function indexFiltered(Request $request)
    {
        // $this->validate($request, [
        //     'user_id' => 'required|unique|max:255',
        //     'project_id' => 'required',
        //     'state' => 'required',
        // ]);
        $user = \Auth::user();
        $projects = null;
        $deposits = null;
        $devs =  User::where('role_id',2)->orWhere('role_id',3)->select('name','id')->get();
        if ($user->isManager() ){
            $projects =  Project::select('name','id')->get();
            
            if ($request->user_id!="")
                $tasks = Task::where('invoiced','!=',0)
                        ->where('user_id',$request->user_id)
                        ->orderBy('tasks.created_at','desc')
                        ->get();
            else if ($request->user_id!="" && $request->project_id!="")
                $tasks = Task::where('invoiced','!=',0)
                        ->where('user_id',$request->user_id)
                        ->where('project_id',$request->project_id)
                        ->orderBy('tasks.created_at','desc')
                        ->get();
            else if ($request->project_id!="")
                $tasks = Task::where('invoiced','!=',0)
                        ->where('project_id',$request->project_id)
                        ->orderBy('tasks.created_at','desc')
                        ->get();
            else
                $tasks = Task::where('invoiced','!=',0)
                        ->orderBy('tasks.created_at','desc')
                        ->get();
            
            if ($request->state!=""){
                $newtask = [];
                foreach ($tasks as $t)
                    if ($t->getLastState()==$request->state)
                        $newtask[] = $t;
                
                $tasks = $newtask;        
            }
                
            

            return view('home', [ 'projects' => $projects, 'tasks' => $tasks, 'devs' => $devs]);
            
        }
        if ($user->isClient()){
            $projects = $user->getProjectsToClient();
            return view('home', ['projects' => $projects]);
        }
        if ($user->isDeveloper()) {
            $day = date('w');
            $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
            $week_end = date('Y-m-d', strtotime('+'.(7-$day).' days'));

            $projects =  $user->projects;
            $tasks = Task::where('invoiced','!=',0)
                    ->where('user_id',$user->id)
                    ->get();
             if ($request->project_id!=""){
                 $tasks = Task::where('invoiced','!=',0)
                         ->where('project_id',$request->project_id)
                         ->where('user_id',$user->id)
                         ->orderBy('tasks.created_at','desc')
                         ->get();
             }
             if ($request->state!=""){
                $newtask = [];
                foreach ($tasks as $t)
                    if ($t->getLastState()==$request->state)
                        $newtask[] = $t;
                
                $tasks = $newtask;        
            }
            $times = $user->efforts()->whereBetween('created_at', [$week_start, date('Y-m-d', strtotime('+6 days'))])->get();
            $week_hours = $user->efforts()->whereBetween('created_at', [$week_start, $week_end])->sum('amount');
            return view('home', [ 'projects' => $projects, 'tasks' => $tasks, 'devs' => $devs, 'times' => $times, 'week_hours' => $week_hours ]);
        }
        abort(401,"Error with your user");
    }

    function showProfile(){
        return view('profile',['user' => \Auth::user()]);
    }


    function updateProfile(Request $request){
        request()->validate([
            'name' => 'required',
            'author' => 'required',
        ]);
        $cover = $request->file('bookcover');
        $extension = $cover->getClientOriginalExtension();
        Storage::disk('public')->put($cover->getFilename().'.'.$extension,  File::get($cover));
    
        // $book = new Book();
        // $book->name = $request->name;
        // $book->author = $request->author;
        // $book->mime = $cover->getClientMimeType();
        // $book->original_filename = $cover->getClientOriginalName();
        // $book->filename = $cover->getFilename().'.'.$extension;
        // $book->save();


        return view('profile',['user' => \Auth::user()]);
    }

    
    function showDeposits(Request $request){
        $request->validate([
            'start_date'=> 'date|before_or_equal:end_date',
            'end_date'=> 'date',
          ],[
            'start_date.date'=>"La fecha inicial no es válida",
            'start_date.before_or_equal'=>"La fecha inicial no debe ser mayor a la fecha final",
            'end_date.date'=>"La fecha final no es válida"
          ]);
        $user = \Auth::user();
        $projects = $user->getProjectsToClient();
        $start_date = $request->get('start_date')==null ? date("Y-m-01") : $request->get('start_date');
        $end_date = $request->get('end_date')==null ? date("Y-m-t") : $request->get('end_date');
        if ($user->isClient()){
            $deposits = $user->getDeposits();
            $by_hours = $user->isByHours();
            $project_id = $user->projects->first()->id;//ACA SOLO TRAIGO EL PRIMERO, FALTA SELECT para elegir proyecto
            $total = Effort::whereBetween('efforts.created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])
                                ->join('tasks','tasks.id','=','efforts.task_id')
                                ->select(DB::raw('sum(amount) as total, project_id'))
                                ->groupby('project_id')
                                ->having('project_id',$project_id)
                                ->first();
            $total = $total ? $total->total : 0;
            $efforts = Effort::whereBetween('efforts.created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])
                                ->select('efforts.created_at as date','detail','amount','tasks.id as task_id')
                                ->join('tasks','tasks.id','=','efforts.task_id')
                                ->where('tasks.project_id',$project_id)
                                ->get();

            
            return view('deposits', ['deposits' => $deposits,
                         'projects' => $projects,
                         'efforts' => $efforts,
                         'by_hours' => $by_hours,
                         'start_date' => $start_date,
                         'total' => $total,
                         'end_date' => $end_date]);
        }
        if (!$user->isManager()){
            abort(401,"Usted no tieene permiso.");
        }
    }
    
    function showReports(Request $request){
        $request->validate([
            'start_date'=> 'date|before_or_equal:end_date',
            'end_date'=> 'date',
          ],[
            'start_date.date'=>"La fecha inicial no es válida",
            'start_date.before_or_equal'=>"La fecha inicial no debe ser mayor a la fecha final",
            'end_date.date'=>"La fecha final no es válida"
          ]);
        $user = \Auth::user();
        $start_date = $request->get('start_date')==null ? date("Y-m-01") : $request->get('start_date');
        $end_date = $request->get('end_date')==null ? date("Y-m-t") : $request->get('end_date');
        $project_id = $request->get('project_id')==null ? "all" : $request->get('project_id');


        $users  = User::all();

        return view('reports.create',compact('users'));

        // if($user->isManager() || $user->isDeveloper()){
            
            
        //     $projects = $user->projects;
        //     $name_project = [];
        //     foreach ($projects as $key => $value) {
        //         $name_project[$value->id] = $value->name;
        //     }
        //     if ($project_id=="all"){
        //         $percentages = $user->efforts()->
        //                                         whereBetween('efforts.created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])
        //                                         ->join('tasks','tasks.id','=','efforts.task_id')->select(DB::raw('sum(amount) as total, project_id'))
        //                                         ->groupby('project_id')
        //                                         ->get();
        //         $total = $user->efforts()->whereBetween('efforts.created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])->sum('amount');
        //         $efforts = $user->efforts()->whereBetween('efforts.created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])->get();

        //     }else {
        //         $percentages = $user->efforts()->
        //         whereBetween('efforts.created_at', [$start_date,$end_date])
        //                         ->join('tasks','tasks.id','=','efforts.task_id')->select(DB::raw('sum(amount) as total, project_id'))
        //                         ->groupby('project_id')
        //                         ->having('project_id',$project_id)
        //                         ->get();
        //         $total = $user->efforts()->
        //                                 whereBetween('efforts.created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])
        //                                 ->join('tasks','tasks.id','=','efforts.task_id')
        //                                 ->where('tasks.project_id',$project_id)
        //                                 ->sum('amount');

        //         $efforts = $user->efforts()->
        //         whereBetween('efforts.created_at', [$start_date, date('Y-m-d', strtotime('+6 days'))])
        //         ->join('tasks','tasks.id','=','efforts.task_id')
        //         ->where('tasks.project_id',$project_id)
        //         ->get();

        //     }
        //     return view('reports.index', [  'efforts' => $efforts,
        //                                 'total' => $total,
        //                                 'start_date' => $start_date ,
        //                                 'end_date' => $end_date,
        //                                 'percentages' => $percentages,
        //                                 'name_project' => $name_project,
        //                                 'project_id' => $project_id
        //                                 ]);

        // }

        // abort(401,"Usted no tiene permiso");
    }

}
