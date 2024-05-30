@extends('layouts.app')

@section('content')
<div class="mx-2">

    <div class="h2">{{$project->name}}</div>

    @foreach ($project->iterations()->orderby('id','desc')->get() as $key => $iteration)
        @if (!$key)
            <h2 class="my-2" >Iteración Actual</h2>
        @endif
        @if ($key==1)
            <h2 class="my-2" >Iteraciónes Pasadas</h2>
        @endif
            <div class="card">
                <div class="card-header p-2">
                    
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Horas Facturadas</th>
                            <th scope="col">Objetivos</th>
                            <th scope="col">Entrega</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">{{$iteration->title}}</th>
                            <td>{{$iteration->getBilledHours()}}</td>
                            <td>
                                <ol>
                                    @foreach (json_decode($iteration->objetives) as $item)
                                        <li>{{$item}}</li>                                    
                                    @endforeach
                                </ol>
                            </td>
                            <td>
                                
                                @if ($iteration->is_active)
                                    {{$iteration->getDelivery()}}
                                @else
                                    <s>{{$iteration->getDelivery()}}</s>
                                    <div class="badge badge-success">
                                        <i class="fa fa-check" ></i> Entregada
                                    </div>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-body p-2">
                    <b>Tareas de la iteración:</b>
                    <ul>
                        @foreach ($iteration->tasks as $task)
                            <li>
                                <b>{{$task->name}}</b> ({{$task->billed}} hs)
                                @if (isManager())
                                    ({{$task->getEfforts()}} ejecutadas)
                                    <a href="/tasks/{{$task->id}}">[ver]</a>
                                @endif
                                @if ($task->isFather())
                                    </br>
                                    <small>Tareas hijas
                                        <ul>
                                            @foreach ($task->tasks as $item)
                                                <li><b>{{$item->name}}</b> ({{$item->billed}} hs)</li>
                                            @endforeach
                                        </ul>
                                    </small>
                                    
                                @endif
                            
                            </li>
                            @if ($task->items->count()>0)
                                Hitos:
                                <ul>
                                    @foreach ($task->items as $item)
                                        <li>{{$item->name}}</li>
                                    @endforeach
                                </ul>
                            @endif
                            @if ($task->efforts()->count()>0)
                                Se trabajó en:
                                <ul>
                                    @foreach ($task->efforts as $effort)
                                        <li>{{$effort->detail}}</li>
                                    @endforeach
                                </ul>
                            @endif


                        @endforeach

                    </ul>
                </div>
            </div>
    @endforeach

    

    
</div>
    
    


@endsection
@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection
