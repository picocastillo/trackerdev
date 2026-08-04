@extends('layouts.app')

@section('content')
<div class="page-shell">
@if ($report->user->role_id != 4)
    @include('includes.page-header', [
        'title' => 'Detalle de reporte',
        'subtitle' => 'Desde el '.explode('-', $report->from)[2].'/'.explode('-', $report->from)[1].' hasta '.explode('-', $report->to)[2].'/'.explode('-', $report->to)[1].(\Auth::user()->id != $report->user_id ? ' · '.$report->user->name : ''),
        'breadcrumbs' => [
            ['label' => 'Reportes', 'url' => '/reports'],
            ['label' => 'Detalle', 'url' => null],
        ],
    ])

    @php
        $total_ticket = 0;
        $total_manual = 0;
    @endphp

    <section class="card">
        <div class="card-header">Por proyecto</div>
        <div class="card-body space-y-4 text-sm">
            @foreach ($by_project as $key => $project)
                <div>
                    <h3 class="mb-2 font-semibold text-stone-800">{{ $key }}</h3>
                    <ul class="list-disc space-y-1 pl-5 text-stone-700">
                        @foreach ($by_project[$key] as $effort)
                            <li>
                                {{ $effort['amount'] }} minutos — {{ $effort['detail'] }} ({{ $effort['date'] }})
                                @if ($effort['task_id'])
                                    [<a href="/tasks/{{ $effort['task_id'] }}">{{ $effort['title_task'] }}</a>]
                                    @php $total_ticket += $effort['amount']; @endphp
                                @else
                                    [Cargado Manualmente]
                                    @php $total_manual += $effort['amount']; @endphp
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
            <p class="text-xs text-stone-500">Se paga 15%+ si supera el 85 y 30%+ con el 95 *solo del tiempo cargado en tickets</p>
        </div>
    </section>

    <section class="card">
        <div class="card-header">Totales a liquidar</div>
        <div class="card-body">
            @php $total = 0; @endphp
            <dl class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div class="task-stat">
                    <dt>Horas en tickets</dt>
                    <dd>
                        {{ cuth($total_ticket / 60) }} hs · ${{ $report->rate * $total_ticket / 60 }}
                        @php $total += $report->rate * $total_ticket / 60; @endphp
                        @if ($report->productivity > 95)
                            <span class="mt-1 block text-xs font-semibold text-emerald-700">+ ${{ $report->rate * $total_ticket / 60 * 0.3 }} (+30%)</span>
                            @php $total += $report->rate * $total_ticket / 60 * 0.3; @endphp
                        @endif
                        @if ($report->productivity > 85 && $report->productivity < 96)
                            <span class="mt-1 block text-xs font-semibold text-emerald-700">+ ${{ $report->rate * $total_ticket / 60 * 0.15 }} (+15%)</span>
                            @php $total += $report->rate * $total_ticket / 60 * 0.15; @endphp
                        @endif
                    </dd>
                </div>
                <div class="task-stat">
                    <dt>Horas manuales</dt>
                    <dd>
                        {{ cuth($total_manual / 60) }} hs · ${{ $report->rate * $total_manual / 60 }}
                        @php $total += $report->rate * $total_manual / 60; @endphp
                    </dd>
                </div>
            </dl>
            <div class="text-right text-lg font-bold text-stone-900">TOTAL: ${{ $total }}</div>
        </div>
    </section>

    <div class="text-center">
        <button type="button" class="btn btn-primary" onclick="document.getElementById('collapseExample').classList.toggle('hidden')">
            Ver más detalles
        </button>
    </div>

    <div id="collapseExample" class="hidden space-y-6">
        <section class="card">
            <div class="card-header">Tareas</div>
            <div class="card-body overflow-x-auto p-0">
                <table class="table-app text-center">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Horas Estimadas</th>
                            <th scope="col">Esfuerzo</th>
                            <th scope="col">Productividad</th>
                            <th scope="col">Proyecto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (explode(',', $report->tasks) as $id)
                            @php $item = App\Models\Task::findOrFail($id) @endphp
                            <tr>
                                <th scope="row"><a href="/tasks/{{ $item->id }}">{{ $item->getTitle() }}</a></th>
                                <td>{{ $item->estimation }}</td>
                                <td>{{ minutesToHours($item->getEfforts()) }}</td>
                                <td>%{{ number_format($item->getProductivity($report->user_id) * 100, 2) }}</td>
                                <td>{{ $item->project->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-stone-200 px-4 py-3 text-sm">
                Productividad: <b>%{{ $report->productivity }}</b>
            </div>
        </section>

        <section class="card">
            <div class="card-header">Tiempos cargados</div>
            <div class="card-body overflow-x-auto p-0">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th scope="col">Descripción</th>
                            <th scope="col">H</th>
                            <th scope="col">Proyecto</th>
                            <th scope="col">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (explode(',', $report->efforts) as $id)
                            @php $item = App\Models\Effort::findOrFail($id); @endphp
                            <tr>
                                <th scope="row">
                                    {{ $item->detail }}
                                    @if ($item->task)
                                        ({{ $item->task->getTitle() }})
                                    @endif
                                </th>
                                <td>{{ minutesToHours($item->amount) }}</td>
                                <td>{{ $item->project->name }}</td>
                                <td>{{ $item->getDate() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="space-y-2 border-t border-stone-200 px-4 py-3 text-sm">
                <p>Total trabajadas: <b>{{ $report->billed_hours }} horas</b></p>
                <p class="text-center text-stone-600">{{ $report->detail }}</p>
                <p class="text-right">Costo por hora $<b>{{ $report->rate }}</b></p>
            </div>
        </section>
    </div>

@else
    @php
        $aproved_hours = 0;
        $total_hours_per_task = 0;
    @endphp

    @include('includes.page-header', [
        'title' => 'Detalle de reporte',
        'subtitle' => 'Desde el '.explode('-', $report->from)[2].'/'.explode('-', $report->from)[1].' hasta '.explode('-', $report->to)[2].'/'.explode('-', $report->to)[1],
        'breadcrumbs' => [
            ['label' => 'Reportes', 'url' => '/reports'],
            ['label' => 'Detalle', 'url' => null],
        ],
    ])

    <section class="card">
        <div class="card-header">Tareas que se estuvieron desarrollando</div>
        <div class="card-body">
            <ol class="list-decimal space-y-3 pl-5 text-sm">
                @foreach ($tasks as $task)
                    @php
                        if ($task->estimation)
                            $total_hours_per_task += $task->estimation;
                        else
                            $total_hours_per_task += $task->getEfforts();
                    @endphp
                    <li>
                        <a target="_blank" href="/tasks/{{ $task->id }}">{{ $task->name }}</a>
                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{ $task->project->name }}</span>
                        ({{ $task->getDate() }})
                        ({{ $task->billed }} horas)
                        @if ($task->items()->count())
                            <br>Hitos
                            <ul class="list-disc pl-5">
                                @foreach ($task->items as $item)
                                    <li>{{ $item->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                        @if (!$task->estimation)
                            <br>Esfuerzos
                            <ul class="list-disc pl-5">
                                @foreach ($task->efforts as $item)
                                    <li>{{ $item->detail }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    @if (count($efforts))
        <section class="card">
            <div class="card-header">Esfuerzos</div>
            <div class="card-body">
                <ul class="list-disc space-y-2 pl-5 text-sm">
                    @foreach ($efforts as $effort)
                        <input type="hidden" name="efforts[]" value="{{ $effort->id }}">
                        <li>
                            {{ $effort->detail }}
                            <i class="text-stone-500">[{{ $effort->getDate() }}]</i>
                            <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{ $effort->project->name }}</span>
                            [{{ minutesToHours($effort->amount * $effort->user->role->weight) }} Hs]
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="border-t border-stone-200 px-4 py-3 text-right text-sm font-semibold">
                Total de horas facturadas: {{ $report->billed_hours }}
            </div>
        </section>
    @else
        <div class="text-right text-sm font-semibold">
            Total de horas facturadas: {{ $report->billed_hours }}
        </div>
    @endif
@endif
</div>
@endsection
