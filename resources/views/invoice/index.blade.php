@extends('layouts.app')

@section('content')
<div class="container">
    @if(\Auth::user()->isManager())
    <form method="GET" action="manager/invoice/new">
        @csrf
       
        <div class="form-inline">
            <div class="form-group mb-2">
                <label for="dev" class="text-white mx-2" >Nueva factura para </label>
                    <select name="user_id" class="form-control" >
                        <option selected value={{0}} > Sin Definir </option>
                        @foreach ($devs as $d)
                            <option value={{$d->id}} > {{$d->name}} </option>
                        @endforeach
                    </select>
              </div>
            <div class="form-group mb-2">
                <input class="form-control mx-1" type="date" name="start_date" value="{{old('start_date') ?? $start_date}}">
              </div>
            <div class="form-group mb-2">
                <input class="form-control"  type="date" name="end_date" value="{{old('end_date') ?? $end_date}}">

              
              <button type="submit" class="btn btn-primary mb-2  mx-2">Nueva factura</button>
        </div>
        
    </form>
    @endif

    <table class="table table-dark">
        <thead>
          <tr>
            <th scope="col">Total de horas</th>
            <th scope="col">Productividad</th>
            <th scope="col">Fechas</th>
            <th scope="col">Detalle</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $item)
            <input type="hidden" name="effort[]" value="{{$item->id}}"  />

            <tr class="text-white">
              <th scope="row">{{$item->total}}</th>
              <td>{{$item->productivity}} </a></td>
              <td>{{$item->getDate()}}</td>
              <td><a class="btn btn-success" href="/invoice/{{$item->id}}" >Ver</a></td>
            </tr>
                
            @endforeach
          
        </tbody>
      </table>


</div>
    
    


@endsection
@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection
