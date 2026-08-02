@extends('layouts.app')

@section('content')
<div class="mb-4">

@if ($report->user->role_id!=4)
    <h2 class="mt-4 text-lg font-semibold">
        Desde el {{explode('-',$report->from)[2]}}/{{explode('-',$report->from)[1]}} hasta {{explode('-',$report->to)[2]}}/{{explode('-',$report->to)[1]}}
        @if (\Auth::user()->id!=$report->user_id)
            ({{$report->user->name}})
        @endif
    </h2>
    @php
        $total_ticket = 0;
        $total_manual = 0;
    @endphp

    @foreach ($by_project as $key => $project)
        <ul class="my-2 list-disc pl-5">
            <li>
                <b>{{$key}}:</b>
                <ul class="list-disc pl-5">
                    @foreach ($by_project[$key] as $effort)
                        <li>{{$effort['amount']}} minutos - {{$effort['detail']}} ({{$effort['date']}})
                            @if ($effort['task_id'])
                                [<a href="/tasks/{{$effort['task_id']}}">{{$effort['title_task']}}</a>]
                                @php $total_ticket+=$effort['amount']; @endphp
                            @else
                                [Cargado Manualmente]
                                @php $total_manual+=$effort['amount']; @endphp
                            @endif
                        </li>
                    @endforeach
                </ul>
            </li>
        </ul>
    @endforeach

    <small>Se paga 15%+ si supera el 85 y 30% + con el 95 *solo del tiempo cargado en tickets</small>
    <br>

    <div class="card my-4">
        <div class="card-body">
            Totales a liquidar:
            @php $total = 0; @endphp
            <ul class="list-disc space-y-2 pl-5">
                <li>
                    <i>{{cuth($total_ticket/60) }}</i> Horas en tickets: <b>${{$report->rate * $total_ticket/60}}</b>
                    @php $total += $report->rate * $total_ticket/60; @endphp
                    @if ($report->productivity>95)
                        + <b>${{$report->rate * $total_ticket/60 * 0.3}}(+ 30 %)</b>
                        @php $total += $report->rate * $total_ticket/60 * 0.3; @endphp
                    @endif
                    @if ($report->productivity>85 && $report->productivity<96)
                        + <b>${{$report->rate * $total_ticket/60 * 0.15 }} (+ 15 %)</b>
                        @php $total += $report->rate * $total_ticket/60 * 0.15; @endphp
                    @endif
                </li>
                <li>
                    <i>{{cuth($total_manual/60) }}</i> Horas Manuales: <b>${{$report->rate * $total_manual/60 }}</b>
                    @php $total += $report->rate * $total_manual/60; @endphp
                </li>
            </ul>
            <div class="mt-4 text-right">
                <b>TOTAL: ${{$total}}</b>
            </div>
        </div>
    </div>

    <div class="my-4 text-center">
        <button type="button" class="btn btn-primary" onclick="document.getElementById('collapseExample').classList.toggle('hidden')">
            Ver Mas Detalles
        </button>
    </div>

    <div id="collapseExample" class="hidden">
        <div class="card">
            <div class="card-body">
                <div class="mb-4 text-xl font-semibold">Tareas</div>
                <div class="overflow-x-auto">
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
                            @foreach (explode(',',$report->tasks) as $id)
                                @php $item = App\Models\Task::findOrFail($id) @endphp
                                <tr>
                                    <th scope="row"><a href="/tasks/{{$item->id}}">{{$item->getTitle()}}</a></th>
                                    <td>{{$item->estimation}}</td>
                                    <td>{{minutesToHours($item->getEfforts())}}</td>
                                    <td>%{{number_format($item->getProductivity($report->user_id) * 100,2)}}</td>
                                    <td>{{$item->project->name}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">Productividad: <b>%{{$report->productivity}}</b></div>

                <div class="mb-4 mt-6 text-xl font-semibold">Tiempos Cargados</div>
                <div class="overflow-x-auto">
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
                            @foreach (explode(',',$report->efforts) as $id)
                                @php $item = App\Models\Effort::findOrFail($id); @endphp
                                <tr>
                                    <th scope="row">
                                        {{$item->detail}}
                                        @if ($item->task)
                                            ({{$item->task->getTitle()}})
                                        @endif
                                    </th>
                                    <td>{{minutesToHours($item->amount)}}</td>
                                    <td>{{$item->project->name}}</td>
                                    <td>{{$item->getDate()}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">Total trabajadas: <b>{{$report->billed_hours}} horas</b></div>
                <div class="mt-4 text-center">{{$report->detail}}</div>
                <div class="mt-4 text-right">Costo por hora $<b>{{$report->rate}}</b></div>
            </div>
        </div>
    </div>

@else
    @php
        $aproved_hours = 0;
        $total_hours_per_task = 0;
    @endphp
    <h2 class="text-center text-2xl font-bold">Desde el {{explode('-',$report->from)[2]}}/{{explode('-',$report->from)[1]}} hasta {{explode('-',$report->to)[2]}}/{{explode('-',$report->to)[1]}}</h2>
    <div class="my-2">
        <div class="text-lg font-semibold">Proyectos</div>
        <div class="ml-2"></div>
        <div class="text-right"></div>
    </div>
    <div class="my-2">
        <div class="text-lg font-semibold">Tareas que se estuvieron desarrollando</div>
        <div class="ml-2">
            <ol class="list-decimal space-y-2 pl-5">
                @foreach ($tasks as $task)
                    @php
                        if ($task->estimation)
                            $total_hours_per_task += $task->estimation;
                        else
                            $total_hours_per_task += $task->getEfforts();
                    @endphp
                    <li>
                        <a target="_BLANK" href="/tasks/{{$task->id}}">{{$task->name}}</a>
                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{$task->project->name}}</span>
                        ({{$task->getDate()}})
                        ({{$task->billed}} horas)
                        @if ($task->items()->count())
                            <br>Hitos
                            <ul class="list-disc pl-5">
                                @foreach ($task->items as $item)
                                    {{$item->name}}
                                @endforeach
                            </ul>
                        @endif
                        @if (!$task->estimation)
                            <br>Esfuerzos
                            <ul class="list-disc pl-5">
                                @foreach ($task->efforts as $item)
                                    {{$item->detail}}
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </div>

    @foreach ($efforts as $effort)
        <input type="hidden" name="efforts[]" value="{{$effort->id}}">
        <li>
            {{$effort->detail}}
            <i>[{{$effort->getDate()}}]</i>
            <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{$effort->project->name}}</span>
            [{{minutesToHours($effort->amount * $effort->user->role->weight)}} Hs]
        </li>
    @endforeach
    <div class="text-right">
        Total de horas facturadas: {{$report->billed_hours}}
    </div>
@endif

</div>
@endsection
