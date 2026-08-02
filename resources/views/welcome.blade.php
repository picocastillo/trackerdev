<!DOCTYPE html>
<html lang="es-AR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $configuredUrl = rtrim((string) config('app.url', 'https://trackerdev.com.ar'), '/');
        $siteUrl = str_contains($configuredUrl, 'localhost') || str_contains($configuredUrl, '127.0.0.1')
            ? 'https://trackerdev.com.ar'
            : $configuredUrl;
        $path = request()->getPathInfo() ?: '/';
        $canonical = $siteUrl . ($path === '/' ? '/' : rtrim($path, '/'));

        $pages = [
            '/' => [
                'title' => 'TrackerDev | Desarrollo de software web y móvil a medida',
                'description' => 'Empresa de desarrollo de software en Santa Fe, Argentina. Creamos aplicaciones web, apps móviles, sistemas a medida y landing pages con diseño, desarrollo y testing profesional.',
            ],
            '/methodology' => [
                'title' => 'Metodología de desarrollo de software | TrackerDev',
                'description' => 'Conocé cómo TrackerDev desarrolla software: descubrimiento, prototipo, presupuesto y producto funcional. Proceso claro para proyectos web y móviles.',
            ],
            '/projects' => [
                'title' => 'Proyectos de desarrollo de software | TrackerDev',
                'description' => 'Casos de desarrollo de software de TrackerDev: apps móviles, sistemas web y productos digitales a medida para distintos rubros.',
            ],
            '/contact' => [
                'title' => 'Contacto | Desarrollo de software TrackerDev',
                'description' => 'Contactá a TrackerDev por WhatsApp para cotizar desarrollo de software web o móvil a medida. Santa Fe, Argentina.',
            ],
        ];
        $page = $pages[$path] ?? $pages['/'];
        $title = $page['title'];
        $description = $page['description'];
        $ogImage = $siteUrl . '/images/bg3-opt.jpg';
        $logoUrl = $siteUrl . '/images/icon_td.png';
    @endphp

    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="author" content="TrackerDev">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="{{ $canonical }}">

    <meta property="og:locale" content="es_AR">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TrackerDev">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:secure_url" content="{{ $ogImage }}">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="1920">
    <meta property="og:image:height" content="1280">
    <meta property="og:image:alt" content="TrackerDev - desarrollo de software web y móvil">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">

    <meta name="google-site-verification" content="9ot2-8AJF12vTEGc9xlrwchn8r-UinvOLuc9FyZQLDw" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="msapplication-TileColor" content="#5d161a">
    <meta name="theme-color" content="#1a1a1a">

    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => $siteUrl . '/#organization',
                'name' => 'TrackerDev',
                'url' => $siteUrl . '/',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $logoUrl,
                    'width' => 256,
                    'height' => 256,
                ],
                'image' => $ogImage,
                'description' => $pages['/']['description'],
                'telephone' => '+54-342-528-7592',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'Santa Fe',
                    'addressRegion' => 'Santa Fe',
                    'addressCountry' => 'AR',
                ],
                'sameAs' => [
                    'https://www.facebook.com/trackerdev',
                    'https://www.instagram.com/trackerdev/',
                    'https://www.linkedin.com/in/trackerdev-solutions',
                    'https://wa.me/543425287592',
                ],
                'contactPoint' => [
                    [
                        '@type' => 'ContactPoint',
                        'telephone' => '+54-342-528-7592',
                        'contactType' => 'sales',
                        'areaServed' => 'AR',
                        'availableLanguage' => ['es', 'en'],
                    ],
                ],
            ],
            [
                '@type' => ['ProfessionalService', 'LocalBusiness'],
                '@id' => $siteUrl . '/#business',
                'name' => 'TrackerDev',
                'url' => $siteUrl . '/',
                'image' => $ogImage,
                'description' => 'Servicios de desarrollo de software: aplicaciones web, apps móviles, sistemas a medida, diseño UX/UI y landing pages.',
                'telephone' => '+54-342-528-7592',
                'priceRange' => '$$',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'Santa Fe',
                    'addressRegion' => 'Santa Fe',
                    'addressCountry' => 'AR',
                ],
                'geo' => [
                    '@type' => 'GeoCoordinates',
                    'latitude' => -31.6333,
                    'longitude' => -60.7,
                ],
                'areaServed' => [
                    '@type' => 'Country',
                    'name' => 'Argentina',
                ],
                'knowsAbout' => [
                    'Desarrollo de software',
                    'Desarrollo web',
                    'Desarrollo de aplicaciones móviles',
                    'Diseño UX UI',
                    'Software a medida',
                    'Laravel',
                    'React',
                ],
                'hasOfferCatalog' => [
                    '@type' => 'OfferCatalog',
                    'name' => 'Servicios de desarrollo de software',
                    'itemListElement' => [
                        [
                            '@type' => 'Offer',
                            'itemOffered' => [
                                '@type' => 'Service',
                                'name' => 'Desarrollo de sistemas web',
                                'description' => 'Sistemas y plataformas web a medida para operar y escalar negocios.',
                            ],
                        ],
                        [
                            '@type' => 'Offer',
                            'itemOffered' => [
                                '@type' => 'Service',
                                'name' => 'Desarrollo de aplicaciones móviles',
                                'description' => 'Apps nativas e híbridas para Android e iOS.',
                            ],
                        ],
                        [
                            '@type' => 'Offer',
                            'itemOffered' => [
                                '@type' => 'Service',
                                'name' => 'Diseño UX/UI',
                                'description' => 'Prototipado y diseño de interfaces para productos digitales.',
                            ],
                        ],
                        [
                            '@type' => 'Offer',
                            'itemOffered' => [
                                '@type' => 'Service',
                                'name' => 'Landing pages',
                                'description' => 'Páginas de aterrizaje orientadas a conversión y captación de leads.',
                            ],
                        ],
                    ],
                ],
                'parentOrganization' => ['@id' => $siteUrl . '/#organization'],
            ],
            [
                '@type' => 'WebSite',
                '@id' => $siteUrl . '/#website',
                'url' => $siteUrl . '/',
                'name' => 'TrackerDev',
                'description' => $pages['/']['description'],
                'publisher' => ['@id' => $siteUrl . '/#organization'],
                'inLanguage' => 'es-AR',
            ],
            [
                '@type' => 'WebPage',
                '@id' => $canonical . '#webpage',
                'url' => $canonical,
                'name' => $title,
                'description' => $description,
                'isPartOf' => ['@id' => $siteUrl . '/#website'],
                'about' => ['@id' => $siteUrl . '/#business'],
                'primaryImageOfPage' => [
                    '@type' => 'ImageObject',
                    'url' => $ogImage,
                    'width' => 1920,
                    'height' => 1280,
                ],
                'inLanguage' => 'es-AR',
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TY9Z038WBJ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-TY9Z038WBJ');
    </script>
    <script>
        window.__PORTFOLIO_PROJECTS__ = @json($portfolioProjects ?? []);
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="public-shell font-sans antialiased overflow-x-hidden">
    <div id="app-home"></div>
    <noscript>
        <main style="max-width:720px;margin:2rem auto;padding:1rem;color:#fff;font-family:sans-serif;">
            <h1>{{ $title }}</h1>
            <p>{{ $description }}</p>
            <h2>Servicios de desarrollo de software</h2>
            <ul>
                <li>Desarrollo de sistemas web a medida</li>
                <li>Desarrollo de aplicaciones móviles Android e iOS</li>
                <li>Diseño UX/UI y prototipado</li>
                <li>Landing pages orientadas a conversión</li>
            </ul>
            <nav>
                <a href="{{ $siteUrl }}/">Inicio</a> ·
                <a href="{{ $siteUrl }}/methodology">Metodología</a> ·
                <a href="{{ $siteUrl }}/projects">Proyectos</a> ·
                <a href="{{ $siteUrl }}/contact">Contacto</a>
            </nav>
            <p>Contacto WhatsApp: <a href="https://wa.me/543425287592">+54 342 528-7592</a></p>
        </main>
    </noscript>
</body>
</html>
