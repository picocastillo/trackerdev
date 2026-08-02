@extends('layouts.app')

@section('content')
    @include('includes.errors')
    @include('includes.messages')

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <div class="card">
                <div class="card-header">
                    <ul class="space-y-1">
                        @foreach ($users as $item)
                            <li>{{$item->email}} => ID {{$item->id}} <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{$item->role->seniority}}</span></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="lg:col-span-7">
            <div class="card">
                <div class="card-header">Nuevo proyecto</div>
                <div class="card-body">
                    <form method="POST" action="/project/{{isset($project) ? $project->id.'/edit' : 'create'}}" class="space-y-4">
                        @if (isset($project))
                            @method('PUT')
                        @endif
                        @csrf
                        <div>
                            <label class="form-label">Nombre del proyecto-nombre cliente-email</label>
                            @if (isset($project))
                                <input type="text" required name="title" value="{{$project->name}}" class="form-input">
                            @else
                                <input type="text" required name="title" class="form-input">
                            @endif
                        </div>
                        <div>
                            <label class="form-label">Ingrese equipo separado por comas</label>
                            @if (isset($project))
                                @php
                                    $aux = $project->users()->pluck('users.id')->toArray();
                                    $aux = implode(',',$aux);
                                @endphp
                                <input type="text" required name="users_ids" value="{{$aux}}" class="form-input">
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
            </div>
        </div>
    </div>
@endsection
