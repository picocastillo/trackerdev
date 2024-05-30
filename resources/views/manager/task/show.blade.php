@extends('layouts.app')
@section('content')
<div class="container ubuntu">
    
    @include('includes.errors')
    @include('includes.messages')
    <div class="row">
        <div class="col-12  col-lg-12 my-3">
            
            <div class="">
                <div class="card card-body mt-2  my-2">
                    <div class="row">


                        <div class="col-12">
                            <table class="table table-responsive">
                                <thead>
                                  <tr>
                                    <th scope="col">Titulo</th>
                                    <th scope="col">Proyecto</th>
                                    <th scope="col">Estimación</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Esfuerzo</th>
                                    <th scope="col">Revisiones</th>
                                    <th scope="col">Creada hace</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <th scope="row">#{{$task->getTitle()}}</th>
                                    <td>
                                        {{$task->project->name}}
                                    </td>
                                    <td>
                                        {{$task->estimation}}
                                        @if (isManager())
                                            <span class="badge badge-warning">{{$task->billed}} F</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($task->getLastState()==2)
                                            <h4>
                                                <span class="badge {{getClassStateColor($task->getLastState())}} m-2"> 
                                                Asignada a <b >{{$task->user->name}}</b>
                                                @if ($task->watcher_id)
                                                    <small> ({{$task->watcher->name}})</small>
                                                @endif
                                                </span>
                                                
                                            <h4>
                                        @else
                                        <span class="badge {{getClassStateColor($task->getLastState())}} m-2 h5"> 
                                             {{getNameState($task->getLastState())}}
                                             @if ($task->isToTest())
                                                 ({{$task->user->name}})
                                             @endif
                                             
                                            </span>
                                        @endif  
                                    </td>
                                    <td>
                                        {{$task->getEfforts()}}
                                       <div class="badge badge-{{$task->estimation - $task->getEfforts()>0 ? 'success' : 'danger'}}">
                                         ( {{$task->estimation - $task->getEfforts()>0 ? '+':''}} {{$task->estimation - $task->getEfforts()}}  )   
                                        </div> 
                                    </td>
                                    <td>
                                        {{$task->review}}
                                    </td>
                                    <td>
                                        {{$task->getDaysCreatedAt()}} {{$task->getDaysCreatedAt()==0 ? 'dia' : 'dias'}}
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
    
    
    
                            
                            </div>
                        </div>

                        @if (isManager())
                            <div class="col-12">
                                <form action="/tasks/assign-to" method="POST">
                                    @csrf
                                    <input class="d-none" name="task_id" value="{{ $task->id }}" />
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <select name="assign_to" class="form-control">
                                                    @foreach ($devs as $d)
                                                        <option value={{$d->id}} > {{$d->name}} </option>
                                                    @endforeach
                                                </select>
                                                </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="row">
                                                <button type="submit" class="btn assigned btn-sm mt-1 text-center ">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                                &nbsp;
                                                <a href="/task/{{$task->id}}/edit" class="btn btn-outline-primary btn-sm mt-1">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                &nbsp;
                                                <a href="/task/{{$task->id}}/create-a-child" class="btn btn-outline-primary btn-sm mt-1">
                                                    <i class="fa fa-plus"></i>
                                                    <i class="fa fa-child"></i>
                                                </a>
                                                &nbsp;
                                                <button type="button" class="btn btn-outline-primary btn-sm mt-1" data-toggle="modal" data-target="#add_time">
                                                    <i class="fa fa-clock"></i>
                                                </button>
                                            </div>
                                        </div>
                                       
                                    </div>
                                </form>   
                            </div>
                                <form class="row my-1" action="/tasks/add-watcher" method="POST" >
                                    @csrf
                                    <input class="d-none" name="task_id" value="{{ $task->id }}" />
                                    <div class="col-4">
                                        <div class="ml-3">
                                            <select name="user_id" class="form-control">
                                                @foreach ($devs as $d)
                                                    <option value={{$d->id}} > {{$d->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button type="submit" class="btn assigned mt-1 btn-sm text-center ">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </form>
                            

                        @endif
                    
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-12 ">
                                        <p><b>Detalle: </b></p>
                                        {!! nl2br(str_replace(' ','&nbsp;',$task->description)) !!}
                                        @if ($task->task_id)
                                            </br>
                                            <div class="text-right">
                                                <b>Hija de <a href="/tasks/{{$task->task->id}}">#{{$task->task->getTitle()}}</a> </b>
                                            </div>
                                        @endif
                                        @if (count($childs))
                                            <h5>Tareas hijas</h5>
                                            <ol>
                                                @foreach ($childs as $item)
                                                    <li> 
                                                        <a target="_BLANK"  href="{{$item->id}}">{{$item->getTitle()}}</a>

                                                        <span class="badge {{getClassStateColor($item->getLastState())}}"> {{getNameState($item->getLastState())}} </span> 
                                                    </li>
                                                @endforeach
                                            </ol>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-body mt-2  my-2">
                            <div class="card">
                                <div class="row m-3">
                                    <div class="col-sm-6 col-12">
                                        <form action="/tasks/attach-file" method="POST"  enctype="multipart/form-data">
                                            @csrf
                                                <div class="col-12">
                                                    <input class="d-none" name="task_id" value="{{ $task->id }}" />
                                                    <input class="d-none" name="user_id" value="{{ \Auth::user()->id }}" />
                                                    <div class="form-group ">
                                                    <input required type="file" name="file" class="form-control-file" >
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit " class="btn btn-primary col-12">Subir</button>
                                                </div>
                                        </form>
    
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <div class="col-12">Estados</div>
                                        <div class="col-12">
                                            @foreach ($task->states as $key => $item)
                                                <span class="badge {{getClassStateColor($item->name)}} m-2">
                                                    ({{$item->user->name}})
                                                    {{getNameState($item->name)}} 
                                                    @if (isset($task->user_id))
                                                    (  <b >{{$task->user->name}}</b>) 
                                                    @endif
                                                </span>
                                                @if (count($task->states)>$key+1)
                                                    ->
                                                @endif
                                            @endforeach
                                        </div>
    
                                    </div>
                                </div>
                                <div class=" p-1 mx-2">
                                    @foreach ($task->files as $file)
                                        <li> <a href="/{{$file->path}}"> {{$file->real_name}} </a> , fue subido el {{$file->getDate()}} por {{$file->user->name}}</li></br>
                                    @endforeach
                                    @if  (!count($task->files))
                                        <p class="col-8 offset-4"> Aun no hay archivos adjuntos </p>
                                    @endif
                                </div>
    
                            </div>
                        </div>

                    <div class="card mt-2  pt-3 pl-2">
                        <div class="h5">
                            <i>Hitos a completar:</i>
                        </div>
                        <ol>
                            @foreach ($task->items as $item)
                                @if ($item->completed)
                                    <li><s> {{$item->name}}  </s></li>
                                @else
                                <div class="row my-1">
                                    
                                        <form method="POST" action="/tasks/complete-item" >
                                        <li> {{$item->name}}
                                        &nbsp;&nbsp;&nbsp;
                                            @csrf
                                                <input type="hidden" name="task_id" value={{$task->id}}>
                                                <input type="hidden" name="item_id" value={{$item->id}}>
                                                @if (canChargeTime($task->id))
                                                    <button type="submit" class="btn btn-success btn-sm" >✔</button>
                                                @endif    
                                        </li>
                                        </form>
                                    
                                </div>
                                @endif    
                            @endforeach
                            @if (!count($task->items))
                                <p class="pt-4 text-center">No hay Hitos aun en esta tarea</p>
                            @endif
                        </ol>
                        <b>RECORDA:</b>
                        <li><small>Ir marcando a medida que se van completando los hitos en caso de que existan.</small></li>
                        <li><small>revisar el <a target="_BLANK" href="/wiki#checking">Checking de Testing </a>antes de pasar a Testing</small>.</li>
                        <li><small>Dejar almenos una nota del branch donde se trabajo y comandos a correr para probar la tarea, explicar brevemente lo que se debe probar.</small></li>
                        <li><small>"Pasar a testing" cuando completes la tarea.</small></li>
                        <div class="row">
                            <div class="col-sm-10  px-sm-5 py-sm-3">
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{$task->getPercentage() }}%" aria-valuenow="{{$task->getPercentage() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                @if (!isClient() && $task->isComplete() && $task->getLastState()!=3 && $task->getLastState()!=4 )
                                    <div class="col m-2">
                                        <form method="POST" action="/tasks/change-to-testing" >
                                            @csrf
                                            <input type="hidden" name="task_id" value={{$task->id}}>
                                            <button type="submit" class="btn testing btn-sm" >
                                                Pasar a testing
                                                <i class="fa fa-check" ></i>

                                            </button>
                                        </form>    
                                    </div>
                                @endif
                                @if (!isClient() && ($task->getLastState()==3) && (isManager()))
                                    <div class="col m-2">
                                        <form method="POST" action="/tasks/change-to-finished" >
                                            @csrf
                                            <input type="hidden" name="task_id" value={{$task->id}}>
                                            <button type="submit" class="btn finished btn-sm text-white col-12" >
                                                Finalizar
                                                <i class="fa fa-check" ></i>
                                            </button>
                                        </form>    
                                    </div>
                                @endif
                                <div class="col m-2">
                                    @if ($task->getLastState()!=5)
                                        <form method="POST" action="/tasks/change-to-feedback" >
                                            @csrf
                                            <input type="hidden" name="task_id" value={{$task->id}}>
                                            <button type="submit" class="btn feedback btn-sm text-white" >
                                                Necesita Feedback
                                            </button>
                                        </form>   
                                    @endif 
                                </div>
                                @if (isInTeam($task->id) || isManager() )
                                <div class="col m-2">
                                    <button type="button" class="btn btn-primary btn-sm col-12" data-toggle="modal" data-target="#exampleModal">
                                        Agregar Hito
                                      </button>
                                </div>
                                @endif
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
                @if (canShowTimes())
                    <div class="tab-pane mb-2"  >
                        <div class="card card-body mt-2 ">
                            <div class="max_height_table">
                                <table class="table table-responsive">
                                    <thead class="">
                                    <tr>
                                        <th scope="col">Horas</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Fecha</th>
                                    </tr>
                                    </thead>
                                        <tbody class="">
                                            @foreach ($task->efforts as $effort)
                                                <tr>
                                                    <th scope="row">{{$effort->amount}} ({{$effort->user->name}}) </th>
                                                    <td>{{$effort->detail}}</td>
                                                    <td>{{$effort->getDate()}} </td>
                                                </tr>
                                            @endforeach
                                            @if (!count($task->efforts))
                                                <tr>
                                                    <th ></th>
                                                    <td>Aun no hay tiempos</td>
                                                    <td></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                </table>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-success"> {{$task->totalHours()}} Horas </span>
                            </div>
                            @if (canChargeTime($task->id) && $task->getLastState()!=4)
                                <div class="card mt-2 ">
                                    <form method="POST" action="/tasks/add-effort">
                                        @csrf
                                        <input type="number" name="task_id" class="d-none" value="{{$task->id}}">
                                        <input type="number" name="user_id" class="d-none" value="{{\Auth::user()->id}}">

                                        <div class="row m-2 pt-3">
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <input required type="number" min="0" step="0.1" name="time" class="form-control"  placeholder="tiempo">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <input required type="text" name="description" class="form-control"  placeholder="descripción">
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <button type="submit" class="btn btn-success col-12 ">+</button>
                                            </div>
                                        </div>
                                    </form>    
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                <div >
                    
                    
                </div>
                    

                
                    

           

        </div>

        </div>
        <div class="col-sm-12 col-lg-12">
            <div class="card ">
                <div class="card-header row ">
                    <div class="col-sm-11 col-10 h3">
                        Notas
                    </div>
                    <div class="col-sm-1 col-2">
                       <h3>  <span class="badge badge-warning">{{ count($task->notes)}} </span></h3>
                    </div>
                </div>
                <div class="card-body ">

                    <div class="mt-3">
                        <form class="row" method="POST" action="/tasks/add-message">
                           @csrf
                           <input type="hidden" name="task_id" value={{$task->id}}>
                           <input type="hidden" name="user_id" value={{\Auth::user()->id}}>
                           <div class="col-sm-2 col-2 d-sm-block d-none">
                               <img  height="110" class="rounded-circle img-responsive" src={{\Auth::user()->image ? url('images/'.\Auth::user()->image) : url('uploads/user.jpg')}}></img>
                           </div>
                           <div class="col-sm-8 col-8">
                               <div class="form-group">
                                   <textarea  required name="message" rows="5" type="text" class="form-control " placeholder="Mensage"></textarea>
                               </div>
                           </div>
                           <div class="col-sm-2 col-2">
                                <button type="submit" class="btn btn-success col-sm-12 col-12 mt-4"><h2>+</h2></button>
                           </div>
                       </form>    
                   </div>

                    {{-- <div id="message_task" ></div> --}}
                    <div class="messages_task">
                        @foreach ($task->getNotes() as $message)
                            <li class="list-group-item  my-2 ">
                                <div class="row ">
                                    <div class="col-sm-2 col-2 text-center d-sm-block d-none">
                                        <img height="80" src={{$message->user->image ? url('images/'.$message->user->image) : url('uploads/user.jpg')}} class="rounded-circle img-responsive" alt="" />
                                    </div>
                                    <div class="col-sm-10 col-10">
                                        <div class="p-2 card card-header" >
                                            {!! nl2br(str_replace(' ','&nbsp;',$message->message)) !!}
                                        </div>
                                    </div>
                                    <footer class="blockquote-footer pl-2 pt-2">Escrito por<cite title="Source Title">&nbsp; {{$message->getUser()}} &nbsp;&nbsp;&nbsp;&nbsp; {{$message->getDate()}} </cite></footer>
                                </div>
                            </li>
                        @endforeach
    
                     
                        

                    </div>


                    

                </div>
            </div>
        </div>

    </div>

    <!-- Modal Add Item -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Agregar nuevo Hito a la tarea</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form class="row" method="POST" action="/tasks/add-item">
                    @csrf
                    <input type="hidden" name="task_id" value={{$task->id}}>
                    
                    <div class="col-sm-12 col-12">
                        <div class="form-group">
                            <input type="text" name="text" placeholder="Breve descripción del hito" class="form-control"/>
                        </div>
                    </div>
                    
                    <div class="col-sm-12 col-12">
                         <button type="submit" class="btn btn-primary col-sm-12 col-12 mt-4"><h2>+</h2></button>
                    </div>
                </form>  
            </div>
          </div>
        </div>
      </div>

      <!-- Add time -->
    <div class="modal fade" id="add_time" tabindex="-1" role="dialog" aria-labelledby="add_time" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" >Agregar tiempo a esta tarea</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form class="row" method="POST" action="/tasks/add-time">
                    @csrf
                    <input type="hidden" name="task_id" value={{$task->id}}>
                    
                    <div class="col-sm-12 col-12">
                        <div class="form-group">
                            <input type="number" name="time" placeholder="Agregar tiempo en horas" class="form-control"/>
                        </div>
                    </div>
                    
                    <div class="col-sm-12 col-12">
                         <button type="submit" class="btn btn-primary col-sm-12 col-12 mt-4"><h2>+</h2></button>
                    </div>
                </form>  
            </div>
            
        </div>
        </div>
    </div>
    
    


@endsection
@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection
