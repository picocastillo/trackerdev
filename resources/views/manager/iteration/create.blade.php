@extends('layouts.app')

@section('content')
@include('includes.errors')
@include('includes.messages')
<div class="container">
    <div class="">
        <div class="col-12 my-2">
            <div class="h2">
                Nueva Iteración para {{$project->name}}      
                @if ($project->getLastIteration())
                    (Ultima {{$project->getLastIteration()->title}})
                @endif
            </div>              
        </div>
        
        <div id="create_iteration"  project_id={{$project->id}} token={{json_encode(\Session::token())}}></div>
    </div>

</div>
    
    


@endsection
@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection
