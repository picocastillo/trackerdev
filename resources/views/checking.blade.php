@extends('layouts.app')

@section('content')
<div class="page-shell">
    @include('includes.page-header', [
        'title' => 'Checking / Testing',
        'subtitle' => 'Checklist antes de pasar tareas a testing',
        'breadcrumbs' => [
            ['label' => 'Inicio', 'url' => '/home'],
            ['label' => 'Checking', 'url' => null],
        ],
        'actions' => '<a class="btn btn-outline btn-sm" href="/wiki#checking">Ver wiki completa</a>',
    ])

    <section class="card">
        <div class="card-header">Checking / Testing</div>
        <div class="card-body text-sm text-stone-600">
            <p>Usá la sección de checking en la <a href="/wiki#checking" class="font-semibold">Wiki</a> para revisar el checklist completo antes de pasar una tarea a testing.</p>
        </div>
    </section>
</div>
@endsection
