<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts 
        <script src="{{ asset('js/app.js') }}" defer></script>
        -->

        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/4cf490c3ec.js"></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet"> 

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


        <!-- Styles 
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        -->

        <!-- Styles -->
        <style>

            .body {

                font-family: 'Quicksand';

                background: url(../images/fuerteNacimiento.JPEG) no-repeat center;

                background-size: cover;

                min-height: 100vh;

            }

            .header .navbar {
                
                background-color: transparent !important;

            }

            .form-area{

                font-family: 'Quicksand';

                color: #fff;

                position: absolute;

                top: 50%;

                left: 50%;

                transform: translate(-50%, -50%);

                width: 400px;

                height: 680px;

                background : rgba(0,0,0,0.7);

                border-radius: 25px 25px 25px 25px;

                -moz-border-radius: 25px 25px 25px 25px;

                -webkit-border-radius: 25px 25px 25px 25px;
            }

            .vl {
              
                border-left: 1px solid white;
                
                height: 450px;
            }

        </style>

    </head>

    <body class="body">

        <div class="header">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                
              <a class="navbar-brand text-secondary font-weight-lighter" href="{{ url('/') }}">

                    <img src="{{ asset('images/MarcaMunicipal_LetrasBlancas.png')}}" style="width: 200px;" class="ml-5 mr-3">
                    
                    Intranet Municipal

                </a>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                
                    <span class="navbar-toggler-icon"></span>
                
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    
                    <ul class="navbar-nav mr-auto">
                        
                       
                    </ul>

                     <span class="navbar-text">

                     @if (Route::has('login'))

                            <div>

                                @auth

                                    <a href="{{ url('/home') }}" class="text-secondary mr-3 text-decoration-none">Inicio</a>

                                

                                @endauth

                            </div>

                        @endif
                    
                    </span>

                </div>

            </nav>

            <div class="container form-area p-3">

                <div class="row">

                    <div class="col">
                        
                        <form method="POST" action="{{ route('register') }}">

                            @csrf

                            <div class="text-center mb-3">
                            
                                <h2>
                                
                                    Formulario de Registro
                                
                                </h2>    

                                <div class="text-white"> < Quiero acceder a la Intranet Municipal /> </div>
                            
                            </div>

                            <div class="form-group row mb-3">
                            
                                <label for="name" class="col-md-12 col-form-label">{{ __('Nombre') }}</label>

                                <div class="col-md-12">
                            
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Juan Per??z Castillo">

                                    @error('name')
                            
                                        <span class="invalid-feedback" role="alert">
                             
                                            <strong>{{ $message }}</strong>
                             
                                        </span>

                                    @enderror

                                </div>

                            </div>

                            <div class="form-group">

                                <label for="dependencySelect">Dependencia Municipal</label>

                                <select name="dependencySelect" class="browser-default custom-select" title="Por favor, seleecione su Dependencia Municipal">

                                    @foreach($dependencies as $dependency)

                                        <option value="{{ $dependency->id }}">{{ $dependency->name }}</option>

                                    @endforeach

                                </select>

                            </div>



                            <div class="form-group row mb-3">
                            
                                <label for="email" class="col-md-12 col-form-label">{{ __('Correo Institucional') }}</label>

                                <div class="col-md-12">
                            
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="usuario@nacimiento.cl">

                                    @error('email')
                                
                                        <span class="invalid-feedback" role="alert">
                                
                                            <strong>{{ $message }}</strong>

                                        </span>
                                    
                                    @enderror

                                </div>
                                
                            </div>

                            <div class="form-group row mb-3">
                        
                                <label for="password" class="col-md-12 col-form-label">{{ __('Contrase??a') }}</label>

                                <div class="col-md-12">
                                
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="******************">

                                    @error('password')

                                        <span class="invalid-feedback" role="alert">

                                            <strong>{{ $message }}</strong>

                                        </span>

                                    @enderror
                 
                                </div>
                 
                            </div>

                            <div class="form-group row mb-4">
                  
                                <label for="password-confirm" class="col-md-12 col-form-label">{{ __('Confirmar Contrase??a') }}</label>

                                <div class="col-md-12">
                   
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="******************">
                        
                                </div>
                        
                            </div>

                            <div class="form-group row mb-2">
                         
                                <div class="col-md-12">
                         
                                    <button type="submit" class="btn btn-success btn-block">
                         
                                        {{ __('Reg??strese') }}
                         
                                </button>
                         
                                </div>
                         
                            </div>

                            <div class="form-group row mb-3">
                            
                                <div class="col-md-12 text-center">

                                        <a href="{{ route('login') }}" class="btn btn-primary btn-block mr-5 text-decoration-none">Login</a>

                            
                                </div>
                            
                            </div>
                        
                        </form>

                    </div>

                </div>

            </div>

        </div>

    </body>

</html> 