@extends('layouts.app')

@section('content')
@include('includes.errors')
@include('includes.messages')
<div class="container">
    <form method="POST" action="{{$isEdit ? '/task/update' : '/task/create'}} ">
        @csrf
        @if ($isEdit)
            <input  type="hidden" value="{{$task->id}}"  name="task_id" required >
        @endif
        @if (isset($task_id))
            <input  type="hidden" value="{{$task_id}}"  name="task_id" >
        @endif
    <div class="row">
        <div class="col-sm-6 col-12">
            <div class="card">
                <div class="card-header ">

                    Detalle de tarea
                </div>

                <div class="card-body">
                  <div class="form-group row">
                   {{--  <div class="col-6">
                        <p>Tarea privada</p>
                    </div>
                    <div class="col-6">
                        <input class="form-check-input" name="is_private" {{$isEdit  ? $task->is_private :''}} type="checkbox" value="0" >
                    </div> --}}
                </div>  
                <div class="form-group row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dev">Desarrollador</label>
                            <select name="user_id" class="form-control" id="dev">
                                <option selected value={{0}} > Sin Definir </option>
                                @foreach ($devs as $d)
                                    <option value={{$d->id}} > {{$d->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="dev">Estimación</label>
                        @if ($isEdit)
                            <input id="estimation" type="number" value="{{$task->estimation}}" class="form-control " name="estimation" >
                        @else
                            <input id="estimation" type="number" class="form-control " name="estimation"  >
                        @endif
                        
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="proj">Projecto</label>
                            <select name="project_id" class="form-control" id="proj">
                                @if ($isEdit)
                                    <option value={{$task->project->id}} > {{$task->project->name}} </option>
                                @else
                                    @foreach ($projects as $p)
                                        <option value={{$p->id}} > {{$p->name}} </option>
                                    @endforeach
                                @endif
                            </select>
                          </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-10">
                        <label>Titulo </label>
                            @if ($isEdit)
                                <input  type="text" value=" {{$task->name}} " class="form-control" name="name" required >
                            @else
                                <input  type="text" class="form-control" name="name" required >
                            @endif
                    </div>
                    <div class="col-md-2 text-center">
                        <label>F </label>
                            @if ($isEdit)
                                <input  type="text" value="{{$task->billed}} " class="form-control" name="billed" required >
                            @else
                                <input  type="text" class="form-control" name="billed" required >
                            @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label>Información adicional</label>
                        @if ($isEdit)
                            <textarea type="text" class="form-control" name="description"   spellcheck="false" required>{{$task->description}}</textarea>
                        @else
                            <textarea type="text" rows="6" class="form-control" name="description" required> </textarea>
                        @endif

                    </div>
                </div>

                
                <div class="form-group row mb-0">
                    <div class="col-md-3 offset-md-9">
                        <button type="submit" class="btn btn-primary">
                            Terminar
                        </button>
                    </div>
                </div>
                </div>
            </div>

        </div>
        <div class="col-sm-6 col-12">
            <div class="card">
                <div class="card-header">
                    Items
                </div>
                <div class="card-body">
                    <div id="create_task"  items="{{json_encode($items)}}"  ></div>
                </div>
            </div>
        </div>

    </div>

        
    </form>
</div>
    
    


@endsection
@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection
