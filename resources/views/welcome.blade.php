<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TrackerDev</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
    <meta name="google-site-verification" content="9ot2-8AJF12vTEGc9xlrwchn8r-UinvOLuc9FyZQLDw" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="description" content="Somos un equipo de trabajo destinado al diseño, desarrollo, implementación y pruebas de Software Web y Mobile. Soluciones integrales para problemas desglosables. Nos encontramos en la ciudad de Santa Fe Capital, hacemos software personalizado, aplicaciones móviles, llevamos una solución de software a tu negocio." />
    <meta name="title" content="Desarrollo de Software Web y Móvil, un equipo ubicado en la ciudad de Santa Fe Capital" />
    <meta name="msapplication-TileColor" content="#5d161a">
    <meta name="theme-color" content="#1a1a1a">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TY9Z038WBJ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-TY9Z038WBJ');
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="public-shell font-sans antialiased overflow-x-hidden">
    <div id="app-home" data-token="{{ csrf_token() }}" token="{{ csrf_token() }}"></div>
</body>
</html>
