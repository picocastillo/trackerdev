<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Project;
use Validator;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class ProjectController extends Controller
{
    function create(){
        $users = User::all();
        return view('projects.create',compact('users'));
    }

    function edit($id){
        $users = User::all();
        $project = Project::findOrFail($id);
        return view('projects.create',compact('users','project'));
    }


    function store(Request $request){
        $request->validate([
            'users_ids' => 'required',
            'title' => 'required',
        ]);


        try {
            DB::beginTransaction();
           
            $ids = explode(',',$request->users_ids);

            $has_a_stackeholder = false;
            $name_project_and_client = explode('-',$request->title);
            $project_name = "";
            $user_client = null;
            $email = null;
            $password = null;
            if (count($name_project_and_client)==3){
                $project_name = $name_project_and_client[0];
                $email = $name_project_and_client[2];
                $password="firstpass";
                $user_client = User::create([
                    'name' => $name_project_and_client[1],
                    'email' => $email,
                    'password' => bcrypt($password),
                    'is_active' => false,
                    'role_id' => 4,
                ]);
            }else {
                $project_name = $request->title;
            }
            if ($user_client)
                $ids[] = $user_client->id;



            $project = Project::create([
                'name' => $project_name,
            ]);

            foreach ($ids as $id) {
                $user = User::findOrFail($id);
                $user->projects()->attach($project->id);
                
            }
           

            

            DB::commit();
            if ($password)
                return redirect()->back()->with('alert-success',"Agregado con exito, la contraseña para el usuario ".$email." es ".$password);
            else
                return redirect()->back()->with('alert-success',"Agregado con exito");

  
        } catch (\Exception $e) {
    
            DB::rollback();
            dd($e);
        }


    }
    function update(Request $request,$id){
        $request->validate([
            'users_ids' => 'required',
            'title' => 'required',
        ]);


        try {
            DB::beginTransaction();
            $project = Project::findOrFail($id);
            $ids = explode(',',$request->users_ids);

            foreach ($ids as $_id) {
                DB::table('project_user')->where('project_id',$id)->where('user_id',$_id)->delete();
                $user = User::findOrFail($_id);
                $user->projects()->attach($project->id);
            }

            DB::commit();
            return redirect()->back()->with('alert-success',"Modificado con éxito");

  
        } catch (\Exception $e) {
    
            DB::rollback();
            dd($e);
        }


    }



    function index(){
        $user = \Auth::user();
        $projects =  Project::select('name','id')->orderby('id','desc')->get();

        return view('projects.index', [  'projects' => $projects]);

    }


    function show($id){
        if (!isSenior() && !isClient())
            abort(401,"No podes ver esta pagina");
        $project = Project::findOrFail($id);
        return view('projects.show',compact('project'));
    }


}
