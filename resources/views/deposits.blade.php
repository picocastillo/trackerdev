@extends('layouts.app')

@section('content')
    @include('includes.errors')
    @include('includes.messages')

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="py-2">
            <div class="card overflow-hidden">
                <div class="card-header bg-emerald-600 text-white">Información de pagos</div>
                <div class="card-body bg-stone-900 text-white">
                    <div class="overflow-x-auto">
                        <table class="table-app text-white">
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
                                                <th scope="row">{{($key3)+1}}</th>
                                                <td>$ {{$deposit['amount']}}</td>
                                                <td>{{$deposit['date']}}</td>
                                                <td>{{$deposit['hours']}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr><td colspan="4" class="text-center">Aun no hay depositos</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <p class="text-left"><i>Horas saldadas <strong>{{$deposits['hours_paid']}}</strong></i></p>
                    <p class="text-center"><i>Horas adeudadas <strong>{{ $projects['total_hours'] - $deposits['hours_paid']}}</strong></i></p>
                    <p class="text-right"><i>Horas facturadas <strong>{{$projects['total_hours']}}</strong></i></p>
                </div>
            </div>
        </div>

        <div>
            @if (canShowTimes())
            <div class="card overflow-hidden">
                <div class="card-header bg-emerald-600 text-white">
                    Detalle de horas
                    <div class="card my-2 border-white/20">
                        <form class="flex flex-wrap items-center gap-2 bg-emerald-600 p-2" action="/deposits" method="GET">
                            {{ csrf_field() }}
                            <input class="form-input w-auto" type="date" name="start_date" value="{{old('start_date') ?? $start_date}}">
                            <input class="form-input w-auto" type="date" name="end_date" value="{{old('end_date') ?? $end_date}}">
                            <input class="btn btn-outline border-white text-white hover:bg-white hover:text-emerald-700" type="submit" value="Filtrar">
                        </form>
                    </div>
                </div>
                <div class="card-body bg-stone-900 text-white">
                    <div class="overflow-x-auto">
                        <table class="table-app text-white">
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
                                            <th scope="row">{{($key3)+1}}</th>
                                            <td>{{$e->detail}}</td>
                                            <td>{{$e->amount}}</td>
                                            <td>{{ date("d/m",strtotime($e->date))}}</td>
                                            <td><a class="btn btn-sm bg-emerald-600 text-white hover:bg-emerald-700" target="_blank" href="/tasks/{{$e->task_id}}">Ver</a></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="5" class="text-center">Aun no hay tiempos cargados</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="my-3 text-right">
                        <span class="inline-flex w-full justify-center rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-800">Desde el {{$start_date}} hasta el {{$end_date}} hay {{$total}} h.</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
