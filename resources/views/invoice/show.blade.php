@extends('layouts.app')

@section('content')
<div class="page-shell">
    @include('includes.page-header', [
        'title' => 'Factura',
        'subtitle' => 'Detalle de liquidación',
        'breadcrumbs' => [
            ['label' => 'Facturas', 'url' => '/invoice'],
            ['label' => 'Detalle', 'url' => null],
        ],
    ])

    @if (\Auth::user()->isManager() && !$invoice->expence)
        <section class="card">
            <div class="card-header">Registrar pago</div>
            <div class="card-body">
                <form method="POST" action="/manager/invoice/paid-off" class="page-toolbar items-end">
                    @csrf
                    <input class="form-input w-auto" type="text" name="amount" value="{{ $amount }}" placeholder="Pago">
                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                    <button type="submit" class="btn btn-primary">Pagar</button>
                </form>
            </div>
        </section>
    @endif

    <dl class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        <div class="task-stat">
            <dt>Total de horas</dt>
            <dd>{{ $invoice->total }}</dd>
        </div>
        <div class="task-stat">
            <dt>Productividad</dt>
            <dd>{{ number_format($invoice->productivity, 2) }}%</dd>
        </div>
        @if (\Auth::user()->isDeveloper())
            <div class="task-stat">
                <dt>Estado</dt>
                <dd>
                    @if ($invoice->expence)
                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Pagado</span>
                    @else
                        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Adeudado</span>
                    @endif
                </dd>
            </div>
        @endif
        @if (number_format($invoice->productivity, 2) >= 75 && number_format($invoice->productivity, 2) < 95)
            <div class="task-stat">
                <dt>Bonus</dt>
                <dd>+15%</dd>
            </div>
        @endif
        @if (number_format($invoice->productivity, 2) >= 95)
            <div class="task-stat">
                <dt>Bonus</dt>
                <dd>+30%</dd>
            </div>
        @endif
    </dl>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <section class="card lg:col-span-8">
            <div class="card-header">Esfuerzos</div>
            <div class="card-body overflow-x-auto p-0">
                <table class="table-app">
                    <thead>
                        <tr>
                            <th scope="col">HS</th>
                            <th scope="col">Tarea</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Proyecto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($efforts as $item)
                        <input type="hidden" name="effort[]" value="{{ $item->id }}">
                        <tr>
                            <th scope="row">{{ $item->amount }}</th>
                            <td><a href="/tasks/{{ $item->task->id }}">{{ $item->task->getTitle() }}</a></td>
                            <td>{{ $item->getDate() }}</td>
                            <td>{{ $item->detail }}</td>
                            <td>
                                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                                    {{ $item->task->project->name }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card lg:col-span-4">
            <div class="card-header">Detalle</div>
            <div class="card-body prose-task text-sm text-stone-700">
                {!! nl2br(str_replace(' ', '&nbsp;', $invoice->detail)) !!}
            </div>
        </section>
    </div>
</div>
@endsection
