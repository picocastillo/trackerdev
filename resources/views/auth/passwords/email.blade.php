@extends('layouts.app')

@section('content')
<div class="page-shell">
    @include('includes.page-header', [
        'title' => __('Reset Password'),
        'subtitle' => 'Te enviamos un enlace para restablecer la contraseña',
        'breadcrumbs' => [
            ['label' => 'Recuperar contraseña', 'url' => null],
        ],
    ])

    <div class="mx-auto max-w-lg">
        <section class="card">
            <div class="card-header">{{ __('Reset Password') }}</div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert-success mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
                        <input id="email" type="email" class="form-input @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-full">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
