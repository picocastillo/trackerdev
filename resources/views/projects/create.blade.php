@extends('layouts.app')

@section('content')
    @include('includes.errors')
    @include('includes.messages')

    <div class="page-shell">
        @include('includes.page-header', [
            'title' => isset($project) ? 'Editar proyecto' : 'Nuevo proyecto',
            'subtitle' => 'Definí nombre y equipo del proyecto',
            'breadcrumbs' => [
                ['label' => 'Proyectos', 'url' => '/project'],
                ['label' => isset($project) ? 'Editar' : 'Crear', 'url' => null],
            ],
        ])

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <section class="card lg:col-span-5">
                <div class="card-header">Usuarios disponibles</div>
                <div class="card-body">
                    <ul class="space-y-2 text-sm">
                        @foreach ($users as $item)
                            <li class="flex flex-wrap items-center gap-2">
                                <span class="font-medium text-stone-800">{{ $item->email }}</span>
                                <span class="text-stone-500">ID {{ $item->id }}</span>
                                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{ $item->role->seniority }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>

            <section class="card lg:col-span-7">
                <div class="card-header">{{ isset($project) ? 'Editar proyecto' : 'Nuevo proyecto' }}</div>
                <div class="card-body">
                    <form method="POST" action="/project/{{ isset($project) ? $project->id.'/edit' : 'create' }}" class="space-y-4">
                        @if (isset($project))
                            @method('PUT')
                        @endif
                        @csrf
                        <div>
                            <label class="form-label">Nombre del proyecto-nombre cliente-email</label>
                            @if (isset($project))
                                <input type="text" required name="title" value="{{ $project->name }}" class="form-input">
                            @else
                                <input type="text" required name="title" class="form-input">
                            @endif
                        </div>
                        <div>
                            <label class="form-label">Ingrese equipo separado por comas</label>
                            @if (isset($project))
                                @php
                                    $aux = $project->users()->pluck('users.id')->toArray();
                                    $aux = implode(',', $aux);
                                @endphp
                                <input type="text" required name="users_ids" value="{{ $aux }}" class="form-input">
                            @else
                                <input type="text" required name="users_ids" class="form-input">
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary w-full">
                            @if (isset($project))
                                Actualizar
                            @else
                                Crear
                            @endif
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
