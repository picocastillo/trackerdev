@extends('layouts.app')

@section('content')
<div class="container   mb-4">
  
  @if (isset($efforts)) {{--is developer--}}
  <form action="/reports/store" method="post">
    @csrf
    <input type="hidden" name="to" value="{{$to}}" >
    <input type="hidden" name="user_id" value="{{$user->id}}" >
    <input type="hidden" name="from" value="{{$from}}" >
    <h2 class="h2" >Para el usuario {{$user->name}} desde el {{explode('-',$from)[2]}}/{{explode('-',$from)[1]}} hasta {{explode('-',$to)[2]}}/{{explode('-',$to)[1]}}</h2>
    @php
        $productivity_f = 0;
        $productivity = 0;
        $total_hours_billed_per_task = 0;
    @endphp
    <div class="h3">Tareas</div>
      <table class="table ">
        <thead>
          <tr>
            <th scope="col">
              Nombre
            </th>
            <th scope="col">Horas Estimadas</th>
            <th scope="col">Horas Facturadas</th>
            <th scope="col">Esfuerzos</th>
            <th scope="col">Productividad (E/F)</th>
            <th scope="col">Proyecto</th>
            {{-- <th scope="col">Porcentaje</th>
            <th scope="col">Desde - Hasta</th>
            <th scope="col">Ver Detalle</th> --}}
          </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $item)
            <input type="hidden" name="tasks[]" value="{{$item->id}}" >
                <tr >
                <th scope="row">
                  <a href="/tasks/{{$item->id}}">{{$item->getTitle()}}</a>
                </th>
                <td >
                  {{$item->estimation}}
                </td>
                <td>{{$item->billed}}</td>
                <td>{{$item->getEfforts()}}</td>
                <td>
                  %{{ number_format(($item->getProductivity($user->id)) * 100,2)}}
                  @php
                      if ($item->getEfforts()!=0)
                        $productivity += ($item->estimation * 60 / $item->getEfforts()) * 100;
                         $productivity_f += $item->getProductivity2($user->id) * 100;
                  @endphp
                  /%{{ number_format(($item->getProductivity2($user->id)) * 100,2) }}

                </td>
                <td>{{$item->project->name}}</td>
                </tr>
            @endforeach
        </tbody>
      </table>
      <div class="row">
        <div class="col-4">
          @if (count($tasks))
            Productividad (E/F): <b>%{{number_format($productivity / count($tasks),2)}}</b>
            /
            <b>%{{number_format($productivity_f / count($tasks),2)}}</b>
            <input type="hidden" name="productivity" value="{{$productivity / count($tasks)}}" >
          @endif
        </div>
      </div>
      <div class="h3">Tiempo Cargado</div>
      <table class="table ">
        <thead>
          <tr>
            <th scope="col">
              Descripción
            </th>
            <th scope="col">H</th>
            <th scope="col">Proyecto</th>
            <th scope="col">Fecha</th>
          </tr>
        </thead>
        <tbody>
          @php
              $total_hours = 0;
              $total_manual_hours = 0;
          @endphp
            @foreach ($efforts as $item)
            <input type="hidden" name="efforts[]" value="{{$item->id}}" >
                <tr >
                <th scope="row">
                  {{$item->detail}}
                  @if ($item->task)
                  ({{$item->task->getTitle()}})
                  @else
                    [manual]    
                  @endif
                </th>
                <td >
                  {{$item->amount}}
                  @php
                      if ($item->task){
                        $total_hours += $item->amount;
                      }
                      else {
                        $total_manual_hours += $item->amount;
                      }
                  @endphp
                </td>
                <td>{{$item->project->name}}</td>
                <td>{{$item->getDate()}}</td>
                </tr>
            @endforeach
        </tbody>
      </table>
      <div class="row">
        <div class="col-4">
          <p>

          </p>
          <i>
            Total trabajadas:</i> <b>{{minutesToHours($total_manual_hours+$total_hours)}} Horas</b><br/>
             <b>{{cut($total_hours / 60)}}  </b>horas
              +
             <b> {{cut($total_manual_hours / 60)}}</b> horas cargadas manualmente
          <input type="hidden" name="billed_hours" value="{{($total_hours + $total_manual_hours) / 60}}" >
        </div>
      </div>
      <p>
        <small>Se paga 15%+ si supera el 85 y 30%+ con el 95%</small>
      </p>
      <button class="btn btn-success col-12" type="submit" > <b>Crear</b>  </button>
      <label>Comentarios</label>
      <textarea type="text" class="form-control my-2" rows="5" name="detail"   spellcheck="false" >
      </textarea>
      <label>Costo por hora</label>
      <input type="text" class="form-control my-2"  name="rate"   required />
  </form>
  @else {{--is client--}}
  <form action="/reports/store" method="post">
    @csrf
    <input type="hidden" name="to" value="{{$to}}" >
    <input type="hidden" name="user_id" value="{{$user->id}}" >
    <input type="hidden" name="from" value="{{$from}}" >
    <h2 class="h2 text-center" >Para el usuario {{$user->name}} desde el {{explode('-',$from)[2]}}/{{explode('-',$from)[1]}} hasta {{explode('-',$to)[2]}}/{{explode('-',$to)[1]}} </h2>
    @php
        $productivity = 0;
        $aproved_hours = 0;
    @endphp
      <div class="my-2">
        <div class="h5">Proyectos</div>
        <div class="ml-2">
          @foreach ($projects as $item)
              <li> {{$item->name}} 
              @if ($item->getLastIteration() && $item->getLastIteration()->is_active)
                 - ultima iteración {{$item->getLastIteration()->title}}, creada el {{$item->getLastIteration()->getDate()}} a entregar el {{$item->getLastIteration()->getDelivery()}}  
                @if ($item->getLastIteration()->billed_hours && $item->getLastIteration()->is_active)
                    con <b>{{$item->getLastIteration()->billed_hours}} horas aprobadas</b>
                    @php
                        $aproved_hours += $item->getLastIteration()->billed_hours;
                    @endphp
                @endif
              @else
                  (sin iteración creada)
              @endif
              </li>
          @endforeach
        </div>
        <div class="text-right">
          @if ($aproved_hours)
            Total de horas aprobadas para el mes de trabajo:<b> {{$aproved_hours}} horas</b>
            <input type="hidden" name="billed_hours" value="{{$aproved_hours}}" >
          @else
              En esta iteración se trabaja sin horas estimadas
          @endif
        </div>
      </div>
      <div class="my-2">
        <div class="h5">Tareas que se estuvieron desarrollando</div>
        @php
            $total_hours_per_task = 0;
            $total_hours_billed_per_task = 0;
            $total_hours_efforts_per_task = 0;
        @endphp
        <div class="ml-2">
          <ol>
            @foreach ($tasks as $task)
            <input type="hidden" name="tasks[]" value="{{$task->id}}" >

            @php
                if ($task->estimation){
                  $total_hours_per_task += $task->estimation;
                  $total_hours_billed_per_task += $task->billed;
                  $total_hours_efforts_per_task += $task->getEfforts();;
                }
                else
                  $total_hours_per_task += $task->getEfforts();
            @endphp
                <li> {{$task->name}} 
                  <i>Creada el {{$task->getDate()}}</i>
                  <span class="badge badge-success" >{{$task->project->name}}</span>
                  @if ($task->estimation)
                      ({{$task->estimation}} horas estimadas) ( {{$task->getEfforts()}} cargadas)( {{$task->billed}} F)
                  @else
                    ({{$task->getEfforts()}} horas)
                  @endif

                  @if ($task->items()->count())
                  <br/>Hitos
                    <ul>
                      @foreach ($task->items as $item)
                        <li>  {{$item->name}}</li>
                      @endforeach
                    </ul>
                  @endif

                  @if (!$task->estimation || $task->items()->count() == 0 && ($task->efforts()->count()!=0))
                    <br/>Esfuerzos
                    <ul>
                      @foreach ($task->efforts as $item)
                        <input type="hidden" name="efforts[]" value="{{$item->id}}" >
                        <li>  {{$item->detail}}</li>
                      @endforeach
                    </ul>
                  @endif
                
                </li>
            @endforeach
          </ol>
        </div>
      </div>
      
      <div class="text-right">
        Suma Estimaciones:  {{$total_hours_per_task}}</br>
        Suma Facturadas: {{$total_hours_billed_per_task}}</br>
        Suma Esfuerzos: {{$total_hours_efforts_per_task}}</br>
        @if ($aproved_hours)
          Facturado: {{number_format(($aproved_hours / $total_hours_efforts_per_task) * 100,2)}} %
          <input type="hidden" name="productivity" value="{{number_format(($aproved_hours / $total_hours_efforts_per_task) * 100,2)}}" >
        @else
          Facturado: {{number_format(($total_hours_billed_per_task / $total_hours_per_task) * 100,2)}} %
          <input type="hidden" name="productivity" value="{{number_format(($total_hours_billed_per_task / $total_hours_per_task) * 100,2)}}" >

        @endif

      </div>
      
      <button class="btn btn-success col-12" type="submit" > <b>Crear</b>  </button>
      <label>Comentarios</label>
      <textarea type="text" class="form-control my-2" rows="5" name="detail"   spellcheck="false" >
      </textarea>
      <label>Costo por hora</label>
      <input type="text" class="form-control my-2"  name="rate"   required />
      @if (!$aproved_hours)
        <input type="hidden" name="billed_hours" value="{{$total_hours_billed_per_task}}" >
      @endif
  </form>
  @endif


</div>
    
    


@endsection
@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection
