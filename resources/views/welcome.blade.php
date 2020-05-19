@extends('layouts.app')

@section('head')

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/patients.css">
    <link rel="stylesheet" type="text/css" href="/css/form.css">
    <link rel="stylesheet" type="text/css" href="/css/welcome.css">
@endsection

@section('content')
<div class="flex-center full-height">
    <div class="content">
        <div class="title m-b-md">
            Cepeti
        </div>

        <div class="links">
            <ul class="opt">
                @can ('view_list')
                    <li>
                        <a class="options cloroquina" href="{{ url('/cl/pacientes') }}">Pacientes já randomizados (Trial Cloroquina)</a>
                    </li>
                @endcan
                <li>
                    <a class="options cloroquina" href="{{ url('/cl/pacientes/criar') }}">Randomizar novo paciente (Trial Cloroquina)</a>
                </li>
                <li>
                    <a class="options cloroquina" href="{{ url('/cl/pacientes/buscar') }}">Procurar paciente (Trial Cloroquina)</a>
                </li>
                @can ('view_list')
                    <li>
                        <a class="options prona" href="{{ url('/pr/pacientes') }}">Pacientes já randomizados (Trial Prona)</a>
                    </li>
                @endcan
                <li>
                    <a class="options prona" href="{{ url('/pr/pacientes/criar') }}">Randomizar novo paciente (Trial Prona)</a>
                </li>
                <li>
                    <a class="options prona" href="{{ url('/pr/pacientes/buscar') }}">Procurar paciente (Trial Prona)</a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
