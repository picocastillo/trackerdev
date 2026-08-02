@extends('layouts.app')

@section('content')
    <div class="my-2 flex justify-end">
        <a class="btn btn-primary" href="/project/create">Nuevo Proyecto</a>
    </div>

    <div class="overflow-x-auto">
        <table class="table-app">
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
                            <a href="project/{{$p->id}}/edit">[editar]</a>
                        </th>
                        <td>
                            @foreach ($p->users as $item)
                                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{$item->name}} ( {{$item->role->seniority}} )</span>
                            @endforeach
                        </td>
                        <td>{{minutesToHours($p->getEffortsByProject())}}/{{$p->getHoursByProject()}} Horas</td>
                        <td class="flex gap-2"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
