@extends('layouts.app')

@section('content')
@include('includes.errors')
@include('includes.messages')
<div class="container">

@if (isClient())
    <h3>Tareas actuales</h3>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Tarea</th>
            <th scope="col">Estimacion</th>
            <th scope="col">Progreso</th>
            <th scope="col">Fecha</th>

        </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach($tasks as $task)
                <tr>
                    <td>
                        <a href="tasks/{{$task->id}}">{{$task->getTitle()}}</a>
                    </td>
                    <td>
                        {{$task->billed}} Horas
                    </td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: {{$task->getPercentage()}}%" aria-valuenow="{{round($task->getPercentage(),2)}}" aria-valuemin="0" aria-valuemax="100">{{round($task->getPercentage(),2)}}%</div>
                          </div>
                    </td>
                    <td>
                        {{$task->getDate()}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@else
    <div class="card">
    <div class="card-body">
        <h5>Horas Sin Facturar</h5> 
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Detalle</th>
            <th scope="col">Tarea</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Fecha</th>

        </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
        @foreach($efforts as $effort)
            <tr>
                
                <td>
                    {{$effort->detail}}
                    (<a href="tasks/{{$effort->task_id}}">{{$effort->task->getTitle()}}</a>)
                    
                </td>
                <td >
                    @if ($effort->project)
                    <span class="badge badge-info"> {{$effort->project->name}}</div>
                        
                    @else
                    <a href="tasks/{{$effort->task_id}}">{{$effort->task->getTitle()}}</a>
                    <span class="badge badge-info">{{$effort->task->project->name}}</span>&nbsp;
                        
                    @endif

                </td>
                <td>
                    {{$effort->amount}} minutos
                    @php
                        $total = $total + $effort->amount;
                    @endphp
                </td>
                <td>
                    {{$effort->getDate()}}
                </td>
                
                
            </tr>
        @endforeach
        </tbody>
    </table>


    <div class="row">
        <div class="col-12 text-right">
            <b>TOTAL </b>{{floor($total / 60).':'.$total%60}} Horas
        {{--   TOTAL {{number_format($total/60, 2) }} Horas --}}
        </div>
    </div>
    </div>
    </div>
@endif





<div class="row mt-2">
    <h2>->Reportes</h2>

</div>

    @if (isSenior())
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header ">
                        Reporte para
                    <div class="card my-2">
                        <form class="form-inline  border-white" action="/reports" method="POST">
                            {{ csrf_field() }}
                                <select name="user_id" class="form-control ml-1">
                                    @foreach ($users as $key => $item)
                                        <option value="{{$item->id}}" >{{$item->name}}</option>
                                    @endforeach
                                </select>
                                <input class="form-control mx-1" type="date" name="start_date" value="{{old('start_date') ?? $start_date}}">
                                <input class="form-control"  type="date" name="end_date" value="{{old('end_date') ?? $end_date}}">
                            <input class="btn btn-outline-primary my-2 ml-2" type="submit" value="Crear">
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    @endif

    <div class="col-12  mt-4">
        <table class="table">
            <thead>
              <tr>
                @if (isSenior())
                    <th scope="col">Usuario</th>
                    <th scope="col">
                        HF
                    </th>
                @endif
                <th scope="col">productividad</th>
                <th scope="col">costo hora</th>
                <th scope="col">Total de horas</th>
                <th scope="col">desde</th>
                <th scope="col">hasta</th>
              </tr>
            </thead>
            <tbody>
              @foreach($reports as $report)
                <tr>
                    @if (isSenior())
                        <th scope="row">
                            <a href="/reports/{{$report->id}}" >
                                {{$report->user->name}}
                            </a>
                        </th>
                        <td>
                            {{$report->billed_hours}}
                        </td>
                    @endif
                    <td>
                        {{$report->productivity}} <a href="reports/{{$report->id}}">[ver]</a>
                    </td>
                    <td>
                        {{$report->rate}}
                    </td>
                    <td>
                        {{$report->billed_hours}}
                    </td>
                    <td>
                        {{$report->from}}
                    </td>
                    <td >
                        {{$report->to}}
                    </td>
                    
                  </tr>
              @endforeach
            </tbody>
          </table>
          {{$reports->links()}}
    </div>

</div>
    
     

@endsection
@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection
