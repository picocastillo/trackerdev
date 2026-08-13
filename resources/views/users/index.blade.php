@extends('layouts.app')

@section('content')
    @include('includes.errors')
    @include('includes.messages')

    <div class="page-shell">
        @include('includes.page-header', [
            'title' => 'Usuarios',
            'subtitle' => 'Alta, edición y desactivación de cuentas',
            'breadcrumbs' => [
                ['label' => 'Inicio', 'url' => '/home'],
                ['label' => 'Usuarios', 'url' => null],
            ],
            'actions' => '<a class="btn btn-primary btn-sm" href="/users/create"><i class="fa fa-plus mr-1.5"></i> Nuevo usuario</a>',
        ])

        <section class="card">
            <div class="card-body overflow-x-auto p-0">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Email</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $item)
                            <tr class="{{ $item->is_active ? '' : 'opacity-60' }}">
                                <th scope="row">{{ $item->name }}</th>
                                <td>{{ $item->email }}</td>
                                <td>
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                                        {{ $item->role->seniority }}
                                    </span>
                                </td>
                                <td>
                                    @if ($item->is_active)
                                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Activo</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-stone-200 px-2.5 py-0.5 text-xs font-semibold text-stone-700">Desactivado</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <a href="/users/{{ $item->id }}/edit" class="text-sm font-medium">Editar</a>
                                        @if ($item->id === Auth::id())
                                            <span class="text-sm text-stone-400">Tu usuario</span>
                                        @elseif ($item->is_active)
                                            <form method="POST" action="/users/{{ $item->id }}/deactivate" onsubmit="return confirm(@json('¿Desactivar a '.$item->name.'? No podrá iniciar sesión.'));">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-sm font-medium text-red-700 hover:text-red-800">Desactivar</button>
                                            </form>
                                        @else
                                            <form method="POST" action="/users/{{ $item->id }}/activate" onsubmit="return confirm(@json('¿Reactivar a '.$item->name.'?'));">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-sm font-medium text-emerald-700 hover:text-emerald-800">Reactivar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-stone-500">No hay usuarios todavía.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
