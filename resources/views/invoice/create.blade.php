@extends('layouts.app')

@section('content')
<div class="container">
    <form method="POST" action="/manager/invoice">
        @csrf
        <input type="hidden" name="user_id" value="{{$user->id}}"  />
        <input type="hidden" name="from" value="{{$from}}"  />
        <input type="hidden" name="to" value="{{$to}}"  />
        <input type="hidden" name="productivity" value="{{$sum}}"  />
        <input type="hidden" name="total" value="{{$total}}"  />
        
        <div class="row">
          <div class="col-6 text-white my-2">
            <span class="badge badge-success">Total de horas: {{$total}} </span>  
          </div>
          <div class="col-6 text-white my-2">
             <span class="badge badge-warning">Productividad: {{number_format($sum,2)}}%</span>          
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
                  <input type="hidden" name="efforts[]" value="{{$item->id}}"  />

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
            <table class="table table-dark">
              <thead>
                <tr>
                  <th scope="col">task_id</th>
                  <th scope="col">total</th>
                  <th scope="col">estimacion</th>
                  <th scope="col">productividad</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($total_by_tasks as $item)
                  <tr class="text-white">
                    <th scope="row">{{$item->task_id}}</th>
                    <td>{{$item->total}}</a></td>
                    <td>{{$item->estimation}}</td>
                    <td>{{number_format($item->productivity * 100,2)}}%</td>
                  </tr>
                      
                  @endforeach
                
              </tbody>
            </table>

            <div class="card-body bg-dark text-white">
              <div class="my_card_client">
                  <table class="table text-white">
                      <thead>
                          <tr>
                          <th scope="col">proyecto</th>
                          <th scope="col">Porcentaje</th>
                          </tr>
                      </thead>
                      <tbody class="text-white">
                          @if ($percentages)
                              @foreach ($percentages as $key3 => $e)
                                  @if (($e))
                                      <tr>
                                          <td>{{$name_project[$e->project_id]}} </td>
                                          <td>{{ round($e->total / $total,2) * 100}} %</td>
                                      </tr>
                                  @endif
                              @endforeach
                          @else
                              <div class="text-center">Aun no hay tiempos</div>
                          @endif
                      </tbody>
                  </table>
              </div>
            </div>
            <input type="number" required name="rate" class="form-control my-2"  placeholder="Precio hora" />

            <div class="form-group row  my-2">
              <div class="col-12">
                  <button type="submit" class="btn btn-primary">
                      Terminar
                  </button>
              </div>
          </div>
          </div>
        </div>

        <div class="row">
          <div class="col-4">
            <textarea name="detail" cols="100" rows="15" class="form-control">
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
        </div>


       
        
    </form>
</div>
    
    


@endsection
@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection
