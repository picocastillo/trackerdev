@extends('layouts.app')
@section('content')
    @include('includes.errors')
    @include('includes.messages')

    @php
        $lastState = $task->getLastState();
        $hoursDiff = $task->estimation - $task->totalHours();
        $daysCreated = $task->getDaysCreatedAt();
    @endphp

    <div class="task-show space-y-6">
        {{-- Header --}}
        <header class="task-show-header overflow-hidden rounded-xl border border-stone-200 bg-white shadow-sm">
            <div class="relative border-b border-stone-200 bg-gradient-to-br from-stone-50 via-white to-primary/5 px-5 py-5 sm:px-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="min-w-0 flex-1 space-y-3">
                        <div class="flex flex-wrap items-center gap-2 text-sm text-stone-500">
                            <a href="/home" class="hover:text-primary">Tareas</a>
                            <span aria-hidden="true">/</span>
                            <span class="font-medium text-stone-700">{{ $task->project->name }}</span>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="font-display text-2xl font-bold tracking-tight text-stone-900 sm:text-3xl">
                                #{{ $task->getTitle() }}
                            </h1>
                            @if ($lastState == 2)
                                <span class="badge-state assigned">
                                    Asignada a <b>{{ $task->user->name }}</b>
                                    @if ($task->watcher_id)
                                        <span class="ml-1 opacity-90">({{ $task->watcher->name }})</span>
                                    @endif
                                </span>
                            @else
                                <span class="badge-state {{ getClassStateColor($lastState) }}">
                                    {{ getNameState($lastState) }}
                                    @if ($task->isToTest())
                                        ({{ $task->user->name }})
                                    @endif
                                </span>
                            @endif
                        </div>

                        @if ($task->task_id)
                            <p class="text-sm text-stone-500">
                                Hija de
                                <a href="/tasks/{{ $task->task->id }}" class="font-semibold">
                                    #{{ $task->task->getTitle() }}
                                </a>
                            </p>
                        @endif
                    </div>

                    @if (isManager())
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="/task/{{ $task->id }}/edit" class="btn btn-outline btn-sm" title="Editar">
                                <i class="fa fa-edit mr-1.5"></i> Editar
                            </a>
                            <a href="/task/{{ $task->id }}/create-a-child" class="btn btn-outline btn-sm" title="Crear tarea hija">
                                <i class="fa fa-plus mr-1"></i><i class="fa fa-child"></i>
                            </a>
                            <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('add_time').showModal()" title="Agregar tiempo">
                                <i class="fa fa-clock mr-1.5"></i> Tiempo
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Metrics --}}
                <dl class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3 @if (!isClient()) lg:grid-cols-5 @else lg:grid-cols-4 @endif">
                    <div class="task-stat">
                        <dt>Proyecto</dt>
                        <dd>{{ $task->project->name }}</dd>
                    </div>
                    <div class="task-stat">
                        <dt>Estimación</dt>
                        <dd class="flex flex-wrap items-center gap-2">
                            <span>{{ $task->estimation }}</span>
                            @if (isManager())
                                <span class="inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">
                                    {{ $task->billed }} F
                                </span>
                            @endif
                        </dd>
                    </div>
                    @if (!isClient())
                        <div class="task-stat">
                            <dt>Esfuerzo</dt>
                            <dd class="flex flex-wrap items-center gap-2">
                                <span>{{ $task->totalHours() }}</span>
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold {{ $hoursDiff > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    ({{ $hoursDiff > 0 ? '+' : '' }}{{ $hoursDiff }})
                                </span>
                            </dd>
                        </div>
                    @endif
                    <div class="task-stat">
                        <dt>Revisiones</dt>
                        <dd>{{ $task->review }}</dd>
                    </div>
                    <div class="task-stat">
                        <dt>Creada hace</dt>
                        <dd>{{ $daysCreated }} {{ $daysCreated == 0 ? 'día' : 'días' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Progress + workflow actions --}}
            <div class="flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div class="flex min-w-0 flex-1 items-center gap-3">
                    <div class="h-2.5 flex-1 overflow-hidden rounded-full bg-stone-200">
                        <div class="h-full rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ $task->getPercentage() }}%"></div>
                    </div>
                    <span class="shrink-0 text-sm font-semibold text-stone-600">{{ round($task->getPercentage(), 0) }}%</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if (!isClient() && $task->isComplete() && $lastState != 3 && $lastState != 4)
                        <form method="POST" action="/tasks/change-to-testing">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <button type="submit" class="btn btn-sm badge-state testing">
                                Pasar a testing <i class="fa fa-check ml-1"></i>
                            </button>
                        </form>
                    @endif
                    @if (!isClient() && $lastState == 3 && isManager())
                        <form method="POST" action="/tasks/change-to-finished">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <button type="submit" class="btn btn-sm badge-state finished">
                                Finalizar <i class="fa fa-check ml-1"></i>
                            </button>
                        </form>
                    @endif
                    @if (!isClient() && $lastState != 5)
                        <form method="POST" action="/tasks/change-to-feedback">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <button type="submit" class="btn btn-sm badge-state feedback">
                                Necesita Feedback
                            </button>
                        </form>
                    @endif
                    @if (!isClient() && (isInTeam($task->id) || isManager()))
                        <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('exampleModal').showModal()">
                            <i class="fa fa-plus mr-1"></i> Agregar Hito
                        </button>
                    @endif
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Main column --}}
            <div class="space-y-6 lg:col-span-8">
                {{-- Description --}}
                <section class="card">
                    <div class="card-header">Detalle</div>
                    <div class="card-body space-y-4">
                        <div class="prose-task text-sm leading-relaxed text-stone-700">
                            {!! nl2br(str_replace(' ', '&nbsp;', $task->description)) !!}
                        </div>

                        @if (count($childs))
                            <div class="border-t border-stone-100 pt-4">
                                <h3 class="mb-3 text-sm font-semibold text-stone-800">Tareas hijas</h3>
                                <ul class="space-y-2">
                                    @foreach ($childs as $item)
                                        <li class="flex flex-wrap items-center gap-2 text-sm">
                                            <a target="_blank" href="/tasks/{{ $item->id }}" class="font-medium">
                                                {{ $item->getTitle() }}
                                            </a>
                                            <span class="badge-state {{ getClassStateColor($item->getLastState()) }}">
                                                {{ getNameState($item->getLastState()) }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Milestones --}}
                <section class="card">
                    <div class="card-header flex items-center justify-between gap-3">
                        <span>Hitos a completar</span>
                        <span class="text-xs font-normal text-stone-500">{{ $task->getPercentage() }}% listo</span>
                    </div>
                    <div class="card-body space-y-4">
                        @if (count($task->items))
                            <ol class="space-y-2">
                                @foreach ($task->items as $item)
                                    @if ($item->completed)
                                        <li class="task-milestone task-milestone-done">
                                            <span class="task-milestone-check" aria-hidden="true"><i class="fa fa-check"></i></span>
                                            <span class="line-through text-stone-400">{{ $item->name }}</span>
                                        </li>
                                    @else
                                        <li>
                                            <form method="POST" action="/tasks/complete-item" class="task-milestone">
                                                @csrf
                                                <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                                <span class="task-milestone-check task-milestone-pending" aria-hidden="true"></span>
                                                <span class="flex-1 text-stone-800">{{ $item->name }}</span>
                                                @if (canChargeTime($task->id))
                                                    <button type="submit" class="btn btn-sm bg-emerald-600 text-white hover:bg-emerald-700" title="Completar hito">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                @endif
                                            </form>
                                        </li>
                                    @endif
                                @endforeach
                            </ol>
                        @else
                            <p class="py-6 text-center text-sm text-stone-500">No hay hitos aún en esta tarea</p>
                        @endif

                        @if (!isClient())
                            <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                                <p class="mb-2 font-semibold">Recordá:</p>
                                <ul class="list-disc space-y-1 pl-5 text-amber-800/90">
                                    <li>Ir marcando a medida que se van completando los hitos en caso de que existan.</li>
                                    <li>Revisar el <a target="_blank" href="/wiki#checking" class="font-semibold underline">Checking de Testing</a> antes de pasar a Testing.</li>
                                    <li>Dejar al menos una nota del branch donde se trabajó y comandos a correr para probar la tarea; explicar brevemente lo que se debe probar.</li>
                                    <li>“Pasar a testing” cuando completes la tarea.</li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Notes --}}
                <section class="card">
                    <div class="card-header flex items-center justify-between">
                        <span>Notas</span>
                        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-sm font-semibold text-amber-800">
                            {{ count($task->notes) }}
                        </span>
                    </div>
                    <div class="card-body space-y-5">
                        <form class="rounded-lg border border-stone-200 bg-stone-50/80 p-4" method="POST" action="/tasks/add-message">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <input type="hidden" name="user_id" value="{{ \Auth::user()->id }}">
                            <div class="flex gap-3">
                                <div class="hidden shrink-0 sm:block">
                                    <img
                                        class="user_image"
                                        src="{{ \Auth::user()->image ? url('images/'.\Auth::user()->image) : url('uploads/user.jpg') }}"
                                        alt="{{ \Auth::user()->name }}"
                                    >
                                </div>
                                <div class="min-w-0 flex-1 space-y-3">
                                    <textarea required name="message" rows="4" class="form-input" placeholder="Escribí una nota…"></textarea>
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <label class="flex items-center gap-2 text-sm text-stone-600">
                                            <input class="rounded border-stone-300 text-primary focus:ring-primary/30" id="private" name="is_private" type="checkbox" checked>
                                            Nota privada
                                        </label>
                                        <button type="submit" class="btn bg-emerald-600 text-white hover:bg-emerald-700">
                                            <i class="fa fa-plus mr-1.5"></i> Agregar nota
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="messages_task space-y-3">
                            @forelse ($task->getNotes() as $message)
                                @if (isClient() && !$message->is_private)
                                    <article class="task-note">
                                        <div class="hidden shrink-0 sm:block">
                                            <img
                                                src="{{ $message->user->image ? url('images/'.$message->user->image) : url('uploads/user.jpg') }}"
                                                class="user_image"
                                                alt="{{ $message->getUser() }}"
                                            >
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="prose-task text-sm text-stone-800">
                                                {!! nl2br(str_replace(' ', '&nbsp;', $message->message)) !!}
                                            </div>
                                            <footer class="mt-2 text-xs text-stone-500">
                                                Escrito por <cite class="not-italic font-medium text-stone-700">{{ $message->getUser() }}</cite>
                                                · {{ $message->getDate() }}
                                            </footer>
                                        </div>
                                    </article>
                                @endif
                                @if (!isClient())
                                    <article class="task-note">
                                        <div class="hidden shrink-0 sm:block">
                                            <img
                                                src="{{ $message->user->image ? url('images/'.$message->user->image) : url('uploads/user.jpg') }}"
                                                class="user_image"
                                                alt="{{ $message->getUser() }}"
                                            >
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="prose-task text-sm text-stone-800">
                                                {!! nl2br(str_replace(' ', '&nbsp;', $message->message)) !!}
                                            </div>
                                            <footer class="mt-2 flex flex-wrap items-center justify-between gap-2 text-xs text-stone-500">
                                                <span>
                                                    Escrito por <cite class="not-italic font-medium text-stone-700">{{ $message->getUser() }}</cite>
                                                    · {{ $message->getDate() }}
                                                </span>
                                                @if ($message->is_private)
                                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Privada</span>
                                                @else
                                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Pública</span>
                                                @endif
                                            </footer>
                                        </div>
                                    </article>
                                @endif
                            @empty
                                <p class="py-4 text-center text-sm text-stone-500">Todavía no hay notas</p>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-6 lg:col-span-4">
                @if (isManager())
                    <section class="card">
                        <div class="card-header">Asignación</div>
                        <div class="card-body space-y-4">
                            <form action="/tasks/assign-to" method="POST" class="space-y-2">
                                @csrf
                                <input class="hidden" name="task_id" value="{{ $task->id }}">
                                <label class="form-label" for="assign_to">Asignar a</label>
                                <div class="flex gap-2">
                                    <select id="assign_to" name="assign_to" class="form-input">
                                        @foreach ($devs as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm badge-state assigned shrink-0" title="Asignar">
                                        <i class="fa fa-user-plus"></i>
                                    </button>
                                </div>
                            </form>
                            <form action="/tasks/add-watcher" method="POST" class="space-y-2">
                                @csrf
                                <input class="hidden" name="task_id" value="{{ $task->id }}">
                                <label class="form-label" for="watcher_id">Watcher</label>
                                <div class="flex gap-2">
                                    <select id="watcher_id" name="user_id" class="form-input">
                                        @foreach ($devs as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm badge-state assigned shrink-0" title="Agregar watcher">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>
                @endif

                {{-- State history --}}
                <section class="card">
                    <div class="card-header">Estados</div>
                    <div class="card-body">
                        @if (count($task->states))
                            <ol class="task-timeline">
                                @foreach ($task->states as $item)
                                    <li class="task-timeline-item">
                                        <span class="badge-state {{ getClassStateColor($item->name) }}">
                                            {{ getNameState($item->name) }}
                                        </span>
                                        <span class="mt-1 block text-xs text-stone-500">
                                            {{ $item->user->name }}
                                            @if (isset($task->user_id))
                                                · <b class="font-semibold text-stone-700">{{ $task->user->name }}</b>
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ol>
                        @else
                            <p class="text-center text-sm text-stone-500">Sin estados registrados</p>
                        @endif
                    </div>
                </section>

                {{-- Files --}}
                <section class="card">
                    <div class="card-header">Archivos</div>
                    <div class="card-body space-y-4">
                        <form action="/tasks/attach-file" method="POST" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <input class="hidden" name="task_id" value="{{ $task->id }}">
                            <input class="hidden" name="user_id" value="{{ \Auth::user()->id }}">
                            <input required type="file" name="file" class="form-input">
                            <button type="submit" class="btn btn-primary w-full">
                                <i class="fa fa-upload mr-1.5"></i> Subir
                            </button>
                        </form>

                        @if (count($task->files))
                            <ul class="divide-y divide-stone-100 border-t border-stone-100 pt-2">
                                @foreach ($task->files as $file)
                                    <li class="py-2.5 text-sm">
                                        <a href="/{{ $file->path }}" class="font-medium">{{ $file->real_name }}</a>
                                        <p class="mt-0.5 text-xs text-stone-500">
                                            {{ $file->getDate() }} · {{ $file->user->name }}
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-center text-sm text-stone-500">Aún no hay archivos adjuntos</p>
                        @endif
                    </div>
                </section>

                {{-- Time log --}}
                @if (canShowTimes())
                    <section class="card">
                        <div class="card-header flex items-center justify-between">
                            <span>Tiempos</span>
                            <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                                {{ $task->totalHours() }} h
                            </span>
                        </div>
                        <div class="card-body space-y-4">
                            <div class="max_height_table overflow-x-auto">
                                @if (count($task->efforts))
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
                                                    <th scope="row" class="whitespace-nowrap">
                                                        {{ $effort->getHours() }}
                                                        <span class="block text-xs font-normal text-stone-500">{{ $effort->user->name }}</span>
                                                    </th>
                                                    <td>{{ $effort->detail }}</td>
                                                    <td class="whitespace-nowrap">{{ $effort->getDate() }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="py-4 text-center text-sm text-stone-500">Aún no hay tiempos</p>
                                @endif
                            </div>

                            @if (canChargeTime($task->id) && $lastState != 4)
                                <form method="POST" action="/tasks/add-effort" class="space-y-2 rounded-lg border border-stone-200 bg-stone-50/80 p-3">
                                    @csrf
                                    <input type="number" name="task_id" class="hidden" value="{{ $task->id }}">
                                    <input type="number" name="user_id" class="hidden" value="{{ \Auth::user()->id }}">
                                    <div class="flex flex-wrap gap-2">
                                        <input required type="number" min="0" step="1" name="time" class="form-input w-28" placeholder="Minutos">
                                        <input required type="text" name="description" class="form-input min-w-0 flex-1" placeholder="Descripción">
                                        <button type="submit" class="btn bg-emerald-600 text-white hover:bg-emerald-700" title="Cargar tiempo">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </section>
                @endif
            </aside>
        </div>
    </div>

    <dialog id="exampleModal" class="w-full max-w-lg rounded-lg border border-stone-200 bg-white p-0 shadow-xl backdrop:bg-black/50">
        <div class="flex items-center justify-between border-b border-stone-200 px-4 py-3">
            <h5 class="font-semibold">Agregar nuevo hito a la tarea</h5>
            <button type="button" class="text-2xl leading-none text-stone-500 hover:text-stone-800" onclick="document.getElementById('exampleModal').close()" aria-label="Close">&times;</button>
        </div>
        <form method="POST" action="/tasks/add-item" class="card-body space-y-4">
            @csrf
            <input type="hidden" name="task_id" value="{{ $task->id }}">
            <label class="form-label" for="milestone_text">Descripción del hito</label>
            <input id="milestone_text" type="text" name="text" placeholder="Breve descripción del hito" class="form-input">
            <button type="submit" class="btn btn-primary w-full">
                <i class="fa fa-plus mr-1.5"></i> Agregar
            </button>
        </form>
    </dialog>

    <dialog id="add_time" class="w-full max-w-lg rounded-lg border border-stone-200 bg-white p-0 shadow-xl backdrop:bg-black/50">
        <div class="flex items-center justify-between border-b border-stone-200 px-4 py-3">
            <h5 class="font-semibold">Agregar tiempo a esta tarea</h5>
            <button type="button" class="text-2xl leading-none text-stone-500 hover:text-stone-800" onclick="document.getElementById('add_time').close()" aria-label="Close">&times;</button>
        </div>
        <form method="POST" action="/tasks/add-time" class="card-body space-y-4">
            @csrf
            <input type="hidden" name="task_id" value="{{ $task->id }}">
            <label class="form-label" for="add_time_hours">Tiempo en horas</label>
            <input id="add_time_hours" type="number" name="time" placeholder="Agregar tiempo en horas" class="form-input">
            <button type="submit" class="btn btn-primary w-full">
                <i class="fa fa-plus mr-1.5"></i> Agregar
            </button>
        </form>
    </dialog>
@endsection
