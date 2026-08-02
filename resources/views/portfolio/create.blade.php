@extends('layouts.app')

@section('content')
    @include('includes.errors')
    @include('includes.messages')

    @php
        $isEdit = isset($project);
    @endphp

    <div class="page-shell">
        @include('includes.page-header', [
            'title' => $isEdit ? 'Editar proyecto portfolio' : 'Nuevo proyecto portfolio',
            'subtitle' => $isEdit ? 'Actualizá el contenido del sitio' : 'Agregá un proyecto al portfolio público',
            'breadcrumbs' => [
                ['label' => 'Portfolio', 'url' => '/portfolio'],
                ['label' => $isEdit ? 'Editar' : 'Crear', 'url' => null],
            ],
        ])

        <div class="mx-auto max-w-3xl">
            <section class="card">
                <div class="card-header">
                    {{ $isEdit ? 'Editar proyecto portfolio' : 'Nuevo proyecto portfolio' }}
                </div>
                <div class="card-body">
                    <form
                        method="POST"
                        action="/portfolio/{{ $isEdit ? $project->id.'/edit' : 'create' }}"
                        enctype="multipart/form-data"
                        class="space-y-4"
                    >
                        @if ($isEdit)
                            @method('PUT')
                        @endif
                        @csrf

                        <div>
                            <label class="form-label" for="title">Título</label>
                            <input
                                type="text"
                                id="title"
                                name="title"
                                required
                                value="{{ old('title', $isEdit ? $project->title : '') }}"
                                class="form-input"
                            >
                        </div>

                        <div>
                            <label class="form-label" for="description">Descripción</label>
                            <textarea
                                id="description"
                                name="description"
                                required
                                rows="4"
                                class="form-input"
                            >{{ old('description', $isEdit ? $project->description : '') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label" for="badges">Badges (separados por comas)</label>
                            <input
                                type="text"
                                id="badges"
                                name="badges"
                                value="{{ old('badges', $isEdit ? implode(', ', $project->badges ?? []) : '') }}"
                                class="form-input"
                                placeholder="React Native, Laravel, Bootstrap"
                            >
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label" for="sort_order">Orden</label>
                                <input
                                    type="number"
                                    id="sort_order"
                                    name="sort_order"
                                    min="0"
                                    value="{{ old('sort_order', $isEdit ? $project->sort_order : 0) }}"
                                    class="form-input"
                                >
                            </div>
                            <div class="flex items-end pb-2">
                                <label class="inline-flex items-center gap-2 text-sm">
                                    <input type="hidden" name="is_active" value="0">
                                    <input
                                        type="checkbox"
                                        name="is_active"
                                        value="1"
                                        class="rounded border-stone-300 text-primary focus:ring-primary/30"
                                        {{ (string) old('is_active', $isEdit ? ($project->is_active ? '1' : '0') : '1') === '1' ? 'checked' : '' }}
                                    >
                                    Activo en el sitio
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="form-label" for="image">
                                Imagen principal {{ $isEdit ? '(opcional para reemplazar)' : '' }}
                            </label>
                            @if ($isEdit && $project->image)
                                <div class="mb-2">
                                    <img src="{{ $project->image }}" alt="{{ $project->title }}" class="h-28 w-auto rounded border object-cover object-top">
                                </div>
                            @endif
                            <input
                                type="file"
                                id="image"
                                name="image"
                                accept="image/jpeg,image/png,image/gif,image/webp"
                                class="form-input"
                                {{ $isEdit ? '' : 'required' }}
                            >
                        </div>

                        <div>
                            <label class="form-label" for="secondary_image">Imagen secundaria (opcional)</label>
                            @if ($isEdit && $project->secondary_image)
                                <div class="mb-2">
                                    <img src="{{ $project->secondary_image }}" alt="Detalle {{ $project->title }}" class="h-28 w-auto rounded border object-cover object-top">
                                </div>
                                <label class="mb-2 inline-flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="remove_secondary_image" value="1" class="rounded border-stone-300 text-primary focus:ring-primary/30">
                                    Quitar imagen secundaria
                                </label>
                            @endif
                            <input
                                type="file"
                                id="secondary_image"
                                name="secondary_image"
                                accept="image/jpeg,image/png,image/gif,image/webp"
                                class="form-input"
                            >
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="btn btn-primary">
                                {{ $isEdit ? 'Actualizar' : 'Crear' }}
                            </button>
                            <a href="/portfolio" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
