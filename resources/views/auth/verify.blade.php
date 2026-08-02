@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-lg">
    <div class="card">
        <div class="card-header">{{ __('Verify Your Email Address') }}</div>
        <div class="card-body space-y-3">
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
    </div>
</div>
@endsection
