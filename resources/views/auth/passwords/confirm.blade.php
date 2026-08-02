@extends('layouts.app')

@section('content')
<div class="page-shell">
    @include('includes.page-header', [
        'title' => __('Confirm Password'),
        'subtitle' => 'Confirmá tu contraseña para continuar',
        'breadcrumbs' => [
            ['label' => 'Confirmar contraseña', 'url' => null],
        ],
    ])

    <div class="mx-auto max-w-lg">
        <section class="card">
            <div class="card-header">{{ __('Confirm Password') }}</div>
            <div class="card-body">
                <p class="mb-4 text-sm text-stone-600">{{ __('Please confirm your password before continuing.') }}</p>

                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-input @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Confirm Password') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-primary hover:text-primary-light" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
