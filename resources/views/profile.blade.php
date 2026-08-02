@extends('layouts.app')

@section('content')
<div class="page-shell">
    @include('includes.page-header', [
        'title' => 'Perfil',
        'subtitle' => 'Datos de tu cuenta',
        'breadcrumbs' => [
            ['label' => 'Inicio', 'url' => '/home'],
            ['label' => 'Perfil', 'url' => null],
        ],
    ])

    <section class="card">
        <div class="card-header">Usuario</div>
        <div class="card-body">
            <dl class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div class="task-stat">
                    <dt>Nombre</dt>
                    <dd>{{ Auth::user()->name }}</dd>
                </div>
                <div class="task-stat">
                    <dt>Email</dt>
                    <dd>{{ Auth::user()->email }}</dd>
                </div>
            </dl>
        </div>
    </section>
</div>
@endsection
