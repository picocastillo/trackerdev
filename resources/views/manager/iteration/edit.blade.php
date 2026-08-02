@extends('layouts.app')

@section('content')
@include('includes.errors')
@include('includes.messages')

<div class="my-2">
    <div class="text-xl font-semibold">
        ====> Editar última iteración de {{$iteration->project->name}}
    </div>
    <div id="create_iteration"
        title={{$iteration->title}}
        billedHours={{$iteration->billed_hours}}
        time={{$time}}
        objetives="{{($objetives)}}"
        tasks="{{json_encode($tasks->toArray())}}"
        token={{json_encode(\Session::token())}}></div>
</div>
@endsection
