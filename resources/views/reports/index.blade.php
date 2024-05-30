@extends('layouts.app')

@section('content')
@include('includes.errors')
@include('includes.messages')
<div class="container">
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
