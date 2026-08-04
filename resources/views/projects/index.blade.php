@extends('layouts.app')

@section('content')
    <div class="page-shell">
        @include('includes.page-header', [
            'title' => 'Proyectos',
            'subtitle' => 'Equipos y horas por proyecto',
            'breadcrumbs' => [
                ['label' => 'Inicio', 'url' => '/home'],
                ['label' => 'Proyectos', 'url' => null],
            ],
            'actions' => '<a class="btn btn-primary btn-sm" href="/project/create"><i class="fa fa-plus mr-1.5"></i> Nuevo Proyecto</a>',
        ])

        <section class="card">
            <div class="card-body overflow-x-auto p-0">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Equipo</th>
                            <th scope="col">Horas Ejecutadas / Horas Aprobadas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $p)
                            <tr>
                                <th scope="row">
                                    {{ $p->name }}
                                    <a href="/project/{{ $p->id }}/edit" class="ml-1 text-sm">[editar]</a>
                                </th>
                                <td>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($p->users as $item)
                                            <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                                                {{ $item->name }} ({{ $item->role->seniority }})
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ minutesToHours($p->getEffortsByProject()) }}/{{ $p->getHoursByProject() }} Horas</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
