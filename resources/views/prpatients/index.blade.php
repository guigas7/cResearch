@extends('layouts.app')

@section('head')
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="/css/patients.css">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="content">
            <div class="title" style="width: 100%">
                Trial Prona
            </div>
            <dl class="m-b-md">
                <dt class="prona">[Azul]</dt>
                <dd class="prona"><b> - Prona</b></dd>

                <dt class="pcontrole">[Vermelho]</dt>
                <dd class="pcontrole"><b> - Controle</b></dd>
            </dl>
            <ul class="style1">
                @foreach ($patients as $patient)
                    <li>
                        <h3><a class="{{$patient->study ? 'prona' : 'pcontrole'}}" href="{{ route('prpatients.show', $patient) }}">{{ $patient->prontuario }} ({{$patient->hospital->name}})</a></h3>
                        <p>{{ $patient->inserted_on }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
