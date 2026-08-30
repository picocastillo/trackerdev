@extends('layouts.app')

@section('content')
@include('includes.errors')
@include('includes.messages')

<div class="page-shell">
    @include('includes.page-header', [
        'title' => 'Reportes',
        'subtitle' => isClient()
            ? 'Tareas actuales del proyecto'
            : (isSenior() ? 'Reportes para clientes' : 'Horas sin facturar y liquidaciones'),
        'breadcrumbs' => [
            ['label' => 'Inicio', 'url' => '/home'],
            ['label' => 'Reportes', 'url' => null],
        ],
    ])

    @if (isSenior())
        <section class="card">
            <div class="card-header">Crear reporte</div>
            <div class="card-body">
                <form class="page-toolbar items-end" action="{{ url('/reports') }}" method="GET">
                    <div>
                        <label class="form-label" for="report-client">Cliente</label>
                        <select name="user_id" id="report-client" class="form-input w-auto" required>
                            <option value="">Seleccionar cliente</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" @selected((string) ($selectedClientId ?? '') === (string) $client->id)>{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="report-start">Desde</label>
                        <input id="report-start" class="form-input w-auto" type="date" name="start_date" value="{{ old('start_date', $start_date) }}">
                    </div>
                    <div>
                        <label class="form-label" for="report-end">Hasta</label>
                        <input id="report-end" class="form-input w-auto" type="date" name="end_date" value="{{ old('end_date', $end_date) }}">
                    </div>
                    <button class="btn btn-outline" type="submit">Ver resumen</button>
                </form>
            </div>
        </section>

        @if (!empty($previewClient))
            <section class="card">
                <div class="card-header">Agregar horas</div>
                <div class="card-body">
                    <form class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4" action="/reports/hours" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $previewClient->id }}">
                        <input type="hidden" name="start_date" value="{{ $start_date }}">
                        <input type="hidden" name="end_date" value="{{ $end_date }}">
                        <div>
                            <label class="form-label" for="hour-kind">Tipo</label>
                            <select name="kind" id="hour-kind" class="form-input" onchange="onHourKindChange()">
                                <option value="manual" @selected(old('kind') === 'manual')>Manual</option>
                                <option value="task" @selected(old('kind') === 'task')>Sobre tarea</option>
                            </select>
                        </div>
                        <div id="hour-project-wrap">
                            <label class="form-label" for="hour-project">Proyecto</label>
                            <select name="project_id" id="hour-project" class="form-input">
                                @foreach ($previewProjects as $project)
                                    <option value="{{ $project->id }}" @selected((string) old('project_id') === (string) $project->id)>{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="hour-task-wrap" class="hidden">
                            <label class="form-label" for="hour-task">Tarea</label>
                            <select name="task_id" id="hour-task" class="form-input">
                                @foreach ($clientTasks as $task)
                                    <option value="{{ $task->id }}" @selected((string) old('task_id') === (string) $task->id)>{{ $task->getTitle() }} ({{ $task->project->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label" for="hour-amount">Horas</label>
                            <input id="hour-amount" class="form-input" type="number" name="hours" value="{{ old('hours') }}" required>
                        </div>
                        <div class="md:col-span-2 lg:col-span-4">
                            <label class="form-label" for="hour-detail">Detalle</label>
                            <input id="hour-detail" class="form-input" type="text" name="detail" value="{{ old('detail') }}" required>
                        </div>
                        <div class="md:col-span-2 lg:col-span-4">
                            <button class="btn btn-secondary" type="submit">Agregar horas</button>
                        </div>
                    </form>
                </div>
            </section>

            <form action="/reports/store" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="user_id" value="{{ $previewClient->id }}">
                <input type="hidden" name="from" value="{{ $start_date }}">
                <input type="hidden" name="to" value="{{ $end_date }}">

                <section class="card">
                    <div class="card-header">Resumen de {{ $previewClient->name }}</div>
                    <div class="card-body overflow-x-auto p-0">
                        <table class="table-app">
                            <thead>
                                <tr>
                                    <th scope="col">Tarea</th>
                                    <th scope="col">Proyecto</th>
                                    <th scope="col">Horas cargadas</th>
                                    <th scope="col">Horas a facturar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($previewTasks as $task)
                                    <input type="hidden" name="tasks[]" value="{{ $task->id }}">
                                    <tr>
                                        <td><a href="/tasks/{{ $task->id }}">{{ $task->getTitle() }}</a></td>
                                        <td>
                                            <span class="inline-flex rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-semibold text-sky-800">{{ $task->project->name }}</span>
                                        </td>
                                        <td>{{ number_format($task->getEfforts() / 60, 2) }} h</td>
                                        <td>
                                            <input
                                                type="number"
                                                name="billed[{{ $task->id }}]"
                                                value="{{ old('billed.'.$task->id, number_format($task->hoursToBill(), 2, '.', '')) }}"
                                                min="0"
                                                step="0.01"
                                                data-billed-input
                                                oninput="updateReportTotal()"
                                                class="form-input w-24"
                                            >
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-sm text-stone-500">No hay tareas en el período</td>
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
                                    <th scope="col">Proyecto</th>
                                    <th scope="col">Horas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($previewEfforts as $effort)
                                    <input type="hidden" name="efforts[]" value="{{ $effort->id }}">
                                    <tr>
                                        <td>{{ $effort->detail }}</td>
                                        <td>
                                            @if ($effort->project)
                                                <span class="inline-flex rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-semibold text-sky-800">{{ $effort->project->name }}</span>
                                            @endif
                                            <span class="text-xs text-stone-500">Manual</span>
                                        </td>
                                        <td>
                                            <input
                                                type="number"
                                                name="effort_hours[{{ $effort->id }}]"
                                                value="{{ old('effort_hours.'.$effort->id, number_format($effort->amount / 60, 2, '.', '')) }}"
                                                min="0"
                                                step="0.25"
                                                data-billed-input
                                                oninput="updateReportTotal()"
                                                class="form-input w-24"
                                            >
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-6 text-center text-sm text-stone-500">No hay horas extra en el período</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-2 border-t border-stone-200 px-4 py-3 text-sm">
                        <span class="text-stone-500">Total facturable</span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-2.5 py-0.5 font-semibold text-emerald-800">
                            <span><span id="report-total">0.00</span> h</span>
                            <span id="report-hours-amount-wrap" class="hidden">· $<span id="report-hours-amount">0.00</span></span>
                        </span>
                    </div>
                </section>

                <section class="card">
                    <div class="card-header">Finalizar</div>
                    <div class="card-body space-y-4">
                        <div>
                            <label class="form-label" for="report-detail">Resumen / comentarios</label>
                            <textarea id="report-detail" class="form-input whitespace-pre-wrap" rows="12" name="detail">{{ old('detail', $defaultDetail) }}</textarea>
                        </div>
                        <div>
                            <label class="form-label" for="report-rate">Costo por hora</label>
                            <div class="flex flex-wrap items-center gap-3">
                                <input id="report-rate" type="number" min="0" step="0.01" class="form-input w-40" name="rate" value="{{ old('rate') }}" required oninput="updateReportTotal()">
                                <span id="report-amount-wrap" class="hidden inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-sm font-semibold text-emerald-800">
                                    Total parcial $<span id="report-amount">0.00</span>
                                </span>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Guardar reporte</button>
                    </div>
                </section>
            </form>
        @endif
    @endif

    @if (isClient())
        <section class="card">
            <div class="card-header">Tareas actuales</div>
            <div class="card-body overflow-x-auto p-0">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th scope="col">Tarea</th>
                            <th scope="col">Estimación</th>
                            <th scope="col">Horas cargadas</th>
                            <th scope="col">Progreso</th>
                            <th scope="col">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tasks as $task)
                            <tr>
                                <td><a href="/tasks/{{ $task->id }}">{{ $task->getTitle() }}</a></td>
                                <td>{{ $task->billed }} Horas</td>
                                <td>{{ $task->totalHours() }} h</td>
                                <td>
                                    <div class="h-4 w-full min-w-[6rem] overflow-hidden rounded-full bg-stone-200">
                                        <div class="flex h-full items-center justify-center rounded-full bg-emerald-500 text-xs text-white" style="width: {{ $task->getPercentage() }}%">{{ round($task->getPercentage(), 2) }}%</div>
                                    </div>
                                </td>
                                <td>{{ $task->getDate() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-stone-500">No hay tareas actuales</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-2 border-t border-stone-200 px-4 py-3 text-sm">
                <span class="text-stone-500">
                    @if (!empty($lastReport))
                        Incluye horas cargadas posteriores al último reporte (hasta {{ $lastReport->to }}).
                    @endif
                </span>
                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 font-semibold text-emerald-800">
                    TOTAL {{ $totalLoadedHours ?? '0.00' }} h
                </span>
            </div>
        </section>
    @elseif (!isSenior())
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
                            @foreach ($efforts ?? [] as $effort)
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

    <section class="card">
        <div class="card-header">Listado de reportes</div>
        <div class="card-body overflow-x-auto p-0">
            <table class="table-app">
                <thead>
                    <tr>
                        @if (isClient())
                            <th scope="col">Período</th>
                            <th scope="col">Total de horas</th>
                            <th scope="col"></th>
                        @else
                            @if (isSenior())
                                <th scope="col">Usuario</th>
                                <th scope="col">HF</th>
                            @endif
                            <th scope="col">Productividad</th>
                            <th scope="col">Costo hora</th>
                            <th scope="col">Total de horas</th>
                            <th scope="col">Desde</th>
                            <th scope="col">Hasta</th>
                            @if (isSenior())
                                <th scope="col"></th>
                            @endif
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                        <tr>
                            @if (isClient())
                                <td>{{ \Carbon\Carbon::parse($report->from)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($report->to)->format('d/m/Y') }}</td>
                                <td>{{ number_format((float) $report->billed_hours, 2) }} h</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{ url('/reports/'.$report->id.'/pdf') }}">
                                        <i class="fa fa-file-pdf mr-1.5"></i> Descargar
                                    </a>
                                </td>
                            @else
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
                                @if (isSenior())
                                    <td>
                                        <form method="POST" action="{{ url('/reports/'.$report->id) }}" onsubmit="return confirm('¿Borrar este reporte?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-red-700 hover:text-red-800">Borrar</button>
                                        </form>
                                    </td>
                                @endif
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ isClient() ? 3 : (isSenior() ? 8 : 5) }}" class="px-4 py-6 text-center text-sm text-stone-500">No hay reportes</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-stone-200 px-4 py-3">
            {{ $reports->links() }}
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    function onHourKindChange() {
        var kind = document.getElementById('hour-kind');
        var projectWrap = document.getElementById('hour-project-wrap');
        var taskWrap = document.getElementById('hour-task-wrap');
        if (!kind || !projectWrap || !taskWrap) {
            return;
        }
        var isTask = kind.value === 'task';
        projectWrap.classList.toggle('hidden', isTask);
        taskWrap.classList.toggle('hidden', !isTask);
    }

    function formatMoney(value) {
        var parts = Number(value).toFixed(2).split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return parts[0] + ',' + parts[1];
    }

    function updateReportTotal() {
        var total = 0;
        document.querySelectorAll('[data-billed-input]').forEach(function (input) {
            total += parseFloat(input.value || 0);
        });
        var target = document.getElementById('report-total');
        if (target) {
            target.textContent = total.toFixed(2);
        }

        var rateInput = document.getElementById('report-rate');
        var rate = rateInput ? parseFloat(rateInput.value || 0) : 0;
        var amount = total * rate;
        var hasAmount = rate > 0 && total > 0;
        var amountText = formatMoney(amount);

        ['report-amount-wrap', 'report-hours-amount-wrap'].forEach(function (id) {
            var wrap = document.getElementById(id);
            if (wrap) {
                wrap.classList.toggle('hidden', !hasAmount);
            }
        });
        ['report-amount', 'report-hours-amount'].forEach(function (id) {
            var node = document.getElementById(id);
            if (node) {
                node.textContent = amountText;
            }
        });
    }

    onHourKindChange();
    updateReportTotal();
</script>
@endsection
