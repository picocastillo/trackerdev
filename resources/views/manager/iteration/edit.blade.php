@extends('layouts.app')

@section('content')
@include('includes.errors')
@include('includes.messages')
<div class="container">
    <div class="">
        <div class="col-12 h3 my-2">
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

</div>
    
    


@endsection
@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection
