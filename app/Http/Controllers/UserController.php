<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderBy('name')->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('id')->get();

        return view('users.create', compact('roles'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('id')->get();

        return view('users.create', compact('user', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'by_hours' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role_id' => $request->role_id,
                'by_hours' => $request->boolean('by_hours'),
                'is_active' => $request->boolean('is_active'),
            ]);

            DB::commit();

            return redirect('/users')->with('alert-success', 'Usuario creado con éxito');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('alert-danger', $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'by_hours' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        if ($this->isCurrentUser($user) && ! $request->boolean('is_active')) {
            return redirect()->back()
                ->with('alert-danger', 'No podés desactivar tu propio usuario.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'by_hours' => $request->boolean('by_hours'),
                'is_active' => $request->boolean('is_active'),
            ];

            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }

            $user->update($data);

            DB::commit();

            return redirect('/users')->with('alert-success', 'Usuario actualizado con éxito');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('alert-danger', $e->getMessage())->withInput();
        }
    }

    public function deactivate($id)
    {
        $user = User::findOrFail($id);

        if ($this->isCurrentUser($user)) {
            return redirect()->back()->with('alert-danger', 'No podés desactivar tu propio usuario.');
        }

        try {
            DB::beginTransaction();

            $user->update(['is_active' => false]);

            DB::commit();

            return redirect('/users')->with('alert-success', 'Usuario desactivado con éxito');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);

        try {
            DB::beginTransaction();

            $user->update(['is_active' => true]);

            DB::commit();

            return redirect('/users')->with('alert-success', 'Usuario reactivado con éxito');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    private function isCurrentUser(User $user): bool
    {
        return (int) $user->id === (int) Auth::id();
    }
}
