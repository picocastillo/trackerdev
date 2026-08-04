@extends('layouts.app')

@section('content')
<div class="page-shell">
    @include('includes.page-header', [
        'title' => __('Verify Your Email Address'),
        'subtitle' => 'Confirmá tu correo para continuar',
        'breadcrumbs' => [
            ['label' => 'Verificación', 'url' => null],
        ],
    ])

    <div class="mx-auto max-w-lg">
        <section class="card">
            <div class="card-header">{{ __('Verify Your Email Address') }}</div>
            <div class="card-body space-y-3 text-sm text-stone-700">
                @if (session('resent'))
                    <div class="alert-success">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif

                <p>
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="text-primary underline hover:text-primary-light">{{ __('click here to request another') }}</button>.
                    </form>
                </p>
            </div>
        </section>
    </div>
</div>
@endsection
