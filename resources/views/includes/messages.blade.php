<div class="mb-4 space-y-2">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if (Session::has('alert-' . $msg))
            <div class="alert-{{ $msg }} flex items-start justify-between gap-3">
                <p>{{ Session::get('alert-' . $msg) }}</p>
            </div>
        @endif
    @endforeach
</div>
