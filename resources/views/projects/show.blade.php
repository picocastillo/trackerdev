@extends('layouts.app')

@section('content')
    <div class="page-shell">
        @include('includes.page-header', [
            'title' => $project->name,
            'subtitle' => 'Detalle del proyecto',
            'breadcrumbs' => [
                ['label' => 'Proyectos', 'url' => '/project'],
                ['label' => $project->name, 'url' => null],
            ],
            'actions' => '<a class="btn btn-outline btn-sm" href="/project/'.$project->id.'/edit"><i class="fa fa-edit mr-1.5"></i> Editar</a>',
        ])

        <section class="card">
            <div class="card-header">Proyecto</div>
            <div class="card-body text-sm text-stone-600">
                <p>Vista de detalle de <span class="font-semibold text-stone-900">{{ $project->name }}</span>.</p>
            </div>
        </section>
    </div>
@endsection
