<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TrackerDev</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito|Ubuntu:400,500,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="login font-display">
    <div class="mx-auto flex max-w-7xl justify-end px-4 pt-4">
        <div class="relative" id="lang-menu-wrap">
            <button type="button" class="btn-primary" onclick="document.getElementById('lang-menu').classList.toggle('hidden')">
                {{ Config::get('languages')[App::getLocale()] }}
                <i class="fas fa-caret-down ml-1"></i>
            </button>
            <div id="lang-menu" class="absolute right-0 z-10 mt-1 hidden min-w-[8rem] rounded-md border border-stone-200 bg-white py-1 shadow-lg">
                @foreach (Config::get('languages') as $lang => $language)
                    @if ($lang != App::getLocale())
                        <a class="block px-4 py-2 text-sm text-stone-800 hover:bg-stone-100" href="{{ route('lang.switch', $lang) }}">{{ $language }}</a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-md px-4 pb-16 pt-10">
        <div class="mb-8 flex justify-center">
            <img height="160" width="160" src="{{ asset('images/icon_1.svg') }}" alt="TrackerDev" />
        </div>
        <h1 class="mb-6 text-center text-2xl font-bold text-stone-900">{{ __('Sign in to TrackerDev') }}</h1>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="form-label text-center">{{ __('E-Mail Address') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                               class="form-input @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="form-label text-center">{{ __('Password') }}</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="form-input @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn-primary w-full">
                        <b>Ingresar</b>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
