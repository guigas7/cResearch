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
<div class="content">
    <div class="title controle m-b-md" style="width: 100%">
        Cepeti - Trial Cloroquina
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Randomizar novo paciente') }}</div>

                <div class="card-body">
                    <div class="cent">
                        <form method="POST" action="/cl/pacientes">
                        @csrf

                        <div class="form-group row">
                            <label for="prontuario" class="col-md-5 col-form-label text-md-center">{{ __('Número de prontuário do paciente') }}</label>

                            <div class="col-md-5">
                                <input id="prontuario" type="number" class="form-control @error('prontuario') is-invalid @enderror" name="prontuario" value="{{ old('prontuario') }}" required autofocus>

                                @error('prontuario')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row">
                            <label for="hospital" class="col-md-5 col-form-label text-md-center">{{ __('Hospital') }}</label>

                            <div class="col-md-5">
                                <select id="hospital" name="hospital">
                                @foreach ($hospitais as $hospital)
                                    <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row mb-0">
                            <label for="ventilator" class="col-md-5 col-form-label text-md-center">{{ __('Este paciente está em intubação e em ventilação mecânica?') }}</label>

                            <div class="col-md-2">
                                <p>
                                    <input type="radio" id="vent-on" name="ventilator" required value="1">
                                    <label for="vent-on">Sim</label>
                                </p>
                                <p>
                                    <input type="radio" id="vent-off" name="ventilator" value="0">
                                    <label for="vent-off">Não</label>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <br>

                        <div class="form-group row mb-0">
                            <button id="enviar" type="submit" class="btn btn-primary bt">
                                {{ __('Randomizar') }}
                            </button>
                        </div>

                    </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="title controle m-b-md" style="width: 100%">
        Cepeti - Trial Cloroquina
    </div>
</div>
@endsection