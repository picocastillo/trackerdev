@extends('layouts.app')

@section('content')
<div class="container">
    @include('includes.errors')
    @include('includes.messages')
    
    <div class="row my-2 ">
        <div class="col-sm-2 col-12">
            <a class="btn new"  href="?state_name=1"> Ver Nuevas</a>
        </div>
        <div class="col-sm-2 col-12">
            <a class="btn assigned"  href="?state_name=2"> Ver Asignadas</a>
        </div>
        <div class="col-sm-2 col-12">
            <a class="btn feedback"  href="?state_name=5"> Ver en Feedback</a>
        </div>
        <div class="col-sm-2 col-12 ">
            <a class="btn testing"  href="?state_name=3"> Ver en Testing</a>
        </div>


        <div class="col-sm-2 col-12">
            <form id="form_filter_by_project" onchange="onChangeFilterByProject()" class="form-inline " action="/home" method="GET">
                {{ csrf_field() }}
                    <select name="project_id" class="form-control ml-1">
                        <option>Filtrar por Proyecto</option>
                        @foreach ($projects as $key => $item)
                            <option {{request()->has('project_id') && request()->project_id == $item->id ? 'selected' : ''}}  value="{{$item->id}}" >{{$item->name}}</option>
                        @endforeach
                    </select>
              </form>
        </div>
        @if (isManager())
            <div  class="col-sm-2 col-12">
                <form class="form-inline " id="form_filter_by_user" onchange="onChangeFilterByUser()" action="/home" method="GET">
                    {{ csrf_field() }}
                        <select name="user_id" class="form-control ml-1">
                            <option>Filtrar por Usuario</option>
                            @foreach ($devs as $key => $item)
                                <option {{request()->has('user_id') && request()->user_id == $item->id ? 'selected' : ''}} value="{{$item->id}}" >{{$item->name}}</option>
                            @endforeach
                        </select>
                </form>
            </div>
        @endif

        
    </div>
    
    <div class="row">

       


        <div class="col-12">
            <table class="table table-responsive">
                <thead>
                  <tr>
                    <th scope="col">Nombre de tarea</th>
                    <th scope="col">E</th>
                    @if (isSenior())
                        <th scope="col">F</th>
                    @endif
                    <th scope="col">Esfuerzo</th>
                    <th scope="col">Estado</th>
                    <th scope="col">progreso</th>
                    <th scope="col">Fecha creación</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($tasks as $task)
                    <tr>
                        <th scope="row">
                            <a href="/tasks/{{$task->id}}" >
                                {{$task->getTitle()}}
                            </a> 
                            <span class="badge badge-success">{{$task->project->name}}</span>

                            @if ($task->isFather())
                                ({{$task->getChildsProgress()}})
                            @endif
                            @if (isSenior())
                                <a href="javascript:if(confirm('¿Realmente quiere eliminar la tarea?')) location.href = '/task/delete/{{$task->id}}'"><i class="fa fa-trash"></i></a>
                            @endif
                        </th>
                        <td>
                            {{$task->estimation}}
                        </td>
                        @if (isSenior())
                            <td>
                                {{$task->billed}}
                            </td>
                        @endif
                        
                        <td>
                            {{$task->getEfforts()}}
                        </td>
                        <td>
                            <span class="badge {{getClassStateColor($task->getLastState())}}"> 
                                {{getNameState($task->getLastState())}} 
                                @if ($task->assignedTo() )
                                    to {{$task->assignedTo()}}
                                @endif
                                   
                            
                            </span> 
                        </td>
                        <td >
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: {{$task->getPercentage()}}%" aria-valuenow="{{round($task->getPercentage(),2)}}" aria-valuemin="0" aria-valuemax="100">{{round($task->getPercentage(),2)}}%</div>
                              </div>
                        </td>
                        <td >
                            {{$task->getDate()}}
                        </td>
                        
                      </tr>
                  @endforeach
                </tbody>
              </table>
        </div>
    </div>




    {{-- $tasks->appends('city',request()->city)->links()  --}}
    {{ $tasks->links() }}


@endsection

@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection

<script>
    function onChangeFilterByProject(){
        document.getElementById('form_filter_by_project').submit()
    }
    function onChangeFilterByUser(){
        document.getElementById('form_filter_by_user').submit()
    }
</script>
