@extends('layouts.app')

@section('head')
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="/css/form.css">
    <link rel="stylesheet" type="text/css" href="/css/patients.css">
@endsection

@section('content')
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="content">
                <div class="title" style="width: 100%">
                    O paciente <span class="{{ $patient->study ? 'prona' : 'pcontrole' }}"><b>{{ $patient->prontuario }}</b></span> foi alocado para o grupo <span class="{{ $patient->study ? 'prona' : 'pcontrole' }}"><b>{{ $patient->study ? 'Prona' : 'Controle' }}</b></span>
                </div>
            </div>
        </div>
    </div>
    @can ('edit')
        <div class="cent d-table">
            <a class="btn btn-primary pbt" href="{{ url('/pr/pacientes/' . $patient->slug . '/editar') }}">
                {{ __('Editar') }}
            </a>
        </div>
    @endcan
@endsection
