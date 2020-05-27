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
    <div class="content up">
        <div class="title m-b-md">
            Cepeti
        </div>

        <div class="links">
            <ul class="opt">
                @if (Route::has('clpatients.index'))
                    @can ('view_list')
                        <li>
                            <a class="options cloroquina" href="{{ url('/cl/pacientes') }}">Pacientes já randomizados (Trial Cloroquina)</a>
                        </li>
                    @endcan
                @endif
                @if (Route::has('clpatients.create'))
                    <li>
                        <a class="options cloroquina" href="{{ url('/cl/pacientes/criar') }}">Randomizar novo paciente (Trial Cloroquina)</a>
                    </li>
                @endif
                @if (Route::has('clpatients.search'))
                    <li>
                        <a class="options cloroquina" href="{{ url('/cl/pacientes/buscar') }}">Procurar paciente (Trial Cloroquina)</a>
                    </li>
                @endif
                <hr class="sep">
                @if (Route::has('prpatients.index'))
                    @can ('view_list')
                        <li>
                            <a class="options prona" href="{{ url('/pr/pacientes') }}">Pacientes já randomizados (Trial Prona)</a>
                        </li>
                    @endcan
                @endif
                @if (Route::has('prpatients.create'))
                    <li>
                        <a class="options prona" href="{{ url('/pr/pacientes/criar') }}">Randomizar novo paciente (Trial Prona)</a>
                    </li>
                @endif
                @if (Route::has('prpatients.search'))
                    <li>
                        <a class="options prona" href="{{ url('/pr/pacientes/buscar') }}">Procurar paciente (Trial Prona)</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endsection
