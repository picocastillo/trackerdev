@extends('layouts.app')

@section('content')
<div class="container">
    @include('includes.errors')
    @include('includes.messages')

    <div class="row">
            <div class="col-5">
                <div class="card">
                    <div class="card-header">
                        <ul>
                            @foreach ($users as $item)
                                <li>  {{$item->email}} => ID {{$item->id}} <span class="badge badge-success" > {{$item->role->seniority}}</span></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        <div class="col-7">
            <div class="card">
                <div class="card-header">
                    Nuevo proyecto
                </div>
                <div class="card-body">
        
                    
        
                        <form method="POST" action="/project/{{isset($project) ? $project->id.'/edit' : 'create'}}">
                            @if (isset($project))
                                @method('PUT')
                            @endif
                            @csrf
                            <label>Nombre del proyecto-nombre cliente-email</label>
                            @if (isset($project))
                                <input type="text" required name="title" value="{{$project->name}}"  class="form-control my-2" />
                            @else
                                <input type="text" required name="title" class="form-control my-2" />
                            @endif
                            <label>Ingrese equipo separado por comas</label>
                            @if (isset($project))
                                @php
                                    $aux = $project->users()->pluck('users.id')->toArray();
                                    $aux = implode(',',$aux);
                                @endphp
                                <input type="text" required name="users_ids" value="{{$aux}}" class="form-control my-2"  />
                            @else
                                <input type="text" required name="users_ids" class="form-control my-2"  />
                            @endif
                            
                            @if (isset($project))
                                <textarea name="description" cols="100" rows="5" class="form-control">
                                {{$project->description}}
                              </textarea>
                            @else
                            <textarea name="description" cols="100" rows="5" class="form-control">
                                Este es un proyecto acerca de
                              </textarea>
                            @endif
                            
                            <div class="form-group row  my-2">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary col-12">
                                        @if (isset($project))
                                            Actualizar
                                        @else
                                            Crear
                                        @endif
                                    </button>
                                </div>
                            </div>
                      
                    
                           
                            
                        </form>
                </div>
                
            </div>
        </div>
    </div>

    



@endsection

@section('scripts')
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection
