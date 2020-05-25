@extends('layouts.app')

@section('head')
    <link rel="stylesheet" type="text/css" href="/css/patients.css">
    <link rel="stylesheet" type="text/css" href="/css/form.css">
@endsection
    <style type="text/css">
    [type="radio"]:checked,
    [type="radio"]:not(:checked) {
        position: absolute;
        left: -9999px;
    }
</style>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="content">
            <div class="title pcontrole m-b-md" style="width: 100%">
                Trial Prona
            </div>
            <div class="cent fixedw">
                <div class="card">
                    <div class="card-header col-form-label">{{ __('Editar paciente') }}</div>

                    <div class="card-body">
                        <div class="cent">
                            <form method="POST" action="/pr/pacientes/{{ $patient->slug }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="prontuario" class="col-md-5 col-form-label text-md-center">{{ __('Número de prontuário do paciente') }}</label>

                                <div class="col-md-5">
                                    <input id="prontuario" type="number" class="form-control @error('prontuario') is-invalid @enderror" name="prontuario" value="{{ $patient->prontuario }}" required autofocus>

                                    @error('prontuario')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <hr>

                            <br>
                            <div class="form-group row mb-0">
                                <button id="enviar" type="submit" class="btn btn-primary bt">
                                    {{ __('Editar') }}
                                </button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="card">
                    <div class="card-header col-form-label">{{ __('Remover paciente') }}</div>

                    <div class="card-body">
                        <div class="cent">
                            <form method="POST" action="/pr/pacientes/{{ $patient->slug }}">
                                @csrf
                                @method('DELETE')

                                <div class="form-group row">
                                    <label  class="col-form-label col-md-10 offset-md-1 text-md-center">Ao apagar o paciente, será liberada uma vaga para o grupo {{ $patient->study ? 'Prona' : 'Controle' }} aos pacientes do hospital {{ $patient->hospital->name }} {{ $patient->ventilator ? 'com' : 'sem' }} ventilação mecânica</label>
                                </div>
                                <hr>

                                <div class="form-group row mb-0">
                                    <label for="confirm" class="col-md-5 col-form-label text-md-center">{{ __('Deseja apagar o paciente?') }}</label>

                                    <div class="col-md-2">
                                        <p>
                                            <input type="radio" id="vent-on" name="confirm" required value="1">
                                            <label for="vent-on">Sim</label>
                                        </p>
                                    </div>
                                </div>
                                <hr>

                                <br>
                                <div class="form-group row mb-0">
                                    <button id="enviar" type="submit" class="btn btn-primary bt">
                                        {{ __('Apagar') }}
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="title pcontrole m-b-md" style="width: 100%">
            Trial Prona
        </div>
    </div>
</div>
@endsection
