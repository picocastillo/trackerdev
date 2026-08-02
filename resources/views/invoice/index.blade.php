@extends('layouts.app')

@section('content')
<div class="page-shell">
    @include('includes.page-header', [
        'title' => 'Facturas',
        'subtitle' => 'Listado y alta de facturas',
        'breadcrumbs' => [
            ['label' => 'Inicio', 'url' => '/home'],
            ['label' => 'Facturas', 'url' => null],
        ],
    ])

    @if (\Auth::user()->isManager())
        <section class="card">
            <div class="card-header">Nueva factura</div>
            <div class="card-body">
                <form method="GET" action="manager/invoice/new" class="page-toolbar items-end">
                    @csrf
                    <div>
                        <label for="dev" class="form-label">Nueva factura para</label>
                        <select name="user_id" id="dev" class="form-input w-auto">
                            <option selected value="{{ 0 }}">Sin Definir</option>
                            @foreach ($devs as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input class="form-input w-auto" type="date" name="start_date" value="{{ old('start_date') ?? $start_date }}">
                    <input class="form-input w-auto" type="date" name="end_date" value="{{ old('end_date') ?? $end_date }}">
                    <button type="submit" class="btn btn-primary">Nueva factura</button>
                </form>
            </div>
        </section>
    @endif

    <section class="card">
        <div class="card-header">Listado</div>
        <div class="card-body overflow-x-auto p-0">
            <table class="table-app">
                <thead>
                    <tr>
                        <th scope="col">Total de horas</th>
                        <th scope="col">Productividad</th>
                        <th scope="col">Fechas</th>
                        <th scope="col">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $item)
                    <input type="hidden" name="effort[]" value="{{ $item->id }}">
                    <tr>
                        <th scope="row">{{ $item->total }}</th>
                        <td>{{ $item->productivity }}</td>
                        <td>{{ $item->getDate() }}</td>
                        <td>
                            <a class="btn btn-sm btn-primary" href="/invoice/{{ $item->id }}">Ver</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
