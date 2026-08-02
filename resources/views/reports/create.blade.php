@extends('layouts.app')

@section('content')
<div class="page-shell">
    @include('includes.page-header', [
        'title' => 'Nuevo reporte',
        'subtitle' => 'Para '.$user->name.' · '.explode('-', $from)[2].'/'.explode('-', $from)[1].' — '.explode('-', $to)[2].'/'.explode('-', $to)[1],
        'breadcrumbs' => [
            ['label' => 'Reportes', 'url' => '/reports'],
            ['label' => 'Crear', 'url' => null],
        ],
    ])

@if ($user->role->seniority == "senior" || $user->role->seniority == "semi-senior" || $user->role->seniority == "junior")
<form action="/reports/store" method="post" class="space-y-6">
    @csrf
    <input type="hidden" name="to" value="{{ $to }}">
    <input type="hidden" name="user_id" value="{{ $user->id }}">
    <input type="hidden" name="from" value="{{ $from }}">
    @php
        $productivity_f = 0;
        $productivity = 0;
        $total_hours_billed_per_task = 0;
    @endphp

    <section class="card">
        <div class="card-header">Tareas</div>
        <div class="card-body overflow-x-auto p-0">
            <table class="table-app">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Horas Estimadas</th>
                        <th scope="col">Horas Facturadas</th>
                        <th scope="col">Esfuerzos</th>
                        <th scope="col">Productividad (E/F)</th>
                        <th scope="col">Proyecto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $item)
                    <input type="hidden" name="tasks[]" value="{{ $item->id }}">
                    <tr>
                        <th scope="row"><a href="/tasks/{{ $item->id }}">{{ $item->getTitle() }}</a></th>
                        <td>{{ $item->estimation }}</td>
                        <td>{{ $item->billed }}</td>
                        <td>{{ $item->getEfforts() }}</td>
                        <td>
                            %{{ number_format(($item->getProductivity($user->id)) * 100, 2) }}
                            @php
                                if ($item->getEfforts() != 0)
                                    $productivity += ($item->estimation * 60 / $item->getEfforts()) * 100;
                                $productivity_f += $item->getProductivity2($user->id) * 100;
                            @endphp
                            /%{{ number_format(($item->getProductivity2($user->id)) * 100, 2) }}
                        </td>
                        <td>{{ $item->project->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (count($tasks))
            <div class="border-t border-stone-200 px-4 py-3 text-sm">
                Productividad (E/F):
                <b>%{{ number_format($productivity / count($tasks), 2) }}</b>
                / <b>%{{ number_format($productivity_f / count($tasks), 2) }}</b>
                <input type="hidden" name="productivity" value="{{ $productivity / count($tasks) }}">
            </div>
        @endif
    </section>

    <section class="card">
        <div class="card-header">Tiempo cargado</div>
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
                    @php $total_hours = 0; $total_manual_hours = 0; @endphp
                    @foreach ($efforts as $item)
                    <input type="hidden" name="efforts[]" value="{{ $item->id }}">
                    <tr>
                        <th scope="row">
                            {{ $item->detail }}
                            @if ($item->task)
                                ({{ $item->task->getTitle() }})
                            @else
                                [manual]
                            @endif
                        </th>
                        <td>
                            {{ $item->amount }}
                            @php
                                if ($item->task) {
                                    $total_hours += $item->amount;
                                } else {
                                    $total_manual_hours += $item->amount;
                                }
                            @endphp
                        </td>
                        <td>{{ $item->project->name }}</td>
                        <td>{{ $item->getDate() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="space-y-2 border-t border-stone-200 px-4 py-3 text-sm">
            <p><i>Total trabajadas:</i> <b>{{ minutesToHours($total_manual_hours + $total_hours) }} Horas</b></p>
            <p><b>{{ cut($total_hours / 60) }}</b> horas + <b>{{ cut($total_manual_hours / 60) }}</b> horas cargadas manualmente</p>
            <input type="hidden" name="billed_hours" value="{{ ($total_hours + $total_manual_hours) / 60 }}">
            <p class="text-stone-500">Se paga 15%+ si supera el 85 y 30%+ con el 95%</p>
        </div>
    </section>

    <section class="card">
        <div class="card-header">Finalizar</div>
        <div class="card-body space-y-4">
            <div>
                <label class="form-label">Comentarios</label>
                <textarea class="form-input" rows="5" name="detail" spellcheck="false"></textarea>
            </div>
            <div>
                <label class="form-label">Costo por hora</label>
                <input type="text" class="form-input" name="rate" required>
            </div>
            <button class="btn btn-primary w-full" type="submit">Crear</button>
        </div>
    </section>
</form>
@else
<form action="/reports/store" method="post" class="space-y-6">
    @csrf
    <input type="hidden" name="to" value="{{ $to }}">
    <input type="hidden" name="user_id" value="{{ $user->id }}">
    <input type="hidden" name="from" value="{{ $from }}">

    @php $productivity = 0; $aproved_hours = 0; @endphp

    <section class="card">
        <div class="card-header">Tareas en desarrollo</div>
        <div class="card-body">
            @php
                $total_hours_per_task = 0;
                $total_hours_billed_per_task = 0;
                $total_hours_efforts_per_task = 0;
            @endphp
            <ol class="list-decimal space-y-3 pl-5 text-sm">
                @foreach ($tasks as $task)
                <input type="hidden" name="tasks[]" value="{{ $task->id }}">
                @php
                    $total_hours_per_task += $task->getEfforts();
                    $total_hours_billed_per_task += $task->billed;
                    $total_hours_efforts_per_task += $task->getEfforts();
                @endphp
                <li>
                    {{ $task->name }}
                    <i class="text-stone-500">Creada el {{ $task->getDate() }}</i>
                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{ $task->project->name }}</span>
                    @if ($task->estimation)
                        ({{ $task->estimation }} horas estimadas) ({{ minutesToHours($task->getEfforts()) }} hs cargadas) ({{ $task->billed }} F)
                    @else
                        ({{ $task->getEfforts() }} horas)
                    @endif
                    @if ($task->items()->count())
                        <br>Hitos
                        <ul class="list-disc pl-5">
                            @foreach ($task->items as $item)
                                <li>{{ $item->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                    @if (!$task->estimation || $task->items()->count() == 0 && ($task->efforts()->count() != 0))
                        <br>Esfuerzos
                        <ul class="list-disc pl-5">
                            @foreach ($task->efforts as $item)
                                <input type="hidden" name="efforts[]" value="{{ $item->id }}">
                                <li>{{ $item->detail }}</li>
                            @endforeach
                        </ul>
                    @endif
                </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="card">
        <div class="card-header">Horas cargadas manualmente</div>
        <div class="card-body">
            @php $total_manual = 0; @endphp
            <ol class="list-decimal space-y-2 pl-5 text-sm">
                @foreach ($efforts as $effort)
                <input type="hidden" name="efforts[]" value="{{ $effort->id }}">
                <li>
                    {{ $effort->detail }}
                    <i class="text-stone-500">[{{ $effort->getDate() }}]</i>
                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{ $effort->project->name }}</span>
                    [{{ minutesToHours($effort->amount * $effort->user->role->weight) }} Hs]
                    @php $total_manual += $effort->amount * $effort->user->role->weight @endphp
                </li>
                @endforeach
            </ol>
        </div>
        <div class="space-y-1 border-t border-stone-200 px-4 py-3 text-right text-sm">
            <p>Suma Facturadas: {{ $total_hours_billed_per_task }} hs</p>
            <p>Suma CM: {{ minutesToHours($total_manual) }} hs</p>
            <p><b>TOTAL:</b> {{ minutesToHours($total_manual + $total_hours_billed_per_task * 60) }} hs</p>
            <p>Productividad: {{ $total_hours_billed_per_task * 60 / $total_hours_per_task * 100 }}%</p>
            <input type="hidden" name="productivity" value="{{ $total_hours_billed_per_task * 60 / $total_hours_per_task * 100 }}">
        </div>
    </section>

    <section class="card">
        <div class="card-header">Finalizar</div>
        <div class="card-body space-y-4">
            <div>
                <label class="form-label">Comentarios</label>
                <textarea class="form-input" rows="5" name="detail" spellcheck="false"></textarea>
            </div>
            <div>
                <label class="form-label">Costo por hora</label>
                <input type="text" class="form-input" name="rate" required>
            </div>
            @if (!$aproved_hours)
                <input type="hidden" name="billed_hours" value="{{ ($total_hours_billed_per_task * 60 + $total_manual) / 60 }}">
            @endif
            <button class="btn btn-primary w-full" type="submit">Crear</button>
        </div>
    </section>
</form>
@endif
</div>
@endsection
