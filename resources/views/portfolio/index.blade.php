@extends('layouts.app')

@section('content')
    @include('includes.errors')
    @include('includes.messages')

    <div class="page-shell">
        @include('includes.page-header', [
            'title' => 'Portfolio web',
            'subtitle' => 'Proyectos publicados en el sitio',
            'breadcrumbs' => [
                ['label' => 'Inicio', 'url' => '/home'],
                ['label' => 'Portfolio', 'url' => null],
            ],
            'actions' => '<a class="btn btn-primary btn-sm" href="/portfolio/create"><i class="fa fa-plus mr-1.5"></i> Nuevo proyecto portfolio</a>',
        ])

        <section class="card">
            <div class="card-body overflow-x-auto p-0">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th scope="col">Orden</th>
                            <th scope="col">Imagen</th>
                            <th scope="col">Título</th>
                            <th scope="col">Badges</th>
                            <th scope="col">Activo</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projects as $p)
                            <tr>
                                <td>{{ $p->sort_order }}</td>
                                <td>
                                    <img src="{{ $p->image }}" alt="{{ $p->title }}" class="h-12 w-20 rounded object-cover object-top">
                                </td>
                                <th scope="row">{{ $p->title }}</th>
                                <td>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($p->badges ?? [] as $badge)
                                            <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{ $badge }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ $p->is_active ? 'Sí' : 'No' }}</td>
                                <td>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <a href="/portfolio/{{ $p->id }}/edit" class="text-sm font-medium">Editar</a>
                                        <form method="POST" action="/portfolio/{{ $p->id }}" onsubmit="return confirm('¿Eliminar este proyecto del portfolio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-red-700 hover:text-red-800">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-stone-500">No hay proyectos de portfolio todavía.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
