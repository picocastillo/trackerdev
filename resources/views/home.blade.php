@extends('layouts.app')

@section('content')
    @include('includes.errors')
    @include('includes.messages')

    <div class="page-shell">
        @include('includes.page-header', [
            'title' => 'Tareas',
            'subtitle' => 'Filtrá y seguí el trabajo en curso',
            'breadcrumbs' => [
                ['label' => 'Inicio', 'url' => '/home'],
                ['label' => 'Tareas', 'url' => null],
            ],
            'actions' => isDeveloper()
                ? '<button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById(\'ChargeTime\').showModal()"><i class="fa fa-clock mr-1.5"></i> Cargar Tiempo</button>'
                : null,
        ])

        <section class="card">
            <div class="card-body">
                <div class="page-toolbar">
                    <a class="btn btn-sm badge-state new" href="?state_name=1">Ver Nuevas</a>
                    <a class="btn btn-sm badge-state assigned" href="?state_name=2">Ver Asignadas</a>
                    <a class="btn btn-sm badge-state feedback" href="?state_name=5">Ver en Feedback</a>
                    <a class="btn btn-sm badge-state testing" href="?state_name=3">Ver en Testing</a>

                    <form id="form_filter_by_project" onchange="onChangeFilterByProject()" class="flex items-center" action="/home" method="GET">
                        {{ csrf_field() }}
                        <select name="project_id" class="form-input w-auto">
                            <option>Filtrar por Proyecto</option>
                            @foreach ($projects as $key => $item)
                                <option {{ request()->has('project_id') && request()->project_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </form>

                    @if (isManager())
                        <form class="flex items-center" id="form_filter_by_user" onchange="onChangeFilterByUser()" action="/home" method="GET">
                            {{ csrf_field() }}
                            <select name="user_id" class="form-input w-auto">
                                <option>Filtrar por Usuario</option>
                                @foreach ($devs as $key => $item)
                                    <option {{ request()->has('user_id') && request()->user_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                </div>
            </div>
        </section>

        @if (isDeveloper())
            <dialog id="ChargeTime" class="w-full max-w-lg rounded-lg border border-stone-200 bg-white p-0 shadow-xl backdrop:bg-black/50">
                <form action="/tasks/charge-effort" method="POST">
                    <div class="flex items-center justify-between border-b border-stone-200 px-4 py-3">
                        <h5 class="font-semibold" id="ChargeTimeTitle">Cargar Tiempo Manualmente</h5>
                        <button type="button" class="text-2xl leading-none text-stone-500 hover:text-stone-800" onclick="document.getElementById('ChargeTime').close()" aria-label="Close">&times;</button>
                    </div>

                    <div class="card-body">
                        @csrf
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <input type="hidden" name="user_id" value="{{ \Auth::user()->id }}">
                                <label class="form-label">Detalle</label>
                                <input type="number" class="form-input" min="1" step="1" name="amount" value="" placeholder="Minutos">
                            </div>
                            <div>
                                <input type="hidden" name="user_id" value="{{ \Auth::user()->id }}">
                                <label class="form-label">Proyecto</label>
                                <select name="project_id" class="form-input">
                                    @foreach ($projects as $key => $item)
                                        <option {{ request()->has('project_id') && request()->project_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="form-label" for="effortDetail">Detalle del esfuerzo</label>
                                <textarea name="detail" id="effortDetail" class="form-input" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 border-t border-stone-200 px-4 py-3">
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('ChargeTime').close()">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Cargar</button>
                    </div>
                </form>
            </dialog>
        @endif

        <section class="card">
            <div class="card-body overflow-x-auto p-0">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th scope="col">Nombre de tarea</th>
                            <th scope="col">E</th>
                            @if (isSenior())
                                <th scope="col">F</th>
                            @endif
                            <th scope="col">Esfuerzo</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Progreso</th>
                            <th scope="col">Fecha creación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <th scope="row">
                                    <a href="/tasks/{{ $task->id }}">{{ $task->getTitle() }}</a>
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{ $task->project->name }}</span>
                                    @if ($task->isFather())
                                        ({{ $task->getChildsProgress() }})
                                    @endif
                                    @if (isSenior())
                                        <a href="javascript:if(confirm('¿Realmente quiere eliminar la tarea?')) location.href = '/task/delete/{{ $task->id }}'"><i class="fa fa-trash"></i></a>
                                    @endif
                                </th>
                                <td>{{ $task->estimation }}</td>
                                @if (isSenior())
                                    <td>{{ $task->billed }}</td>
                                @endif
                                <td>{{ $task->totalHours().'h' }}</td>
                                <td>
                                    <span class="badge-state {{ getClassStateColor($task->getLastState()) }}">
                                        {{ getNameState($task->getLastState()) }}
                                        @if ($task->assignedTo())
                                            to {{ $task->assignedTo() }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="h-4 w-full min-w-[6rem] overflow-hidden rounded-full bg-stone-200">
                                        <div class="flex h-full items-center justify-center rounded-full bg-emerald-500 text-xs text-white" style="width: {{ $task->getPercentage() }}%" aria-valuenow="{{ round($task->getPercentage(), 2) }}" aria-valuemin="0" aria-valuemax="100">{{ round($task->getPercentage(), 2) }}%</div>
                                    </div>
                                </td>
                                <td>{{ $task->getDate() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-stone-200 px-4 py-3">
                {{ $tasks->links() }}
            </div>
        </section>
    </div>
@endsection

@section('scripts')
<script>
    function onChangeFilterByProject(){
        document.getElementById('form_filter_by_project').submit()
    }
    function onChangeFilterByUser(){
        document.getElementById('form_filter_by_user').submit()
    }
</script>
@endsection
