<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function create()
    {
        $users = $this->formUsers();

        return view('projects.create', compact('users'));
    }

    public function edit($id)
    {
        $project = Project::with('users')->findOrFail($id);
        $users = $this->formUsers($project);

        return view('projects.create', compact('users', 'project'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'users_ids' => 'required|array|min:1',
            'users_ids.*' => 'integer|exists:users,id',
            'create_client' => 'nullable|boolean',
            'client_name' => 'required_if:create_client,1|nullable|string|max:255',
            'client_email' => 'required_if:create_client,1|nullable|email|max:255|unique:users,email',
        ]);

        try {
            DB::beginTransaction();

            $ids = collect($request->input('users_ids', []))->map(fn ($id) => (int) $id)->unique()->values();
            $password = null;
            $email = null;

            if ($request->boolean('create_client')) {
                $password = Str::password(12);
                $email = $request->client_email;
                $clientRole = Role::where('seniority', 'stackeholder')->firstOrFail();

                $userClient = User::create([
                    'name' => $request->client_name,
                    'email' => $email,
                    'password' => $password,
                    'is_active' => false,
                    'role_id' => $clientRole->id,
                ]);

                $ids->push($userClient->id);
            }

            $project = Project::create([
                'name' => $request->title,
            ]);

            $project->users()->sync($ids->all());

            DB::commit();

            if ($password) {
                return redirect('/project')->with(
                    'alert-success',
                    "Proyecto creado con éxito. La contraseña para {$email} es {$password}"
                );
            }

            return redirect('/project')->with('alert-success', 'Proyecto creado con éxito');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('alert-danger', $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'users_ids' => 'required|array|min:1',
            'users_ids.*' => 'integer|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            $project = Project::findOrFail($id);
            $project->update([
                'name' => $request->title,
            ]);

            $ids = collect($request->input('users_ids', []))->map(fn ($id) => (int) $id)->unique()->values();
            $project->users()->sync($ids->all());

            DB::commit();

            return redirect('/project')->with('alert-success', 'Proyecto modificado con éxito');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('alert-danger', $e->getMessage())->withInput();
        }
    }

    public function index()
    {
        $projects = Project::select('name', 'id')->orderBy('id', 'desc')->get();

        return view('projects.index', ['projects' => $projects]);
    }

    public function show($id)
    {
        if (! isSenior() && ! isClient()) {
            abort(401, 'No podes ver esta pagina');
        }

        $project = Project::findOrFail($id);

        return view('projects.show', compact('project'));
    }

    private function formUsers(?Project $project = null)
    {
        $assignedIds = $project ? $project->users->pluck('id') : collect();

        return User::with('role')
            ->where(function ($query) use ($assignedIds) {
                $query->where('is_active', true);

                if ($assignedIds->isNotEmpty()) {
                    $query->orWhereIn('id', $assignedIds);
                }
            })
            ->orderBy('name')
            ->get();
    }
}
