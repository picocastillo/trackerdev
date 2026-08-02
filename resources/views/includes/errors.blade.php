@if ($errors->any())
    <div class="alert-danger mb-4">
        <p class="mb-2 font-semibold">Errores</p>
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
