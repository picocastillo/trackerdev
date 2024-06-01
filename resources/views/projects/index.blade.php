@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row my-2">
        <div class="col-2 offset-10">
            <a class="btn btn-primary" href="/project/create">Nuevo Proyecto</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Equipo</th>
                    <th scope="col">Horas Ejecutadas/Horas Aprobadas</th>
                   
                  </tr>
                </thead>
                <tbody>
                  @foreach($projects as $p)
                    <tr>
                        <th scope="row">
                            {{$p->name}}
                           {{--  <a href="project/{{$p->id}}">[ver]</a> --}}
                            <a href="project/{{$p->id}}/edit">[editar]</a>
                        </th>
                        <td>
                            @foreach ($p->users as $item)
                                <span class="badge badge-success" > {{$item->name}} ( {{$item->role->seniority}} ) </span>
                            @endforeach
                        </td>
                        {{-- <td>{{($p->getLastIteration()) ? $p->getLastIteration()->billed_hours : '-'}}</td> --}}
                        <td>{{minutesToHours($p->getEffortsByProject())}}/{{$p->getHoursByProject()}} Horas</td>
                        <td>
                           {{--  @if ($p->getLastIteration())
                                {{$p->getLastIteration()->getEHoursOfTask()}} / {{$p->getLastIteration()->getFHoursOfTask()}} /
                                <div class="badge badge-success">
                                    @if ($p->getLastIteration())
                                        
                                    {{ $p->getLastIteration()->billed_hours - $p->getLastIteration()->getFHoursOfTask() }}
                                    @endif
                                </div>
                            @else
                                -
                            @endif --}}
                        </td>
                        <td class="row" >
                            {{-- @if ($p->iterations()->count())
                                <div class="col-sm-3">
                                    <a title="" class="tip-top edit" href="/iteration/{{$p->id}}/edit" data-original-title="Editar"><i class="fa fa-edit"></i></a>
                                </div>
                            @endif
                            <div class="col-sm-3">
                                <a title="" class="tip-top edit" href="/iteration/{{$p->id}}" data-original-title="Editar"><i class="fa fa-plus"></i></a>
                            </div> --}}
                        </td>
                      </tr>
                  @endforeach
                </tbody>
              </table>
        </div>
    </div>

    
</div>
    
    


@endsection
@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection
