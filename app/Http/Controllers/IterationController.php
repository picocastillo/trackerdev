<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use \App\Iteration;
use \App\Task;
use Carbon\Carbon;
use \App\Deposit;
use \App\Project;
use \App\Effort;
use \App\User;
use \App\Item;
use Validator;


class IterationController extends Controller
{
    function create($project_id){
        $project = Project::FindOrFail($project_id);
        return view('manager.iteration.create',compact('project'));
    }
    function edit($project_id){
        $iteration = Project::findOrFail($project_id)->getLastIteration();
        if (!$iteration){
            abort(401,"No existen iteraciones en el proyecto");
        }
        if ($iteration->is_closed){
            abort(401,"No se puede editar la iteracion, ya está cerrada");
        }
        $objetives = $iteration->getObjetives();
        $tasks = $iteration->tasks;
        $now = new \DateTime();
        $d = new \DateTime($iteration->delivery);
        $time  = $now->diff($d)->d + 1;
        return view('manager.iteration.edit',['objetives' => $objetives,'tasks' => $tasks, 'time' => $time, "iteration" => $iteration]);
    }

    function store(Request $request){
        $request->validate([
            "project_id" => "required",
            "objetives" => "required",
            "time" => "required",
            "title" => "required",
        ]);
        DB::beginTransaction();
        try {
            $project_id = (integer) $request->project_id;
            $objetives = $request->objetives;
            $time = strval( $request->time);
            $billed_hours = $request->billed_hours;
            $title = $request->title;

            $project = Project::findOrFail($project_id);
            $last_it = $project->getLastIteration();
            if ($last_it){
                $last_it->is_active = false;
                $last_it->save();

            }
            
            $iteration = new Iteration(
                $project_id,
                $objetives,
                $billed_hours,
                $time,
                $title
            );
            $total_by_task = 0;
            //FALTA CHEQUEAR SI LO BILLED DE LA ITERACIÓN NO ES MAYOR A LO BILLED POR TAREA O AL ESTIMADO
            if (isset($request->tasks['title'])){
                foreach ($request->tasks['title'] as $i => $t){
                    $total_by_task += $request->tasks['billed'][$i];
                    $task = new Task(
                        $request->tasks['title'][$i],
                        $request->tasks['description'][$i],
                        $request->tasks['estimation'][$i],
                        $request->tasks['billed'][$i],
                        $iteration->id,
                        $project_id,
                    );
                }
            }

            if ($total_by_task>$request->billed_hours){
                return redirect()->back()->with('alert-danger', 'No puede ser mayor horas por tarea que facturadas');
            }

            DB::commit();
            return redirect('project')->with('status', 'Iteración creada');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            abort(401,"Error creating Iteration");
        }    
    }

    function update(Request $request){
        $request->validate([
            "project_id" => "required",
            "objetives" => "required",
            "time" => "required",
            "title" => "required",
        ]);
        DB::beginTransaction();
        try {
            $project_id = (integer) $request->project_id;
            $project = Project::findOrFail($project_id);
            $iteration = $project->getLastIteration();
            if (!$iteration){
                abort(401,"Iteración inexistente");
            }
            $objetives = $request->objetives;
            $time = date("Ymd",strtotime(strval( $request->time) . " day"));;
            $iteration->billed_hours  = $request->billed_hours;

            $iteration->objetives = $objetives;
            $iteration->delivery = $time;

            $iteration->save();
            

            // foreach ($iteration->tasks as $key => $value) {
            //     Task::findOrFail($value->id)->delete();
            // }

            // //FALTA CHEQUEAR SI LO BILLED DE LA ITERACIÓN NO ES MAYOR A LO BILLED POR TAREA O AL ESTIMADO
            // foreach ($request->tasks['title'] as $i => $t){
            //     $task = new Task(
            //         $request->tasks['title'][$i],
            //         $request->tasks['description'][$i],
            //         $request->tasks['estimation'][$i],
            //         $request->tasks['billed'][$i],
            //         $iteration->id,
            //         $project_id,
            //     );
            // }
            DB::commit();
            return redirect('home')->with('status', 'Iteración editada');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            abort(401,"Error creating Iteration");
        }    

    }
}
