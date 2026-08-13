@extends('layouts.app')

@section('content')
    @include('includes.errors')
    @include('includes.messages')

    @php
        $isEdit = isset($user);
    @endphp

    <div class="page-shell">
        @include('includes.page-header', [
            'title' => $isEdit ? 'Editar usuario' : 'Nuevo usuario',
            'subtitle' => $isEdit ? 'Actualizá los datos de la cuenta' : 'Creá una cuenta para el equipo o un cliente',
            'breadcrumbs' => [
                ['label' => 'Usuarios', 'url' => '/users'],
                ['label' => $isEdit ? 'Editar' : 'Crear', 'url' => null],
            ],
        ])

        <div class="mx-auto max-w-3xl">
            <section class="card">
                <div class="card-header">
                    {{ $isEdit ? 'Editar usuario' : 'Nuevo usuario' }}
                </div>
                <div class="card-body">
                    <form
                        method="POST"
                        action="/users/{{ $isEdit ? $user->id.'/edit' : 'create' }}"
                        class="space-y-4"
                    >
                        @if ($isEdit)
                            @method('PUT')
                        @endif
                        @csrf

                        <div>
                            <label class="form-label" for="name">Nombre</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                required
                                value="{{ old('name', $isEdit ? $user->name : '') }}"
                                class="form-input"
                            >
                        </div>

                        <div>
                            <label class="form-label" for="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                required
                                value="{{ old('email', $isEdit ? $user->email : '') }}"
                                class="form-input"
                            >
                        </div>

                        <div>
                            <label class="form-label" for="password">
                                Contraseña {{ $isEdit ? '(dejar vacío para no cambiar)' : '' }}
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                autocomplete="new-password"
                                {{ $isEdit ? '' : 'required' }}
                                minlength="8"
                                class="form-input"
                            >
                        </div>

                        <div>
                            <label class="form-label" for="role_id">Rol</label>
                            <select id="role_id" name="role_id" required class="form-input">
                                <option value="">Seleccioná un rol</option>
                                @foreach ($roles as $role)
                                    <option
                                        value="{{ $role->id }}"
                                        {{ (string) old('role_id', $isEdit ? $user->role_id : '') === (string) $role->id ? 'selected' : '' }}
                                    >
                                        {{ $role->seniority }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="inline-flex items-center gap-2 text-sm">
                                    @if ($isEdit && $user->id === Auth::id())
                                        <input type="hidden" name="is_active" value="1">
                                        <input
                                            type="checkbox"
                                            value="1"
                                            checked
                                            disabled
                                            class="rounded border-stone-300 text-primary focus:ring-primary/30"
                                        >
                                    @else
                                        <input type="hidden" name="is_active" value="0">
                                        <input
                                            type="checkbox"
                                            name="is_active"
                                            value="1"
                                            class="rounded border-stone-300 text-primary focus:ring-primary/30"
                                            {{ (string) old('is_active', $isEdit ? ($user->is_active ? '1' : '0') : '1') === '1' ? 'checked' : '' }}
                                        >
                                    @endif
                                    Usuario activo
                                </label>
                                @if ($isEdit && $user->id === Auth::id())
                                    <p class="mt-1 text-xs text-stone-500">No podés desactivar tu propio usuario.</p>
                                @endif
                            </div>
                            <label class="inline-flex items-center gap-2 text-sm">
                                <input type="hidden" name="by_hours" value="0">
                                <input
                                    type="checkbox"
                                    name="by_hours"
                                    value="1"
                                    class="rounded border-stone-300 text-primary focus:ring-primary/30"
                                    {{ (string) old('by_hours', $isEdit ? ($user->by_hours ? '1' : '0') : '0') === '1' ? 'checked' : '' }}
                                >
                                Facturación por horas (cliente)
                            </label>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="btn btn-primary">
                                {{ $isEdit ? 'Actualizar' : 'Crear' }}
                            </button>
                            <a href="/users" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
