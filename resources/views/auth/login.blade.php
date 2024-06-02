<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>TrackerDev</title>
    
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body >

  
    <div class="row">
        <div class="col-sm-1 offset-sm-11 mt-2">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ Config::get('languages')[App::getLocale()] }}
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  @foreach (Config::get('languages') as $lang => $language)
                    @if ($lang != App::getLocale())
                            <a class="dropdown-item"href="{{ route('lang.switch', $lang) }}"> {{$language}}</a>
                    @endif
                   @endforeach
                </div>
              </div>
        </div>
    </div>

    <div class="login">
        <div class="container ">
            <div class="row justify-content-center my-5">
                <img height="200" width="200" src="images/icon_1.svg" />
            </div>
            <div class="row justify-content-center my-2">
                <h3>{{__('Sign in to TrackerDev')}}</h3>
            </div>
            <div class="row justify-content-center ubuntu">
                    
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header b text-center ">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group ">
                                    <div class="text-center">
                                        <label for="email" class=" col-form-label text-center">{{__('E-Mail Address')}}</label>
                                    </div>
                                        <input id="email" type="email"   class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
        
                                <div class="form-group ">
                                    <div class="text-center">
                                        <label for="password" class="col-form-label text-md-right">{{__('Password')}}</label>
                                    </div>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-12 col-sm-12">
                                        <button type="submit" class="btn btn-primary col-12">
                                            <b>Ingresar</b>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</body>
</html>

