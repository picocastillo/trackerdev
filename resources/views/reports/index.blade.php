@extends('layouts.app')

@section('content')
@include('includes.errors')
@include('includes.messages')

<div class="page-shell">
    @include('includes.page-header', [
        'title' => 'Reportes',
        'subtitle' => isClient() ? 'Tareas actuales del proyecto' : 'Horas sin facturar y liquidaciones',
        'breadcrumbs' => [
            ['label' => 'Inicio', 'url' => '/home'],
            ['label' => 'Reportes', 'url' => null],
        ],
    ])

    @if (isClient())
        <section class="card">
            <div class="card-header">Tareas actuales</div>
            <div class="card-body overflow-x-auto p-0">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th scope="col">Tarea</th>
                            <th scope="col">Estimación</th>
                            <th scope="col">Progreso</th>
                            <th scope="col">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td><a href="/tasks/{{ $task->id }}">{{ $task->getTitle() }}</a></td>
                                <td>{{ $task->billed }} Horas</td>
                                <td>
                                    <div class="h-4 w-full min-w-[6rem] overflow-hidden rounded-full bg-stone-200">
                                        <div class="flex h-full items-center justify-center rounded-full bg-emerald-500 text-xs text-white" style="width: {{ $task->getPercentage() }}%">{{ round($task->getPercentage(), 2) }}%</div>
                                    </div>
                                </td>
                                <td>{{ $task->getDate() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @else
        <section class="card">
            <div class="card-header">Horas sin facturar</div>
            <div class="card-body space-y-4">
                <div class="overflow-x-auto">
                    <table class="table-app">
                        <thead>
                            <tr>
                                <th scope="col">Detalle</th>
                                <th scope="col">Tarea</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach ($efforts as $effort)
                                <tr>
                                    <td>
                                        {{ $effort->detail }}
                                        @if ($effort->task)
                                            (<a href="/tasks/{{ $effort->task_id }}">{{ $effort->task->getTitle() }}</a>)
                                        @endif
                                    </td>
                                    <td>
                                        @if ($effort->project)
                                            <span class="inline-flex rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-semibold text-sky-800">{{ $effort->project->name }}</span>
                                        @else
                                            <a href="/tasks/{{ $effort->task_id }}">{{ $effort->task->getTitle() }}</a>
                                            <span class="inline-flex rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-semibold text-sky-800">{{ $effort->task->project->name }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $effort->amount }} minutos
                                        @php $total = $total + $effort->amount; @endphp
                                    </td>
                                    <td>{{ $effort->getDate() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-right text-sm">
                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 font-semibold text-emerald-800">
                        TOTAL {{ floor($total / 60).':'.$total % 60 }} Horas
                    </span>
                </div>
            </div>
        </section>
    @endif

    @if (isSenior())
        <section class="card">
            <div class="card-header">Crear reporte</div>
            <div class="card-body">
                <form class="page-toolbar" action="/reports" method="POST">
                    {{ csrf_field() }}
                    <select name="user_id" class="form-input w-auto">
                        @foreach ($users as $key => $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <input class="form-input w-auto" type="date" name="start_date" value="{{ old('start_date') ?? $start_date }}">
                    <input class="form-input w-auto" type="date" name="end_date" value="{{ old('end_date') ?? $end_date }}">
                    <button class="btn btn-outline" type="submit">Crear</button>
                </form>
            </div>
        </section>
    @endif

    <section class="card">
        <div class="card-header">Listado de reportes</div>
        <div class="card-body overflow-x-auto p-0">
            <table class="table-app">
                <thead>
                    <tr>
                        @if (isSenior())
                            <th scope="col">Usuario</th>
                            <th scope="col">HF</th>
                        @endif
                        <th scope="col">Productividad</th>
                        <th scope="col">Costo hora</th>
                        <th scope="col">Total de horas</th>
                        <th scope="col">Desde</th>
                        <th scope="col">Hasta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            @if (isSenior())
                                <th scope="row">
                                    <a href="/reports/{{ $report->id }}">{{ $report->user->name }}</a>
                                </th>
                                <td>{{ $report->billed_hours }}</td>
                            @endif
                            <td>{{ $report->productivity }} <a href="/reports/{{ $report->id }}">[ver]</a></td>
                            <td>{{ $report->rate }}</td>
                            <td>{{ $report->billed_hours }}</td>
                            <td>{{ $report->from }}</td>
                            <td>{{ $report->to }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="border-t border-stone-200 px-4 py-3">
            {{ $reports->links() }}
        </div>
    </section>
</div>
@endsection
