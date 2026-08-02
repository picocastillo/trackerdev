@extends('layouts.app')

@section('content')
    @if(\Auth::user()->isManager())
    <form method="GET" action="manager/invoice/new" class="mb-4 flex flex-wrap items-end gap-2">
        @csrf
        <div>
            <label for="dev" class="form-label">Nueva factura para</label>
            <select name="user_id" id="dev" class="form-input w-auto">
                <option selected value={{0}}>Sin Definir</option>
                @foreach ($devs as $d)
                    <option value={{$d->id}}>{{$d->name}}</option>
                @endforeach
            </select>
        </div>
        <input class="form-input w-auto" type="date" name="start_date" value="{{old('start_date') ?? $start_date}}">
        <input class="form-input w-auto" type="date" name="end_date" value="{{old('end_date') ?? $end_date}}">
        <button type="submit" class="btn btn-primary">Nueva factura</button>
    </form>
    @endif

    <div class="overflow-x-auto rounded-lg bg-stone-900">
        <table class="table-app text-white">
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
                <input type="hidden" name="effort[]" value="{{$item->id}}">
                <tr>
                    <th scope="row">{{$item->total}}</th>
                    <td>{{$item->productivity}}</td>
                    <td>{{$item->getDate()}}</td>
                    <td><a class="btn btn-sm bg-emerald-600 text-white hover:bg-emerald-700" href="/invoice/{{$item->id}}">Ver</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
