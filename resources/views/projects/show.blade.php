@extends('layouts.app')

@section('content')
    <div class="mx-2">
        <div class="text-2xl font-bold">{{$project->name}}</div>

        @endforeach
    </div>
@endsection
