@extends('layouts.app')

@section('content')
@if(\Auth::user()->isManager() && !$invoice->expence)
<div class="mb-4">
    <form method="POST" action="/manager/invoice/paid-off" class="flex flex-wrap items-end gap-2">
        @csrf
        <input class="form-input w-auto" type="text" name="amount" value="{{$amount}}" placeholder="Pago">
        <input type="hidden" name="invoice_id" value="{{$invoice->id}}">
        <button type="submit" class="btn btn-primary">Pagar</button>
    </form>
</div>
@endif

<div class="mb-4 flex flex-wrap gap-2">
    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Total de horas: {{$invoice->total}}</span>
    @if (\Auth::user()->isDeveloper())
        @if ($invoice->expence)
            <span class="alert-info inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold">PAGADO</span>
        @else
            <span class="alert-warning inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold">ADEUDADO</span>
        @endif
    @endif
    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Productividad: {{number_format($invoice->productivity,2)}}%</span>
    @if(number_format($invoice->productivity,2)>=75 && number_format($invoice->productivity,2)<95)
        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">+15%</span>
    @endif
    @if(number_format($invoice->productivity,2)>=95)
        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">+30%</span>
    @endif
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
    <div class="lg:col-span-8 overflow-x-auto rounded-lg bg-stone-900">
        <table class="table-app text-white">
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
                <input type="hidden" name="effort[]" value="{{$item->id}}">
                <tr>
                    <th scope="row">{{$item->amount}}</th>
                    <td><a href="/tasks/{{$item->task->id}}">{{$item->task->getTitle()}}</a></td>
                    <td>{{$item->getDate()}}</td>
                    <td>{{$item->detail}}</td>
                    <td><span class="inline-flex rounded-full bg-stone-600 px-2.5 py-0.5 text-xs font-semibold text-white">{{$item->task->project->name}}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="lg:col-span-4">
        <div class="card overflow-hidden">
            <div class="card-header bg-stone-900 text-center text-xl text-white">Detalle</div>
            <div class="card-body bg-stone-900 text-white">
                {!! nl2br(str_replace(' ','&nbsp;',$invoice->detail)) !!}
            </div>
        </div>
    </div>
</div>
@endsection
