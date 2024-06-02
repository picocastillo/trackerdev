<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use \App\Iteration;
use \App\Task;
use Carbon\Carbon;
use \App\Deposit;
use \App\Project;
use \App\Effort;
use \App\User;
use \App\Item;

class ManagerController extends Controller
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


    

    function storeDeposit(Request $request){
        DB::beginTransaction();
        try {
        
            $amount = (integer) $request->amount;
            
            $project_id = (integer) $request->project_id;
            $user = Project::findOrFail($project_id)->getClient();
            if (!$user){
                abort(401,"error with project");
            }
            $hours = (integer) $request->hours;
            
            $deposit = new Deposit(
                        $amount,
                        $hours,
                        $user->id
            );

            DB::commit();
            return redirect('home')->with('status', 'Deposito agregado');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }    

    }
    


    function createReport(Request $request){
        $project = Project::findOrFail($request->project_id);
        // $iteration = $project->getLastIteration();
        // $efforts = Effort::join('tasks','tasks.id','=','tasks.project_id')
        //                     ->where('efforts.created_at','>=',$iteration->created_at)
        //                     ->where('tasks.project_id',$project->id)
        //                     ->get();
        $iteration_hours = [];
        $iteration_id = [];
        $project_id = [];
        $total_hours = [];
        $created_from = [];
        $created_end = [];
        foreach ($project->iterations as $key => $iteration) {
            $end_date_iteration = $key+1<count($project->iterations) ? $project->iterations[$key+1]->created_at : Carbon::now();
            $iteration_id[] = $iteration->id;
            $created_from[] = $iteration->created_at;
            $created_end[] = $end_date_iteration;
            $iteration_hours[] = $iteration->tasks->where('is_private',0)->sum('invoiced');
            $total_hours[] = $project->tasks()->
                                    whereBetween('tasks.created_at', [$iteration->created_at, $end_date_iteration])
                                    ->join('efforts','efforts.task_id','=','tasks.id')
                                    ->sum('efforts.amount');
        }

        return view('reports.new',compact('iteration_hours','total_hours','created_from','created_end','project','iteration_id'));


    }

    function detailReport(Request $request){
        $iteration = Iteration::findOrFail($request->iteration_id);
        // $efforts = Effort::join('tasks','tasks.id','=','tasks.project_id')
        //                     ->where('efforts.created_at','>=',$iteration->created_at)
        //                     ->where('tasks.project_id',$project->id)
        //                     ->get();
        $project = Project::findOrFail($request->project_id);
        $tasks = $project->tasks()->where('created_at','>=',$iteration->created_at)->get();
        $total_hours = $project->tasks()
                                ->whereBetween('tasks.created_at', [$request->created_from, $request->created_end])
                                ->join('efforts','efforts.task_id','=','tasks.id')
                                ->sum('efforts.amount');

        return view('reports.show',compact('tasks','iteration','total_hours'));


    }

    

}
