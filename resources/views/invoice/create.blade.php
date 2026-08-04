@extends('layouts.app')

@section('content')
<div class="page-shell">
    @include('includes.page-header', [
        'title' => 'Nueva factura',
        'subtitle' => 'Revisá esfuerzos y cerrá la liquidación',
        'breadcrumbs' => [
            ['label' => 'Facturas', 'url' => '/invoice'],
            ['label' => 'Crear', 'url' => null],
        ],
    ])

    <form method="POST" action="/manager/invoice" class="space-y-6">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <input type="hidden" name="from" value="{{ $from }}">
        <input type="hidden" name="to" value="{{ $to }}">
        <input type="hidden" name="productivity" value="{{ $sum }}">
        <input type="hidden" name="total" value="{{ $total }}">

        <dl class="grid grid-cols-2 gap-3 sm:grid-cols-2">
            <div class="task-stat">
                <dt>Total de horas</dt>
                <dd>{{ $total }}</dd>
            </div>
            <div class="task-stat">
                <dt>Productividad</dt>
                <dd>{{ number_format($sum, 2) }}%</dd>
            </div>
        </dl>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <section class="card lg:col-span-8">
                <div class="card-header">Esfuerzos</div>
                <div class="card-body overflow-x-auto p-0">
                    <table class="table-app">
                        <thead>
                            <tr>
                                <th scope="col">HS</th>
                                <th scope="col">Tarea</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Proyecto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($efforts as $item)
                            <input type="hidden" name="efforts[]" value="{{ $item->id }}">
                            <tr>
                                <th scope="row">{{ $item->amount }}</th>
                                <td><a href="/tasks/{{ $item->task->id }}">{{ $item->task->getTitle() }}</a></td>
                                <td>{{ $item->getDate() }}</td>
                                <td>{{ $item->detail }}</td>
                                <td>
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                                        {{ $item->task->project->name }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <aside class="space-y-6 lg:col-span-4">
                <section class="card">
                    <div class="card-header">Por tarea</div>
                    <div class="card-body overflow-x-auto p-0">
                        <table class="table-app">
                            <thead>
                                <tr>
                                    <th scope="col">task_id</th>
                                    <th scope="col">total</th>
                                    <th scope="col">estimación</th>
                                    <th scope="col">productividad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($total_by_tasks as $item)
                                <tr>
                                    <th scope="row">{{ $item->task_id }}</th>
                                    <td>{{ $item->total }}</td>
                                    <td>{{ $item->estimation }}</td>
                                    <td>{{ number_format($item->productivity * 100, 2) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="card">
                    <div class="card-header">Por proyecto</div>
                    <div class="card-body overflow-x-auto p-0">
                        <table class="table-app">
                            <thead>
                                <tr>
                                    <th scope="col">Proyecto</th>
                                    <th scope="col">Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($percentages)
                                    @foreach ($percentages as $key3 => $e)
                                        @if ($e)
                                            <tr>
                                                <td>{{ $name_project[$e->project_id] }}</td>
                                                <td>{{ round($e->total / $total, 2) * 100 }} %</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr><td colspan="2" class="text-center text-stone-500">Aún no hay tiempos</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="card">
                    <div class="card-header">Cierre</div>
                    <div class="card-body space-y-3">
                        <input type="number" required name="rate" class="form-input" placeholder="Precio hora">
                        <button type="submit" class="btn btn-primary w-full">Terminar</button>
                    </div>
                </section>
            </aside>
        </div>

        <section class="card">
            <div class="card-header">Detalle / evaluación</div>
            <div class="card-body">
                <textarea name="detail" rows="15" class="form-input font-mono text-sm">
              <b>Puntos a mejorar: </b>
                <li></li>


              <b>Puntos mejorados: </b>
                <li></li>



              - Comunicasión: 1/10
              - Capacidad de análisis: 1/10
              - Autonomia: 1/10
              - capacidad de resolución: 1/10

              <i>Observaciones: </i>
                </textarea>
            </div>
        </section>
    </form>
</div>
@endsection
