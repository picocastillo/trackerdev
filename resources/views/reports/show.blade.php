@extends('layouts.app')

@section('content')
<div class="container   mb-4">
  
  @if ($report->user->role_id!=4) {{--is developer or professional--}}
    <h2 class="h4 mt-4" >
      Desde el {{explode('-',$report->from)[2]}}/{{explode('-',$report->from)[1]}} hasta {{explode('-',$report->to)[2]}}/{{explode('-',$report->to)[1]}}
    @if (\Auth::user()->id!=$report->user_id)
        ({{$report->user->name}})
    @endif
    </h2>
    @php
        $total_ticket = 0;
        $total_manual = 0;
    @endphp

    @foreach ($by_project as $key => $project)
    <ul>
      <li>
        <b>
        {{$key}}:

        </b>
        <ul>
          @foreach ($by_project[$key] as $effort)
            <li>{{$effort['amount']}} minutos - {{$effort['detail']}} ({{$effort['date']}})
              @if ($effort['task_id'])
              [<a href="/tasks/{{$effort['task_id']}}">{{$effort['title_task']}}</a>]
              @php
                  $total_ticket+=$effort['amount'];
              @endphp
              @else
              [Cargado Manualmente]
              @php
                  $total_manual+=$effort['amount'];
                  
              @endphp

              @endif

            </li>
          @endforeach
        </ul>
       
      </li>
    </ul>
        
    @endforeach

    <small>Se paga 15%+ si supera el 85 y 30% + con el 95 *solo del tiempo cargado en tickets</small>

    <br/>
    <div class="card">
      <div class="card-body">
        Totales a liquidar:
    @php
        $total = 0;
    @endphp
    <li>
      <i>{{cuth($total_ticket/60) }}</i> Horas en tickets: <b>${{$report->rate * $total_ticket/60}}</b>
      @php
        $total += $report->rate * $total_ticket/60;
      @endphp
       @if ($report->productivity>95)
       + <b>${{$report->rate * $total_ticket/60 * 0.3}}(+ 30 %)</b>
       @php
        $total += $report->rate * $total_ticket/60 * 0.3;
      @endphp
       @endif
       @if ($report->productivity>85 && $report->productivity<96)
           + <b>${{$report->rate * $total_ticket/60 * 0.15 }} (+ 15 %)</b>
           @php
           $total += $report->rate * $total_ticket/60 * 0.15;
         @endphp
       @endif

    </li>


    <li>
      <i>{{cuth($total_manual/60) }}</i> Horas Manuales: <b> ${{$report->rate * $total_manual/60 }}   </b>

      @php
           $total += $report->rate * $total_manual/60;
          
      @endphp
    </li>

    <div class="row">
      <div class="col-12 text-right">
        <b>
          TOTAL:
          ${{$total}}
        </b>

        
      </div>
    </div>  
      </div>
    </div>

    <div class="h3">Tareas</div>
      <table class="table text-center">
        <thead>
          <tr>
            <th scope="col">
              Nombre
            </th>
            <th scope="col">Horas Estimadas</th>
            <th scope="col">Esfuerzo</th>
            <th scope="col">Productividad</th>
            <th scope="col">Proyecto</th>
          </tr>
        </thead>
        <tbody class="text-center">
            @foreach (explode(',',$report->tasks) as $id)
            @php
                $item = \App\Task::findOrFail($id)
            @endphp
                <tr >
                <th scope="row">
                  <a href="/tasks/{{$item->id}}">{{$item->getTitle()}}</a>
                </th>
                <td >
                  {{$item->estimation}}
                </td>
                <td>{{minutesToHours($item->getEfforts())}}</td>
                <td>
                  %{{number_format($item->getProductivity($report->user_id) * 100,2)}}
                </td>
                <td>{{$item->project->name}}</td>
                </tr>
            @endforeach
        </tbody>
      </table>
      <div class="row">
        <div class="col-4">
            Productividad: <b>%{{$report->productivity}}</b>
        </div>
      </div>
      <div class="h3">Tiempos Cargados</div>
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
            @foreach (explode(',',$report->efforts) as $id)
            @php
                $item = \App\Effort::findOrFail($id);
            @endphp
                <tr >
                <th scope="row">
                  {{$item->detail}}
                  @if ($item->task)
                  ({{$item->task->getTitle()}})

                  @endif
                </th>
                <td >
                  {{minutesToHours($item->amount)}}
                </td>
                <td>{{$item->project->name}}</td>
                <td>{{$item->getDate()}}</td>
                </tr>
            @endforeach
        </tbody>
      </table>
      <div class="row">
        <div class="col-4">
          Total trabajadas: <b>{{$report->billed_hours}} horas</b>
        </div>
      </div>
      <div class="text-center">
          {{$report->detail}}
      </div>
      <div class="text-right">
        Costo por hora $<b>{{$report->rate}}</b>
      </div>
  @else {{--is client--}}
      @php
          $aproved_hours = 0;
          $total_hours_per_task = 0;
      @endphp
    <h2 class="h2 text-center" >Desde el {{explode('-',$report->from)[2]}}/{{explode('-',$report->from)[1]}} hasta {{explode('-',$report->to)[2]}}/{{explode('-',$report->to)[1]}} </h2>
      <div class="my-2">
        <div class="h5">Proyectos</div>
        <div class="ml-2">
          @foreach ($report->user->projects as $item)
              <li> {{$item->name}} 
              @if ($item->getLastIteration())
                 - ultima iteración {{$item->getLastIteration()->title}}, creada el {{$item->getLastIteration()->getDate()}} a entregar el {{$item->getLastIteration()->getDelivery()}}  
                @if ($item->getLastIteration()->billed_hours)
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
          Total de horas aprobadas para el mes de trabajo:<b> {{$aproved_hours}} horas</b>
        </div>
      </div>
      <div class="my-2">
        <div class="h5">Tareas que se estuvieron desarrollando</div>
       
        <div class="ml-2">
          <ol>
            @foreach ($tasks as $task)
            @php
                if ($task->estimation)
                  $total_hours_per_task += $task->estimation;
                else
                  $total_hours_per_task += $task->getEfforts();
            @endphp
                <li> 
                  <a target="_BLANK" href="/tasks/{{$task->id}}">{{$task->name}} </a>
                  <span class="badge badge-success" >{{$task->project->name}}</span>
                  ({{$task->getDate()}})
                      ({{$task->billed}} horas)
                  @if ($task->items()->count())
                  <br/>Hitos
                    <ul>
                      @foreach ($task->items as $item)
                          {{$item->name}}
                      @endforeach
                    </ul>
                  @endif

                  @if (!$task->estimation)
                  <br/>Esfuerzos
                    <ul>
                      @foreach ($task->efforts as $item)
                          {{$item->detail}}
                      @endforeach
                    </ul>
                  @endif
                
                </li>
            @endforeach
          </ol>
          @if (isSenior())
            <p> Detalle:  </p>
            
            <p>{!! nl2br(str_replace(' ','&nbsp;',$report->detail)) !!}</p>
              
          @endif

        </div>
      </div>
      @if (isSenior())
        <div class="text-right">
          Total por tareas: {{$total_hours_per_task}}
        </div>
      @else
        <div class="text-right">
          Total de horas facturadas: {{$report->billed_hours}}
        </div>
      @endif

      
  @endif


</div>
    
    


@endsection
@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection
