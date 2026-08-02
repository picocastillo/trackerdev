{{--
    Shared page header.
    @param string $title
    @param string|null $subtitle
    @param array $breadcrumbs  [['label' => '...', 'url' => '...'|null], ...]
    @param string|null $actions HTML/Blade string for right-side actions (optional; prefer @slot via include + variable)
--}}
@php
    $breadcrumbs = $breadcrumbs ?? [];
    $subtitle = $subtitle ?? null;
    $actions = $actions ?? null;
@endphp

<header class="page-header overflow-hidden rounded-xl border border-stone-200 bg-white shadow-sm">
    <div class="relative border-b border-stone-200 bg-gradient-to-br from-stone-50 via-white to-primary/5 px-5 py-5 sm:px-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0 flex-1 space-y-3">
                @if (count($breadcrumbs))
                    <nav class="flex flex-wrap items-center gap-2 text-sm text-stone-500" aria-label="Breadcrumb">
                        @foreach ($breadcrumbs as $index => $crumb)
                            @if ($index > 0)
                                <span aria-hidden="true">/</span>
                            @endif
                            @if (!empty($crumb['url']))
                                <a href="{{ $crumb['url'] }}" class="hover:text-primary">{{ $crumb['label'] }}</a>
                            @else
                                <span class="font-medium text-stone-700">{{ $crumb['label'] }}</span>
                            @endif
                        @endforeach
                    </nav>
                @endif

                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="font-display text-2xl font-bold tracking-tight text-stone-900 sm:text-3xl">
                        {{ $title }}
                    </h1>
                </div>

                @if ($subtitle)
                    <p class="text-sm text-stone-500">{{ $subtitle }}</p>
                @endif
            </div>

            @if (!empty($actions))
                <div class="flex flex-wrap items-center gap-2">
                    {!! $actions !!}
                </div>
            @endif
        </div>
    </div>
</header>
