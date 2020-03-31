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
                {{ Auth::user()->name}}
            </div>
            <dl class="m-b-md">
                <dt class="cloroquina">[Verde]</dt>
                <dd class="cloroquina"><b> - Cloroquina</b></dd>

                <dt class="controle">[Laranja]</dt>
                <dd class="controle"><b> - Controle</b></dd>
            </dl>
                @foreach ($groups as $key => $value)
                    <div class="sidebar {{ ($key != 1 ? 'vLine' : '') }}">
                        <h2>{{ $value }}</h2>
                        <ul class="style1">
                            @foreach ($patients as $patient)
                                @if ($patient->ventilator == $key)
                                    <li>
                                        <h3><a class="{{$patient->study ? 'cloroquina' : 'controle'}}" href="{{ route('patients.show', $patient) }}">{{ $patient->name }}</a></h3>
                                        <p>{{ $patient->inserted_on }}</p>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endforeach
        </div>
    </div>
</div>
@endsection
