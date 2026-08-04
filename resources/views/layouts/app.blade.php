<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TrackerDev</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito|Ubuntu:400,500,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="font-display min-h-screen flex flex-col">
    <div id="app" class="flex min-h-screen flex-col">
        <nav class="bg-brand-dark text-white shadow-sm" x-data="{ open: false, userOpen: false }">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
                <a href="{{ url('/home') }}" class="flex items-center gap-2 text-white hover:text-white">
                    <div class="my_icon"></div>
                    <span class="text-lg font-bold tracking-wide">TrackerDev</span>
                </a>

                <button type="button" class="md:hidden rounded p-2 text-white hover:bg-white/10" onclick="document.getElementById('nav-menu').classList.toggle('hidden')" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>

                <div id="nav-menu" class="hidden w-full md:flex md:w-auto md:items-center md:justify-end">
                    @auth
                        <ul class="mt-3 flex flex-col gap-2 md:mt-0 md:flex-row md:items-center md:gap-4">
                            @if (isDeveloper())
                                <li><a class="text-white hover:text-stone-200" href="/wiki">Wiki</a></li>
                            @endif
                            @if (isClient() && canShowTimes())
                                <li>
                                    <a class="text-white hover:text-stone-200" href="/deposits">
                                        Depositos {{ canShowTimes() ? ' Y Tiempos' : '' }}
                                    </a>
                                </li>
                            @endif
                            @if (isDeveloper() || isManager())
                                <li><a class="text-white hover:text-stone-200" href="/reports">Reportes</a></li>
                            @endif
                            @if (isManager())
                                <li><a class="text-white hover:text-stone-200" href="/task/create">Crear Tarea</a></li>
                                <li><a class="text-white hover:text-stone-200" href="/project">Proyectos</a></li>
                                <li><a class="text-white hover:text-stone-200" href="/portfolio">Portfolio web</a></li>
                            @endif
                            <li class="relative" id="user-menu-wrap">
                                <button type="button" class="text-white hover:text-stone-200" onclick="document.getElementById('user-menu').classList.toggle('hidden')">
                                    {{ Auth::user()->name }} <i class="fas fa-caret-down ml-1"></i>
                                </button>
                                <div id="user-menu" class="absolute right-0 z-20 mt-2 hidden min-w-[10rem] rounded-md border border-stone-200 bg-white py-1 text-stone-800 shadow-lg">
                                    <a class="block px-4 py-2 text-sm hover:bg-stone-100" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Salir
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-6">
            @yield('content')
        </main>

        <footer class="mt-auto border-t border-stone-200 bg-white">
            <div class="mx-auto flex max-w-7xl justify-end px-4 py-4">
                <img height="80" width="80" src="/images/icon_td.png" alt="TrackerDev" />
            </div>
        </footer>
    </div>
    @yield('scripts')
</body>
</html>
