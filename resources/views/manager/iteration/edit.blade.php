@extends('layouts.app')

@section('content')
@include('includes.errors')
@include('includes.messages')

<div class="page-shell">
    @include('includes.page-header', [
        'title' => 'Editar iteración',
        'subtitle' => 'Última iteración de '.$iteration->project->name,
        'breadcrumbs' => [
            ['label' => 'Proyectos', 'url' => '/project'],
            ['label' => $iteration->project->name, 'url' => null],
            ['label' => 'Editar iteración', 'url' => null],
        ],
    ])

    <section class="card">
        <div class="card-body">
            <div
                id="create_iteration"
                title="{{ $iteration->title }}"
                billedHours="{{ $iteration->billed_hours }}"
                time="{{ $time }}"
                objetives="{{ $objetives }}"
                tasks="{{ json_encode($tasks->toArray()) }}"
                token="{{ json_encode(\Session::token()) }}"
            ></div>
        </div>
    </section>
</div>
@endsection
