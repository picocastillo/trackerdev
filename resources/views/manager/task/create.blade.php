@extends('layouts.app')

@section('content')
@include('includes.errors')
@include('includes.messages')

<div class="page-shell">
    @include('includes.page-header', [
        'title' => $isEdit ? 'Editar tarea' : 'Crear tarea',
        'subtitle' => $isEdit ? 'Actualizá los datos de la tarea' : 'Definí detalle e hitos de la nueva tarea',
        'breadcrumbs' => [
            ['label' => 'Tareas', 'url' => '/home'],
            ['label' => $isEdit ? 'Editar' : 'Crear', 'url' => null],
        ],
    ])

    <form method="POST" action="{{ $isEdit ? '/task/update' : '/task/create' }}">
        @csrf
        @if ($isEdit)
            <input type="hidden" value="{{ $task->id }}" name="task_id" required>
        @endif
        @if (isset($task_id))
            <input type="hidden" value="{{ $task_id }}" name="task_id">
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="card">
                <div class="card-header">Detalle de tarea</div>
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <label for="dev" class="form-label">Desarrollador</label>
                            <select name="user_id" class="form-input" id="dev">
                                <option selected value="{{ 0 }}">Sin Definir</option>
                                @foreach ($devs as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="estimation" class="form-label">Estimación</label>
                            @if ($isEdit)
                                <input id="estimation" type="number" value="{{ $task->estimation }}" class="form-input" name="estimation">
                            @else
                                <input id="estimation" type="number" class="form-input" name="estimation">
                            @endif
                        </div>
                        <div>
                            <label for="proj" class="form-label">Proyecto</label>
                            <select name="project_id" class="form-input" id="proj">
                                @if ($isEdit)
                                    <option value="{{ $task->project->id }}">{{ $task->project->name }}</option>
                                @else
                                    @foreach ($projects as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-12">
                        <div class="sm:col-span-10">
                            <label class="form-label">Título</label>
                            @if ($isEdit)
                                <input type="text" value="{{ $task->name }}" class="form-input" name="name" required>
                            @else
                                <input type="text" class="form-input" name="name" required>
                            @endif
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label text-center">F</label>
                            @if ($isEdit)
                                <input type="text" value="{{ $task->billed }}" class="form-input" name="billed" required>
                            @else
                                <input type="text" class="form-input" name="billed" required>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Información adicional</label>
                        @if ($isEdit)
                            <textarea class="form-input" name="description" spellcheck="false" required>{{ $task->description }}</textarea>
                        @else
                            <textarea rows="6" class="form-input" name="description" required></textarea>
                        @endif
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary">Terminar</button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Items</div>
                <div class="card-body">
                    <div id="create_task" items="{{ json_encode($items) }}"></div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
