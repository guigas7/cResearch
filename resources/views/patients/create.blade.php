@extends('layouts.app')

@section('head')
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
    <div class="title controle m-b-md" style="width: 100%">
        {{ Auth::user()->name }}
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Randomizar novo paciente') }}</div>

                <div class="card-body">
                    <form method="POST" action="/pacientes">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nome do paciente') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="ventilator" class="col-md-4 col-form-label text-md-right">{{ __('Este paciente está em intubação e em ventilação mecânica?') }}</label>

                            <div class="col-md-6">
                                <p>
                                    <input type="radio" id="on" name="ventilator" value="1">
                                    <label for="on">Sim</label>
                                </p>
                                <p>
                                    <input type="radio" id="off" name="ventilator" value="0" checked>
                                    <label for="off">Não</label>
                                </p>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Randomizar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
