@extends('layouts.app')
@section('content')
    @include('includes.errors')
    @include('includes.messages')

    <div class="my-3 space-y-4">
        <div class="card">
            <div class="card-body">
                <div class="overflow-x-auto">
                    <table class="table-app">
                        <thead>
                            <tr>
                                <th scope="col">Titulo</th>
                                <th scope="col">Proyecto</th>
                                <th scope="col">Estimación</th>
                                <th scope="col">Estado</th>
                                @if (!isClient())
                                    <th scope="col">Esfuerzo</th>
                                @endif
                                <th scope="col">Revisiones</th>
                                <th scope="col">Creada hace</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">#{{$task->getTitle()}}</th>
                                <td>{{$task->project->name}}</td>
                                <td>
                                    {{$task->estimation}}
                                    @if (isManager())
                                        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">{{$task->billed}} F</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($task->getLastState()==2)
                                        <span class="badge-state assigned m-2">
                                            Asignada a <b>{{$task->user->name}}</b>
                                            @if ($task->watcher_id)
                                                <small>({{$task->watcher->name}})</small>
                                            @endif
                                        </span>
                                    @else
                                        <span class="badge-state {{getClassStateColor($task->getLastState())}} m-2">
                                            {{getNameState($task->getLastState())}}
                                            @if ($task->isToTest())
                                                ({{$task->user->name}})
                                            @endif
                                        </span>
                                    @endif
                                </td>
                                @if (!isClient())
                                    <td>
                                        {{$task->totalHours()}}
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{$task->estimation - $task->totalHours()>0 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'}}">
                                            ( {{$task->estimation - $task->totalHours()>0 ? '+':''}} {{$task->estimation - $task->totalHours()}} )
                                        </span>
                                    </td>
                                @endif
                                <td>{{$task->review}}</td>
                                <td>{{$task->getDaysCreatedAt()}} {{$task->getDaysCreatedAt()==0 ? 'dia' : 'dias'}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if (isManager())
                    <form action="/tasks/assign-to" method="POST" class="mt-4">
                        @csrf
                        <input class="hidden" name="task_id" value="{{ $task->id }}">
                        <div class="flex flex-wrap items-center gap-2">
                            <select name="assign_to" class="form-input w-auto">
                                @foreach ($devs as $d)
                                    <option value={{$d->id}}>{{$d->name}}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm badge-state assigned">
                                <i class="fa fa-plus"></i>
                            </button>
                            <a href="/task/{{$task->id}}/edit" class="btn btn-outline btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="/task/{{$task->id}}/create-a-child" class="btn btn-outline btn-sm">
                                <i class="fa fa-plus"></i>
                                <i class="fa fa-child"></i>
                            </a>
                            <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('add_time').showModal()">
                                <i class="fa fa-clock"></i>
                            </button>
                        </div>
                    </form>
                    <form class="my-2 flex flex-wrap items-center gap-2" action="/tasks/add-watcher" method="POST">
                        @csrf
                        <input class="hidden" name="task_id" value="{{ $task->id }}">
                        <select name="user_id" class="form-input w-auto">
                            @foreach ($devs as $d)
                                <option value={{$d->id}}>{{$d->name}}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm badge-state assigned">
                            <i class="fa fa-eye"></i>
                        </button>
                    </form>
                @endif

                <div class="card mt-4">
                    <div class="card-header">
                        <p><b>Detalle: </b></p>
                        {!! nl2br(str_replace(' ','&nbsp;',$task->description)) !!}
                        @if ($task->task_id)
                            <br>
                            <div class="text-right">
                                <b>Hija de <a href="/tasks/{{$task->task->id}}">#{{$task->task->getTitle()}}</a></b>
                            </div>
                        @endif
                        @if (count($childs))
                            <h5 class="mt-3 font-semibold">Tareas hijas</h5>
                            <ol class="list-decimal pl-5">
                                @foreach ($childs as $item)
                                    <li>
                                        <a target="_BLANK" href="{{$item->id}}">{{$item->getTitle()}}</a>
                                        <span class="badge-state {{getClassStateColor($item->getLastState())}}">{{getNameState($item->getLastState())}}</span>
                                    </li>
                                @endforeach
                            </ol>
                        @endif
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <form action="/tasks/attach-file" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <input class="hidden" name="task_id" value="{{ $task->id }}">
                                <input class="hidden" name="user_id" value="{{ \Auth::user()->id }}">
                                <input required type="file" name="file" class="form-input">
                                <button type="submit" class="btn btn-primary w-full">Subir</button>
                            </form>
                            <div>
                                <div class="mb-2 font-semibold">Estados</div>
                                <div class="flex flex-wrap items-center gap-1">
                                    @foreach ($task->states as $key => $item)
                                        <span class="badge-state {{getClassStateColor($item->name)}} m-1">
                                            ({{$item->user->name}})
                                            {{getNameState($item->name)}}
                                            @if (isset($task->user_id))
                                                (<b>{{$task->user->name}}</b>)
                                            @endif
                                        </span>
                                        @if (count($task->states)>$key+1)
                                            ->
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="mx-2 mt-4 p-1">
                            @foreach ($task->files as $file)
                                <li><a href="/{{$file->path}}">{{$file->real_name}}</a>, fue subido el {{$file->getDate()}} por {{$file->user->name}}</li><br>
                            @endforeach
                            @if (!count($task->files))
                                <p class="text-center">Aun no hay archivos adjuntos</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mt-4 pt-3 pl-2">
                    <div class="mb-3 font-semibold"><i>Hitos a completar:</i></div>
                    <ol class="list-decimal space-y-2 pl-5">
                        @foreach ($task->items as $item)
                            @if ($item->completed)
                                <li><s>{{$item->name}}</s></li>
                            @else
                                <form method="POST" action="/tasks/complete-item" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="task_id" value={{$task->id}}>
                                    <input type="hidden" name="item_id" value={{$item->id}}>
                                    <li class="flex items-center gap-2">
                                        {{$item->name}}
                                        @if (canChargeTime($task->id))
                                            <button type="submit" class="btn btn-sm bg-emerald-600 text-white hover:bg-emerald-700">✔</button>
                                        @endif
                                    </li>
                                </form>
                            @endif
                        @endforeach
                        @if (!count($task->items))
                            <p class="pt-4 text-center">No hay Hitos aun en esta tarea</p>
                        @endif
                    </ol>
                    @if (!isClient())
                        <b>RECORDA:</b>
                        <ul class="list-disc pl-5 text-sm">
                            <li><small>Ir marcando a medida que se van completando los hitos en caso de que existan.</small></li>
                            <li><small>revisar el <a target="_BLANK" href="/wiki#checking">Checking de Testing </a>antes de pasar a Testing</small>.</li>
                            <li><small>Dejar almenos una nota del branch donde se trabajo y comandos a correr para probar la tarea, explicar brevemente lo que se debe probar.</small></li>
                            <li><small>"Pasar a testing" cuando completes la tarea.</small></li>
                        </ul>
                    @endif
                    <div class="mt-4 flex flex-wrap items-center gap-4">
                        <div class="h-4 flex-1 overflow-hidden rounded-full bg-stone-200">
                            <div class="h-full rounded-full bg-emerald-500" style="width: {{$task->getPercentage() }}%"></div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if (!isClient() && $task->isComplete() && $task->getLastState()!=3 && $task->getLastState()!=4)
                                <form method="POST" action="/tasks/change-to-testing">
                                    @csrf
                                    <input type="hidden" name="task_id" value={{$task->id}}>
                                    <button type="submit" class="btn btn-sm badge-state testing">
                                        Pasar a testing <i class="fa fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            @if (!isClient() && ($task->getLastState()==3) && (isManager()))
                                <form method="POST" action="/tasks/change-to-finished">
                                    @csrf
                                    <input type="hidden" name="task_id" value={{$task->id}}>
                                    <button type="submit" class="btn btn-sm badge-state finished">
                                        Finalizar <i class="fa fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            @if (!isClient() && $task->getLastState()!=5)
                                <form method="POST" action="/tasks/change-to-feedback">
                                    @csrf
                                    <input type="hidden" name="task_id" value={{$task->id}}>
                                    <button type="submit" class="btn btn-sm badge-state feedback">
                                        Necesita Feedback
                                    </button>
                                </form>
                            @endif
                            @if (!isClient() && isInTeam($task->id) || isManager())
                                <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('exampleModal').showModal()">
                                    Agregar Hito
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (canShowTimes())
            <div class="card">
                <div class="card-body">
                    <div class="max_height_table overflow-x-auto">
                        <table class="table-app">
                            <thead>
                                <tr>
                                    <th scope="col">Horas</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($task->efforts as $effort)
                                    <tr>
                                        <th scope="row">{{$effort->getHours()}} ({{$effort->user->name}})</th>
                                        <td>{{$effort->detail}}</td>
                                        <td>{{$effort->getDate()}}</td>
                                    </tr>
                                @endforeach
                                @if (!count($task->efforts))
                                    <tr>
                                        <th></th>
                                        <td>Aun no hay tiempos</td>
                                        <td></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{$task->totalHours()}} Horas</span>
                    </div>
                    @if (canChargeTime($task->id) && $task->getLastState()!=4)
                        <div class="card mt-4">
                            <form method="POST" action="/tasks/add-effort" class="flex flex-wrap items-end gap-2 p-3">
                                @csrf
                                <input type="number" name="task_id" class="hidden" value="{{$task->id}}">
                                <input type="number" name="user_id" class="hidden" value="{{\Auth::user()->id}}">
                                <input required type="number" min="0" step="1" name="time" class="form-input w-24" placeholder="Minutos">
                                <input required type="text" name="description" class="form-input flex-1" placeholder="descripción">
                                <button type="submit" class="btn bg-emerald-600 text-white hover:bg-emerald-700">+</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header flex items-center justify-between">
                <div class="text-xl font-semibold">Notas</div>
                <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-sm font-semibold text-amber-800">{{ count($task->notes)}}</span>
            </div>
            <div class="card-body">
                <form class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-12" method="POST" action="/tasks/add-message">
                    @csrf
                    <input type="hidden" name="task_id" value={{$task->id}}>
                    <input type="hidden" name="user_id" value={{\Auth::user()->id}}>
                    <div class="hidden sm:col-span-2 sm:block">
                        <img height="110" class="user_image" src={{\Auth::user()->image ? url('images/'.\Auth::user()->image) : url('uploads/user.jpg')}} alt="">
                    </div>
                    <div class="sm:col-span-8">
                        <textarea required name="message" rows="5" class="form-input" placeholder="Mensage"></textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="flex items-center gap-2 text-sm">
                            <input class="rounded border-stone-300" id="private" name="is_private" type="checkbox" checked>
                            Nota privada
                        </label>
                        <button type="submit" class="btn bg-emerald-600 text-white hover:bg-emerald-700 mt-2 w-full"><h2>+</h2></button>
                    </div>
                </form>

                <div class="messages_task space-y-4">
                    @foreach ($task->getNotes() as $message)
                        @if (isClient() && !$message->is_private)
                            <div class="rounded-lg border border-stone-200 p-3">
                                <div class="flex gap-3">
                                    <div class="hidden shrink-0 text-center sm:block">
                                        <img height="80" src={{$message->user->image ? url('images/'.$message->user->image) : url('uploads/user.jpg')}} class="user_image" alt="">
                                    </div>
                                    <div class="flex-1">
                                        <div class="card-header rounded-md">{!! nl2br(str_replace(' ','&nbsp;',$message->message)) !!}</div>
                                        <footer class="mt-2 text-sm text-stone-500">
                                            Escrito por <cite>{{$message->getUser()}}</cite> {{$message->getDate()}}
                                        </footer>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (!isClient())
                            <div class="rounded-lg border border-stone-200 p-3">
                                <div class="flex gap-3">
                                    <div class="hidden shrink-0 text-center sm:block">
                                        <img height="80" src={{$message->user->image ? url('images/'.$message->user->image) : url('uploads/user.jpg')}} class="user_image" alt="">
                                    </div>
                                    <div class="flex-1">
                                        <div class="card-header rounded-md">{!! nl2br(str_replace(' ','&nbsp;',$message->message)) !!}</div>
                                        <footer class="mt-2 flex items-center justify-between text-sm text-stone-500">
                                            <span>Escrito por <cite>{{$message->getUser()}}</cite> {{$message->getDate()}}</span>
                                            @if ($message->is_private)
                                                <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Es privada</span>
                                            @else
                                                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Es Publica</span>
                                            @endif
                                        </footer>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <dialog id="exampleModal" class="w-full max-w-lg rounded-lg border border-stone-200 bg-white p-0 shadow-xl backdrop:bg-black/50">
        <div class="flex items-center justify-between border-b border-stone-200 px-4 py-3">
            <h5 class="font-semibold">Agregar nuevo Hito a la tarea</h5>
            <button type="button" class="text-2xl leading-none text-stone-500 hover:text-stone-800" onclick="document.getElementById('exampleModal').close()" aria-label="Close">&times;</button>
        </div>
        <form method="POST" action="/tasks/add-item" class="card-body space-y-4">
            @csrf
            <input type="hidden" name="task_id" value={{$task->id}}>
            <input type="text" name="text" placeholder="Breve descripción del hito" class="form-input">
            <button type="submit" class="btn btn-primary w-full"><h2>+</h2></button>
        </form>
    </dialog>

    <dialog id="add_time" class="w-full max-w-lg rounded-lg border border-stone-200 bg-white p-0 shadow-xl backdrop:bg-black/50">
        <div class="flex items-center justify-between border-b border-stone-200 px-4 py-3">
            <h5 class="font-semibold">Agregar tiempo a esta tarea</h5>
            <button type="button" class="text-2xl leading-none text-stone-500 hover:text-stone-800" onclick="document.getElementById('add_time').close()" aria-label="Close">&times;</button>
        </div>
        <form method="POST" action="/tasks/add-time" class="card-body space-y-4">
            @csrf
            <input type="hidden" name="task_id" value={{$task->id}}>
            <input type="number" name="time" placeholder="Agregar tiempo en horas" class="form-input">
            <button type="submit" class="btn btn-primary w-full"><h2>+</h2></button>
        </form>
    </dialog>
@endsection
