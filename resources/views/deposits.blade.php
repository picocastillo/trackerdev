@extends('layouts.app')

@section('content')
    @include('includes.errors')
    @include('includes.messages')

    <div class="page-shell">
        @include('includes.page-header', [
            'title' => 'Depósitos y tiempos',
            'subtitle' => 'Pagos y detalle de horas',
            'breadcrumbs' => [
                ['label' => 'Inicio', 'url' => '/home'],
                ['label' => 'Depósitos', 'url' => null],
            ],
        ])

        <dl class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="task-stat">
                <dt>Horas saldadas</dt>
                <dd>{{ $deposits['hours_paid'] }}</dd>
            </div>
            <div class="task-stat">
                <dt>Horas adeudadas</dt>
                <dd>{{ $projects['total_hours'] - $deposits['hours_paid'] }}</dd>
            </div>
            <div class="task-stat">
                <dt>Horas facturadas</dt>
                <dd>{{ $projects['total_hours'] }}</dd>
            </div>
        </dl>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <section class="card">
                <div class="card-header">Información de pagos</div>
                <div class="card-body overflow-x-auto p-0">
                    <table class="table-app">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Depósito</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Horas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($deposits)
                                @foreach ($deposits as $key3 => $deposit)
                                    @if (is_array($deposit))
                                        <tr>
                                            <th scope="row">{{ ($key3) + 1 }}</th>
                                            <td>$ {{ $deposit['amount'] }}</td>
                                            <td>{{ $deposit['date'] }}</td>
                                            <td>{{ $deposit['hours'] }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr><td colspan="4" class="text-center text-stone-500">Aún no hay depósitos</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>

            @if (canShowTimes())
                <section class="card">
                    <div class="card-header flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <span>Detalle de horas</span>
                        <form class="page-toolbar" action="/deposits" method="GET">
                            {{ csrf_field() }}
                            <input class="form-input w-auto" type="date" name="start_date" value="{{ old('start_date') ?? $start_date }}">
                            <input class="form-input w-auto" type="date" name="end_date" value="{{ old('end_date') ?? $end_date }}">
                            <button class="btn btn-outline btn-sm" type="submit">Filtrar</button>
                        </form>
                    </div>
                    <div class="card-body overflow-x-auto p-0">
                        <table class="table-app">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Detalle</th>
                                    <th scope="col">Horas</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Tarea</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($efforts)
                                    @foreach ($efforts as $key3 => $e)
                                        <tr>
                                            <th scope="row">{{ ($key3) + 1 }}</th>
                                            <td>{{ $e->detail }}</td>
                                            <td>{{ $e->amount }}</td>
                                            <td>{{ date('d/m', strtotime($e->date)) }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary" target="_blank" href="/tasks/{{ $e->task_id }}">Ver</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="5" class="text-center text-stone-500">Aún no hay tiempos cargados</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-stone-200 px-4 py-3 text-center">
                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-800">
                            Desde el {{ $start_date }} hasta el {{ $end_date }} hay {{ $total }} h.
                        </span>
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
