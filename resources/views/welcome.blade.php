@extends('layouts.app')

@section('head')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cepeti - Estudo</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="/css/welcome.css">

@endsection
@section('content')
    <div class="flex-center full-height">
        <div class="content up">
            <div class="title m-b-md">
                Cepeti
            </div>

            <div class="links">
                <a class="options cloroquina" href="{{ url('/cl/pacientes') }}">Pacientes já randomizados (Trial Cloroquina)</a>
                <br>
                <a class="options cloroquina" href="{{ url('/cl/pacientes/criar') }}">Randomizar novo paciente (Trial Cloroquina)</a>
                <br>
                <a class="options prona" href="{{ url('/pr/pacientes') }}">Pacientes já randomizados (Trial Prona)</a>
                <br>
                <a class="options prona" href="{{ url('/pr/pacientes/criar') }}">Randomizar novo paciente (Trial Prona)</a>
            </div>
        </div>
    </div>
@endsection
