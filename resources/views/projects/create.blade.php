@extends('layouts.app')

@section('content')
    @include('includes.errors')
    @include('includes.messages')

    @php
        $isEdit = isset($project);
        $selectedUserIds = collect(old('users_ids', $isEdit ? $project->users->pluck('id')->all() : []))
            ->map(fn ($id) => (int) $id);
        $createClient = (string) old('create_client', '0') === '1';
    @endphp

    <div class="page-shell">
        @include('includes.page-header', [
            'title' => $isEdit ? 'Editar proyecto' : 'Nuevo proyecto',
            'subtitle' => $isEdit ? 'Actualizá el nombre y el equipo del proyecto' : 'Definí el nombre, el cliente y el equipo',
            'breadcrumbs' => [
                ['label' => 'Proyectos', 'url' => '/project'],
                ['label' => $isEdit ? 'Editar' : 'Crear', 'url' => null],
            ],
        ])

        <div class="mx-auto max-w-3xl">
            <section class="card">
                <div class="card-header">
                    {{ $isEdit ? 'Editar proyecto' : 'Nuevo proyecto' }}
                </div>
                <div class="card-body">
                    <form
                        method="POST"
                        action="/project/{{ $isEdit ? $project->id.'/edit' : 'create' }}"
                        class="space-y-6"
                    >
                        @if ($isEdit)
                            @method('PUT')
                        @endif
                        @csrf

                        <div>
                            <label class="form-label" for="title">Nombre del proyecto</label>
                            <input
                                type="text"
                                id="title"
                                name="title"
                                required
                                maxlength="255"
                                value="{{ old('title', $isEdit ? $project->name : '') }}"
                                class="form-input"
                                placeholder="Ej. Sitio web Acme"
                            >
                        </div>

                        @unless ($isEdit)
                            <div class="rounded-lg border border-stone-200 bg-stone-50/60 p-4">
                                <label class="inline-flex items-center gap-2 text-sm font-medium text-stone-800">
                                    <input
                                        type="checkbox"
                                        id="create-client"
                                        name="create_client"
                                        value="1"
                                        class="rounded border-stone-300 text-primary focus:ring-primary/30"
                                        {{ $createClient ? 'checked' : '' }}
                                    >
                                    Crear usuario cliente junto con el proyecto
                                </label>
                                <p class="mt-1 text-xs text-stone-500">
                                    El cliente queda inactivo hasta que lo actives en Usuarios. Te vamos a mostrar la contraseña generada.
                                </p>

                                <div id="client-fields" class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 {{ $createClient ? '' : 'hidden' }}">
                                    <div>
                                        <label class="form-label" for="client_name">Nombre del cliente</label>
                                        <input
                                            type="text"
                                            id="client_name"
                                            name="client_name"
                                            maxlength="255"
                                            value="{{ old('client_name') }}"
                                            class="form-input"
                                            {{ $createClient ? 'required' : '' }}
                                        >
                                    </div>
                                    <div>
                                        <label class="form-label" for="client_email">Email del cliente</label>
                                        <input
                                            type="email"
                                            id="client_email"
                                            name="client_email"
                                            maxlength="255"
                                            value="{{ old('client_email') }}"
                                            class="form-input"
                                            {{ $createClient ? 'required' : '' }}
                                        >
                                    </div>
                                </div>
                            </div>
                        @endunless

                        <div>
                            <div class="mb-2 flex flex-wrap items-end justify-between gap-2">
                                <div>
                                    <label class="form-label mb-0" for="user-search">Equipo</label>
                                    <p class="mt-1 text-xs text-stone-500">
                                        Seleccioná quién participa. <span id="selected-count">{{ $selectedUserIds->count() }}</span> elegido(s).
                                    </p>
                                </div>
                                <input
                                    type="search"
                                    id="user-search"
                                    class="form-input max-w-xs"
                                    placeholder="Buscar por nombre o email"
                                    autocomplete="off"
                                >
                            </div>

                            <div class="max-h-80 space-y-1 overflow-y-auto rounded-md border border-stone-200 bg-white p-2">
                                @forelse ($users as $item)
                                    <label
                                        data-user-row
                                        data-search="{{ strtolower($item->name.' '.$item->email.' '.$item->role->seniority) }}"
                                        class="flex cursor-pointer items-center gap-3 rounded-md px-3 py-2 hover:bg-stone-50"
                                    >
                                        <input
                                            type="checkbox"
                                            name="users_ids[]"
                                            value="{{ $item->id }}"
                                            class="user-checkbox rounded border-stone-300 text-primary focus:ring-primary/30"
                                            {{ $selectedUserIds->contains((int) $item->id) ? 'checked' : '' }}
                                        >
                                        <span class="min-w-0 flex-1">
                                            <span class="block truncate font-medium text-stone-800">{{ $item->name }}</span>
                                            <span class="block truncate text-xs text-stone-500">{{ $item->email }}</span>
                                        </span>
                                        <span class="inline-flex shrink-0 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                                            {{ $item->role->seniority }}
                                        </span>
                                        @unless ($item->is_active)
                                            <span class="inline-flex shrink-0 rounded-full bg-stone-200 px-2.5 py-0.5 text-xs font-semibold text-stone-700">
                                                Desactivado
                                            </span>
                                        @endunless
                                    </label>
                                @empty
                                    <p class="px-3 py-6 text-center text-sm text-stone-500">No hay usuarios activos para asignar.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="btn btn-primary">
                                {{ $isEdit ? 'Actualizar' : 'Crear' }}
                            </button>
                            <a href="/project" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function () {
            const createClient = document.getElementById('create-client');
            const clientFields = document.getElementById('client-fields');
            const userSearch = document.getElementById('user-search');
            const selectedCount = document.getElementById('selected-count');
            const checkboxes = document.querySelectorAll('.user-checkbox');

            function toggleClientFields() {
                if (!createClient || !clientFields) {
                    return;
                }
                const enabled = createClient.checked;
                clientFields.classList.toggle('hidden', !enabled);
                clientFields.querySelectorAll('input').forEach(function (input) {
                    input.required = enabled;
                });
            }

            function updateSelectedCount() {
                if (!selectedCount) {
                    return;
                }
                selectedCount.textContent = document.querySelectorAll('.user-checkbox:checked').length;
            }

            createClient?.addEventListener('change', toggleClientFields);

            userSearch?.addEventListener('input', function (event) {
                const query = event.target.value.toLowerCase().trim();
                document.querySelectorAll('[data-user-row]').forEach(function (row) {
                    row.classList.toggle('hidden', query !== '' && !row.dataset.search.includes(query));
                });
            });

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            toggleClientFields();
            updateSelectedCount();
        })();
    </script>
@endsection
