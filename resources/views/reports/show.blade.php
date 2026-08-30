@extends('layouts.app')

@section('content')
@include('includes.errors')
@include('includes.messages')

@php
    $headerActions = '<a class="btn btn-primary btn-sm" href="'.url('/reports/'.$report->id.'/pdf').'"><i class="fa fa-file-pdf mr-1.5"></i> Descargar PDF</a>';
    if (isSenior()) {
        $headerActions .= '<form method="POST" action="'.url('/reports/'.$report->id).'" class="inline" onsubmit="return confirm(\'¿Borrar este reporte?\');">'
            .csrf_field()
            .method_field('DELETE')
            .'<button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash mr-1.5"></i> Borrar</button></form>';
    }
    $periodLabel = 'Desde el '.$periodFrom.' hasta '.$periodTo;
@endphp

<div class="page-shell">
@if (!$report->user->isClient())
    @include('includes.page-header', [
        'title' => 'Detalle de reporte',
        'subtitle' => $periodLabel.(\Auth::user()->id != $report->user_id ? ' · '.$report->user->name : ''),
        'breadcrumbs' => [
            ['label' => 'Reportes', 'url' => '/reports'],
            ['label' => 'Detalle', 'url' => null],
        ],
        'actions' => $headerActions,
    ])

    @php
        $total_ticket = 0;
        $total_manual = 0;
    @endphp

    <section class="card">
        <div class="card-header">Por proyecto</div>
        <div class="card-body space-y-4 text-sm">
            @forelse ($by_project as $key => $project)
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
            @empty
                <p class="text-stone-500">No hay esfuerzos en este reporte</p>
            @endforelse
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
                        @foreach ($tasks as $item)
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
                        @foreach ($efforts as $item)
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
    @include('includes.page-header', [
        'title' => 'Detalle de reporte',
        'subtitle' => $periodLabel.' · '.$projectName,
        'breadcrumbs' => [
            ['label' => 'Reportes', 'url' => '/reports'],
            ['label' => 'Detalle', 'url' => null],
        ],
        'actions' => $headerActions,
    ])

    <dl class="grid grid-cols-1 gap-3 sm:grid-cols-3">
        <div class="task-stat">
            <dt>Período</dt>
            <dd>{{ $periodFrom }} — {{ $periodTo }}</dd>
        </div>
        <div class="task-stat">
            <dt>Horas facturadas</dt>
            <dd>{{ number_format((float) $report->billed_hours, 2) }} h</dd>
        </div>
        <div class="task-stat">
            <dt>Fecha de emisión</dt>
            <dd>{{ $issuedAt }}</dd>
        </div>
    </dl>

    <section class="card">
        <div class="card-header">Tareas</div>
        <div class="card-body overflow-x-auto p-0">
            <table class="table-app">
                <thead>
                    <tr>
                        <th scope="col">Tarea</th>
                        <th scope="col">Proyecto</th>
                        <th scope="col">Horas cargadas</th>
                        <th scope="col">Horas facturadas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td><a href="/tasks/{{ $task->id }}">{{ $task->getTitle() }}</a></td>
                            <td>
                                <span class="inline-flex rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-semibold text-sky-800">{{ $task->project->name }}</span>
                            </td>
                            <td>{{ number_format($task->getEfforts() / 60, 2) }} h</td>
                            <td>{{ number_format((float) $task->billed, 2) }} h</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-stone-500">No hay tareas en este reporte</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="card">
        <div class="card-header">Horas extra</div>
        <div class="card-body overflow-x-auto p-0">
            <table class="table-app">
                <thead>
                    <tr>
                        <th scope="col">Detalle</th>
                        <th scope="col">Proyecto / Tarea</th>
                        <th scope="col">Horas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($efforts as $effort)
                        <tr>
                            <td>{{ $effort->detail }}</td>
                            <td>
                                @if ($effort->project)
                                    <span class="inline-flex rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-semibold text-sky-800">{{ $effort->project->name }}</span>
                                @endif
                                @if ($effort->task)
                                    <a href="/tasks/{{ $effort->task_id }}">{{ $effort->task->getTitle() }}</a>
                                @else
                                    <span class="text-xs text-stone-500">Manual</span>
                                @endif
                            </td>
                            <td>{{ number_format($effort->amount / 60, 2) }} h</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-sm text-stone-500">No hay horas extra en este reporte</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-2 border-t border-stone-200 px-4 py-3 text-sm">
            <span class="text-stone-500">Costo por hora ${{ number_format((float) $report->rate, 2) }}</span>
            <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 font-semibold text-emerald-800">
                TOTAL {{ number_format((float) $report->billed_hours, 2) }} h
            </span>
        </div>
    </section>

    @if ($report->detail)
        <section class="card">
            <div class="card-header">Resumen</div>
            <div class="card-body whitespace-pre-line text-sm text-stone-700">{{ $report->detail }}</div>
        </section>
    @endif
@endif
</div>
@endsection
