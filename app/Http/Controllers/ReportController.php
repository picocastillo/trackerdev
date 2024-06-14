<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use \App\Project;
use \App\Task;
use \App\Effort;
use \App\Iteration;
use \App\Report;
use \App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;
use DB;

class ReportController extends Controller
{
    function index(){
        $efforts  = Effort::where('paid',false)->where('user_id',\Auth::user()->id)->get();

        $start_date =  date("Y-m-01");
        $end_date =date("Y-m-t");
        if (isSenior()){
            $users  = User::all();
            $reports = Report::orderby('id','desc')->paginate(10);
            return view('reports.index',compact('users','start_date','end_date','reports','efforts'));
        }else if (isClient()){
            $users  = User::all();
            $reports = Report::orderby('id','desc')->paginate(10);
           
            return view('reports.index',compact('users','start_date','end_date','reports'));
        }else { //developer or professional
            $users  = User::all();
            $reports = Report::where('user_id',\Auth::user()->id)->orderby('id','desc')->paginate(10);
            return view('reports.index',compact('users','start_date','end_date','reports','efforts'));
        }
    }

    function show($id){
        $report = Report::findOrFail($id);
        if ((!isSenior()) && ($report->user_id != \Auth::user()->id)){
            abort(401,"No podes ver este reporte");
        }
        $tasks = Task::whereIn('id',explode(',',$report->tasks))->get();
        $efforts = Effort::whereIn('id',explode(',',$report->efforts))->get();

        $by_project = [];
        foreach ($efforts as $key => $value) {
            $by_project[$value->project->name][] = [
                "amount" => $value->amount,
                "detail" => $value->detail,
                "date" => $value->getDate(),
                "task_id" => $value->task_id,
                "title_task" => $value->task ? $value->task->getTitle() : '',
            ];
        }
        // dd($by_project);

        return view('reports.show',compact('report','tasks','by_project'));
    }

    function create(Request $request){
        $request->validate([
            'start_date'=> 'date|before_or_equal:end_date',
            'end_date'=> 'date',
            'user_id' => 'required'
          ],[
            'start_date.date'=>"La fecha inicial no es válida",
            'start_date.before_or_equal'=>"La fecha inicial no debe ser mayor a la fecha final",
            'end_date.date'=>"La fecha final no es válida"
          ]);
        
        $from = $request->get('start_date')==null ? date("Y-m-01") : $request->get('start_date');
        $to = $request->get('end_date')==null ? date("Y-m-t") : $request->get('end_date');
        $project_id = $request->get('project_id')==null ? "all" : $request->get('project_id');
        $user = User::findOrFail($request->user_id);
        if ($user->isClient()){//is client
            $projects_ids = $user->projects()->pluck('projects.id')->toArray();
            $tasks = Task::wherein('project_id',$projects_ids)->whereBetween('created_at', [$from, $to])->get();
            $projects = $user->projects;
            // $times = $user->efforts()->whereBetween('created_at', [$week_start, date('Y-m-d', strtotime('+6 days'))])->get();
            // $week_hours = $user->efforts()->whereBetween('created_at', [$week_start, $week_end])->sum('amount');
            return view('reports.create',compact('user','tasks','from','to','projects'));
            
            
        }else { //is developer
            $efforts = $user->efforts()->whereBetween('created_at', [$from." 00:00:00", $to." 23:59:59"])->get();
            $tasks = Task::whereBetween('created_at', [$from." 00:00:00", $to." 23:59:59"])->where('user_id',$user->id)->get();
            return view('reports.create',compact('user','efforts','tasks','from','to'));
        }



    }


    function store(Request $request){
        $request->validate([
            'from'=> 'required|date',
            'to'=> 'required|date',
            'user_id' => 'required',
            'tasks' => 'required|array',
            'efforts' => 'required|array',
            'productivity' => 'required',
            'billed_hours' => 'required',
            'rate' => 'required',
        ]);
        // dd($request->all());
        $report = Report::orderby('id','desc')->where('user_id',$request->user_id)->first();
        if ($report && date($report->to)>=date($request->from)){
            return redirect()->back()->with('alert-danger','Estas creando un reporte con una fecha ya incluida por otro para un mismo usuario');
        }

        try {
            DB::beginTransaction();
            $user = User::FindOrFail($request->user_id);

            if ($user->isDeveloper()){
                Report::create([
                    'from'=> $request->from,
                    'to'=> $request->to,
                    'user_id' => $request->user_id,
                    'tasks' => implode(',',$request->tasks),
                    'efforts' => implode(',',$request->efforts),
                    'productivity' => $request->productivity,
                    'billed_hours' => $request->billed_hours,
                    'rate' => $request->rate,
                    'detail' => $request->detail ? $request->detail : '',
                ]);
                Effort::wherein('id',$request->efforts)->update(['paid'=>true]);
            }else {

                $iterations = Iteration::join('projects','projects.id','=','iterations.project_id')
                                    ->join('project_user','project_user.project_id','=','projects.id')
                                    ->where('project_user.user_id',$request->user_id)
                                    ->select('iterations.id')
                                    ->get();
                foreach ($iterations as $value) {
                    $value->is_active = false;
                    $value->save();
                }
                Report::create([
                    'from'=> $request->from,
                    'to'=> $request->to,
                    'user_id' => $request->user_id,
                    'tasks' => implode(',',$request->tasks),
                    'efforts' => implode(',',$request->efforts),
                    'productivity' => $request->productivity,
                    'billed_hours' => $request->billed_hours,
                    'rate' => $request->rate,
                    'detail' => $request->detail ? $request->detail : '',
                ]);
                foreach ($request->tasks as $value) {
                    $task = Task::findOrFail($value);
                    $task->is_active = false;
                    $task->save();
                }
            }

            DB::commit();

        }catch (\Exeption $e){

        }

        return redirect()->back();


    }
}
