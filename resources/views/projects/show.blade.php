@extends('layouts.app')

@section('content')
<div class="mx-2">

    <div class="h2">{{$project->name}}</div>

 
    @endforeach

    

    
</div>
    
    


@endsection
@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection
