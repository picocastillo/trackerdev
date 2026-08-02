@extends('layouts.app')

@section('content')
<div class="page-shell">
    @include('includes.page-header', [
        'title' => __('Register'),
        'subtitle' => 'Creá una cuenta nueva',
        'breadcrumbs' => [
            ['label' => 'Registro', 'url' => null],
        ],
    ])

    <div class="mx-auto max-w-lg">
        <section class="card">
            <div class="card-header">{{ __('Register') }}</div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="form-label">{{ __('Name') }}</label>
                        <input id="name" type="text" class="form-input @error('name') border-red-500 @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
                        <input id="email" type="email" class="form-input @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-input @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-input" name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-primary w-full">
                        {{ __('Register') }}
                    </button>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
