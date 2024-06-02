@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
  @if(\Auth::user()->isManager() && !$invoice->expence)
  <div class="col 6">
    <form method="POST" action="/manager/invoice/paid-off">
        @csrf
        <div class="form-inline">
            <div class="form-group mb-2">
            <div class="form-group mb-2">
                <input class="form-control mx-1" type="text" name="amount" value="{{$amount}}" placeholder="Pago">
              </div>
            <div class="form-group mb-2">
                <input class="form-control"  type="hidden" name="invoice_id" value="{{$invoice->id}}">
  
              
              <button type="submit" class="btn btn-primary mb-2  mx-2">Pagar</button>
        </div>
    </form>
  </div>
  @endif
</div>

        
        <div class="row">
          <div class="col-4 text-white my-2">
            <span class="badge badge-success">Total de horas: {{$invoice->total}} </span>  
          </div>
          <div class="col-4 text-white my-2">
            @if (\Auth::user()->isDeveloper())
             @if ($invoice->expence)
              <span class="badge badge-info">PAGADO</span>          
             @else
              <span class="badge badge-warning">ADEUDADO</span>          
             @endif
            @endif 
          </div>
          <div class="col-4 text-white my-2">
             <span class="badge badge-warning">Productividad: {{number_format($invoice->productivity,2)}}% </span>          
             @if(number_format($invoice->productivity,2)>=75 && number_format($invoice->productivity,2)<95)
                <span class="badge badge-success">+15% </span>          
             @endif   
             @if(number_format($invoice->productivity,2)>=95)
                <span class="badge badge-success">+30% </span>          
             @endif   
          </div>
          <div class="col-8">
            <table class="table table-dark">
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
                  <input type="hidden" name="effort[]" value="{{$item->id}}"  />

                  <tr class="text-white">
                    <th scope="row">{{$item->amount}}</th>
                    <td><a href="/tasks/{{$item->task->id}}" >{{$item->task->getTitle()}} </a></td>
                    <td>{{$item->getDate()}}</td>
                    <td>{{$item->detail}}</td>
                    <td><span class="badge badge-secondary">{{$item->task->project->name}}</span></td>
                  </tr>
                      
                  @endforeach
                
              </tbody>
            </table>
          </div>
          <div class="col-4">
            <div class="card">
              <div class="card card-header bg-dark border-white text-white text-center h3">Detalle</div>
              <div class="card card-body bg-dark text-white">
                  {!! nl2br(str_replace(' ','&nbsp;',$invoice->detail)) !!}
              </div>
  
            </div>
          </div>
         
        </div>

        <div class="row">
          
        </div>


       
        
    </form>
</div>
    
    


@endsection
@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection
