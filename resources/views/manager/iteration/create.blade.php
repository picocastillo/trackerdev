@extends('layouts.app')

@section('content')
@include('includes.errors')
@include('includes.messages')

<div class="my-2">
    <div class="text-2xl font-bold">
        Nueva Iteración para {{$project->name}}
        @if ($project->getLastIteration())
            (Ultima {{$project->getLastIteration()->title}})
        @endif
    </div>
    <div id="create_iteration" project_id={{$project->id}} token={{json_encode(\Session::token())}}></div>
</div>
@endsection
