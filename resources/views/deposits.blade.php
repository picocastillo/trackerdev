@extends('layouts.app')

@section('content')
<div class="container">
    @include('includes.errors')
    @include('includes.messages')
    <div class="row">
        <div class="col-sm-6  col-12 py-2">
            <div class="card">
                <div class="card-header bg-success border-success">
                    Información de pagos
                </div>
                <div class="card-body bg-dark text-white">
                    <div class="my_card_client">
                        <table class="table text-white">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Depósito</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Horas</th>
                                </tr>
                            </thead>
                            <tbody class="text-white">
                                @if ($deposits)
                                    @foreach ($deposits as $key3 => $deposit)
                                        @if (is_array($deposit))
                                            <tr>
                                                <th scope="row"> {{($key3)+1}} </th>
                                                <td>$ {{$deposit['amount']}} </td>
                                                <td> {{$deposit['date']}}  </td>
                                                <td>{{$deposit['hours']}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="text-center">Aun no hay depositos</div>
                                @endif
                            </tbody>
                        </table>

                    </div>
                    <p class="text-left"><i>Horas saldadas <strong> {{$deposits['hours_paid']}} </strong> </i> </p>
                    <p class="text-center"><i>Horas adeudadas <strong> {{ $projects['total_hours'] - $deposits['hours_paid']}} </strong> </i> </p>
                    <p class="text-right"><i>Horas facturadas <strong> {{$projects['total_hours']}} </strong></i> </p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-12 ">
            @if (canShowTimes())
            <div class="card">
                <div class="card-header bg-success border-success">
                    Detalle de horas
                <div class="card my-2">
                    <form class="form-inline bg-success border-white" action="/deposits" method="GET">
                        {{ csrf_field() }}
                            <input class="form-control mx-1" type="date" name="start_date" value="{{old('start_date') ?? $start_date}}">
                            <input class="form-control"  type="date" name="end_date" value="{{old('end_date') ?? $end_date}}">
                          <input class="btn btn-outline-warning my-2 ml-2" type="submit" value="Filtrar">
                      </form>
                </div>
                <div class="card-body bg-dark text-white">
                    <div class="my_card_client">
                        <table class="table text-white">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Detalle</th>
                                <th scope="col">Horas</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Tarea</th>
                                </tr>
                            </thead>
                            <tbody class="text-white">
                                @if ($efforts)
                                    @foreach ($efforts as $key3 => $e)
                                        <tr>
                                            <th scope="row"> {{($key3)+1}} </th>
                                            <td>{{$e->detail}} </td>
                                            <td> {{$e->amount}}  </td>
                                            <td>{{ date("d/m",strtotime($e->date))}}</td>
                                            <td><a class="btn btn-success" target="_blank" href="/tasks/{{$e->task_id}}">Ver</a></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <div class="text-center">Aun no hay tiempos cargados</div>
                                @endif
                            </tbody>
                        </table>

                    </div>
                    <div class="text-right my-3 ol-sm-12 col-12">
                        <span class="badge badge-success col-12">Desde el {{$start_date}} hasta el {{$end_date}} hay {{$total}} h.</span>
                    </div>
                </div>
            </div>
            @endif
        </div>


    </div>
</div>
    
    


@endsection
@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection
