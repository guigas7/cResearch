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
                Trial Cloroquina
            </div>
            <div class="card">
                <div class="card-header col-form-label">{{ __('Buscar paciente') }}</div>

                <div class="card-body">
                    <div class="cent">
                        <form method="POST" action="/cl/pacientes/buscar">
                            @csrf

                            @error('not_found')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
                                    @foreach ($hospitals as $hospital)
                                        <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            <hr>

                            @guest
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label for="password" class="col-form-label text-md-center">{{ __('Chave de acesso') }}</label>
                                    <br>
                                    <p class="description text-md-center">pode ser encontrada no formulário do redcap</p>
                                </div>
                            

                                <div class="col-md-5">
                                    <input id="password" type="text" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autofocus>

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            @endguest

                            <br>
                            <div class="form-group row mb-0">
                                <button id="enviar" type="submit" class="btn btn-primary bt">
                                    {{ __('Buscar') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection