@extends('layouts.app')

@section('content')
@include('includes.errors')
@include('includes.messages')

<div class="page-shell">
    @include('includes.page-header', [
        'title' => 'Nueva iteración',
        'subtitle' => 'Para '.$project->name.($project->getLastIteration() ? ' · Última: '.$project->getLastIteration()->title : ''),
        'breadcrumbs' => [
            ['label' => 'Proyectos', 'url' => '/project'],
            ['label' => $project->name, 'url' => null],
            ['label' => 'Nueva iteración', 'url' => null],
        ],
    ])

    <section class="card">
        <div class="card-body">
            <div id="create_iteration" project_id="{{ $project->id }}" token="{{ json_encode(\Session::token()) }}"></div>
        </div>
    </section>
</div>
@endsection
