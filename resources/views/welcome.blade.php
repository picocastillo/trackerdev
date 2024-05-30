<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Tracker Dev</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <meta name="google-site-verification" content="9ot2-8AJF12vTEGc9xlrwchn8r-UinvOLuc9FyZQLDw" />
        {{-- Icon --}}

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <meta name="description" content="Somos un equipo de trabajo destinado al diseño, desarrollo, implementación y pruebas de Software Web y Mobile. Soluciones integrales para problemas desglosables. Nos encontramos en la ciudad de Santa Fe Capital, hacemos software personalizado, aplicaciones móviles, llevamos una solución de software a tu negocio." />
        <meta name="title" content="Desarrollo de Software Web y Móvil, un equipo ubicado en la ciudad de Santa Fe Capital" />

        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <link rel="stylesheet" href="css/media.css" media="creen and (max-width: 768px)" />
        <meta name="viewport" content="width=device-width, initial-scale=1" >


        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-TY9Z038WBJ"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-TY9Z038WBJ');
        </script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        {{-- Icon --}}
    </head>
    <body>
        {{-- <div class="flex-center position-ref full-height"> --}}
            {{-- @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif --}}

           
        {{-- </div> --}}
        <div  id="app-home" token="{{ csrf_token() }}"></div>
    </body>
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
</html>

<!-- Google Analytics -->
<script>
window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
ga('create', 'UA-XXXXX-Y', 'auto');
ga('send', 'pageview');
</script>
<script async src='https://www.google-analytics.com/analytics.js'></script>
<!-- End Google Analytics -->