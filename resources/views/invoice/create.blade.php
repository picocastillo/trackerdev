@extends('layouts.app')

@section('content')
<form method="POST" action="/manager/invoice">
    @csrf
    <input type="hidden" name="user_id" value="{{$user->id}}">
    <input type="hidden" name="from" value="{{$from}}">
    <input type="hidden" name="to" value="{{$to}}">
    <input type="hidden" name="productivity" value="{{$sum}}">
    <input type="hidden" name="total" value="{{$total}}">

    <div class="mb-4 flex flex-wrap gap-2">
        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Total de horas: {{$total}}</span>
        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Productividad: {{number_format($sum,2)}}%</span>
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
                    <input type="hidden" name="efforts[]" value="{{$item->id}}">
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
        <div class="lg:col-span-4 space-y-4">
            <div class="overflow-x-auto rounded-lg bg-stone-900">
                <table class="table-app text-white">
                    <thead>
                        <tr>
                            <th scope="col">task_id</th>
                            <th scope="col">total</th>
                            <th scope="col">estimacion</th>
                            <th scope="col">productividad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($total_by_tasks as $item)
                        <tr>
                            <th scope="row">{{$item->task_id}}</th>
                            <td>{{$item->total}}</td>
                            <td>{{$item->estimation}}</td>
                            <td>{{number_format($item->productivity * 100,2)}}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="rounded-lg bg-stone-900 p-4 text-white">
                <table class="table-app text-white">
                    <thead>
                        <tr>
                            <th scope="col">proyecto</th>
                            <th scope="col">Porcentaje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($percentages)
                            @foreach ($percentages as $key3 => $e)
                                @if (($e))
                                    <tr>
                                        <td>{{$name_project[$e->project_id]}}</td>
                                        <td>{{ round($e->total / $total,2) * 100}} %</td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr><td colspan="2" class="text-center">Aun no hay tiempos</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <input type="number" required name="rate" class="form-input" placeholder="Precio hora">
            <button type="submit" class="btn btn-primary">Terminar</button>
        </div>
    </div>

    <div class="mt-6 max-w-2xl">
        <textarea name="detail" cols="100" rows="15" class="form-input font-mono text-sm">
              <b>Puntos a mejorar: </b>
                <li></li>


              <b>Puntos mejorados: </b>
                <li></li>



              - Comunicasión: 1/10
              - Capacidad de análisis: 1/10
              - Autonomia: 1/10
              - capacidad de resolución: 1/10

              <i>Observaciones: </i>
        </textarea>
    </div>
</form>
@endsection
