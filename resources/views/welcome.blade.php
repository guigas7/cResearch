<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Cepeti - Estudo</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="/css/welcome.css">

    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Cadastro</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Cepeti
                </div>

                <div class="links">
                    <a class="options" href="{{ url('/cl/pacientes') }}">Pacientes já randomizados (Trial Cloroquina)</a>
                    <a class="options" href="{{ url('/cl/pacientes/criar') }}">Randomizar novo paciente (Trial Cloroquina)</a>
                    <a class="options" href="{{ url('/pr/pacientes') }}">Pacientes já randomizados (Trial Prona)</a>
                    <a class="options" href="{{ url('/pr/pacientes/criar') }}">Randomizar novo paciente (Trial Prona)</a>
                </div>
            </div>
        </div>
    </body>
</html>
