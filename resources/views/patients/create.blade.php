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
                    <div class="titulo">
                        <h3>Critérios de Inclusão</h3>
                    </div>
                    <hr>

                    <div class="cent">
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

                        <div class="form-group row mb-0">
                            <label for="ventilator" class="col-md-8 col-form-label text-md-right">{{ __('Este paciente está em intubação e em ventilação mecânica?') }}</label>

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

                        <div class="form-group row mb-0">
                            <label for="tcle" class="col-md-8 col-form-label text-md-right">{{ __('Paciente ou seu responsável legal assinou o TCLE?') }}</label>

                            <div class="col-md-2">
                                <p>
                                    <input class="inclusion change" type="radio" id="tcle-on" name="tcle" required value="1">
                                    <label for="tcle-on">Sim</label>
                                </p>
                                <p>
                                    <input class="change" type="radio" id="tcle-off" name="tcle" value="0">
                                    <label for="tcle-off">Não</label>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row mb-0">
                            <label for="age" class="col-md-8 col-form-label text-md-right">{{ __('Paciente tem 18 anos ou mais?') }}</label>

                            <div class="col-md-2">
                                <p>
                                    <input class="inclusion change" type="radio" id="age-on" name="age" required value="1">
                                    <label for="age-on">Sim</label>
                                </p>
                                <p>
                                    <input class="change" type="radio" id="age-off" name="age" value="0">
                                    <label for="age-off">Não</label>
                                </p>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row mb-0">
                            <label for="internado" class="col-md-8 col-form-label text-md-right">{{ __('Paciente está internado?') }}</label>

                            <div class="col-md-2">
                                <p>
                                    <input class="inclusion change" type="radio" id="internado-on" name="internado" required value="1">
                                    <label for="internado-on">Sim</label>
                                </p>
                                <p>
                                    <input class="change" type="radio" id="internado-off" name="internado" value="0">
                                    <label for="internado-off">Não</label>
                                </p>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row mb-0">
                            <label for="coleta" class="col-md-8 col-form-label text-md-right">{{ __('Já foi solicitada coleta ou já foi coletado material para teste de laboratório para diagnóstico de infecção por SARS-CoV-2?') }}</label>

                            <div class="col-md-2">
                                <p>
                                    <input class="inclusion change" type="radio" id="coleta-on" name="coleta" required value="1">
                                    <label for="coleta-on">Sim</label>
                                </p>
                                <p>
                                    <input class="change" type="radio" id="coleta-off" name="coleta" value="0">
                                    <label for="coleta-off">Não</label>
                                </p>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row mb-0 cent group">
                            <label for="sintomas[]" class="col-md-6 col-form-label text-md-right">{{ __('Paciente apresenta quais dos seguintes sintomas gripais?') }}</label>

                            <div class="col-md-6">
                                <div class="checkboxDiv">
                                    <label class="checkbox-label" for="coriza">
                                        <input class="groupa change" type="checkbox" name="sintomas[]" id="coriza" value="coriza">
                                        <span class="checkbox-custom"></span>
                                        <span class="checkText">Coriza</span>
                                    </label>
                                </div>
                                <div class="checkboxDiv">
                                    <label class="checkbox-label" for="tosse">
                                        <input class="groupa change" type="checkbox" name="sintomas[]" id="tosse" value="tosse">
                                        <span class="checkbox-custom"></span>
                                        <span class="checkText">Tosse seca ou produtiva</span>
                                    </label>
                                </div>
                                <div class="checkboxDiv">
                                    <label class="checkbox-label" for="garganta">
                                        <input class="groupa change" type="checkbox" name="sintomas[]" id="garganta" value="garganta">
                                        <span class="checkbox-custom"></span>
                                        <span class="checkText">Dor de garganta</span>
                                    </label>
                                </div>
                                <div class="checkboxDiv">
                                    <label class="checkbox-label" for="febre">
                                        <input class="groupa change" type="checkbox" name="sintomas[]" id="febre" value="febre">
                                        <span class="checkbox-custom"></span>
                                        <span class="checkText">Febre</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row mb-0 cent group">
                            <label for="gravidade[]" class="col-md-6 col-form-label text-md-right">{{ __('Paciente apresenta quais dos seguintes critérios de gravidade') }}</label>

                            <div class="col-md-6">
                                <div class="checkboxDiv">
                                    <label class="checkbox-label" for="radiografia">
                                        <input class="groupb change" type="checkbox" name="gravidade[]" id="radiografia" value="radiografia">
                                        <span class="checkbox-custom"></span>
                                        <span class="checkText">Infiltrados radiográficos por imagem ou padrão de vidro fosco (radiografia de tórax, tomografia computadorizada, etc.)</span>
                                    </label>
                                </div>
                                <div class="checkboxDiv">
                                    <label class="checkbox-label" for="estertores">
                                        <input class="groupb change" type="checkbox" name="gravidade[]" id="estertores" value="estertores">
                                        <span class="checkbox-custom"></span>
                                        <span class="checkText">SpO2 ≤ 94% no ar ambiente e avaliação clínica com evidência de estertores</span>
                                    </label>
                                </div>
                                <div class="checkboxDiv">
                                    <label class="checkbox-label" for="oxigenoterapia">
                                        <input class="groupb change" type="checkbox" name="gravidade[]" id="oxigenoterapia" value="oxigenoterapia">
                                        <span class="checkbox-custom"></span>
                                        <span class="checkText">Necessidade de oxigenoterapia suplementar</span>
                                    </label>
                                </div>
                                <div class="checkboxDiv">
                                    <label class="checkbox-label" for="ventilacao">
                                        <input class="groupb change" type="checkbox" name="gravidade[]" id="ventilacao" value="ventilacao">
                                        <span class="checkbox-custom"></span>
                                        <span class="checkText">Necessidade de ventilação mecânica</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="titulo">
                            <h3>Critérios de Exclusão</h3>
                        </div>
                        <hr>

                        <div class="form-group row mb-0">
                            <label for="gravido" class="col-md-8 col-form-label text-md-right">{{ __('Paciente está gravido ou amamentando?') }}</label>

                            <div class="col-md-2">
                                <p>
                                    <input class="change" class="inclusion" type="radio" id="gravido-on" name="gravido" required value="0">
                                    <label for="gravido-on">Sim</label>
                                </p>
                                <p>
                                    <input class="inclusion change" type="radio" id="gravido-off" name="gravido" value="1">
                                    <label for="gravido-off">Não</label>
                                </p>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row mb-0">
                            <label for="esfoliativa" class="col-md-8 col-form-label text-md-right">{{ __('Paciente é portador de psoríase ou outra doença esfoliativa?') }}</label>

                            <div class="col-md-2">
                                <p>
                                    <input class="change" type="radio" id="esfoliativa-on" name="esfoliativa" required value="0">
                                    <label for="esfoliativa-on">Sim</label>
                                </p>
                                <p>
                                    <input class="inclusion change" type="radio" id="esfoliativa-off" name="esfoliativa" value="1">
                                    <label for="esfoliativa-off">Não</label>
                                </p>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row mb-0">
                            <label for="porfiria" class="col-md-8 col-form-label text-md-right">{{ __('Paciente é portador de porfiria?') }}</label>

                            <div class="col-md-2">
                                <p>
                                    <input class="change" type="radio" id="porfiria-on" name="porfiria" required value="0">
                                    <label for="porfiria-on">Sim</label>
                                </p>
                                <p>
                                    <input class="inclusion change" type="radio" id="porfiria-off" name="porfiria" value="1">
                                    <label for="porfiria-off">Não</label>
                                </p>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row mb-0">
                            <label for="epilepsia" class="col-md-8 col-form-label text-md-right">{{ __('Paciente é portador de epilepsia?') }}</label>

                            <div class="col-md-2">
                                <p>
                                    <input class="change" type="radio" id="epilepsia-on" name="epilepsia" required value="0">
                                    <label for="epilepsia-on">Sim</label>
                                </p>
                                <p>
                                    <input class="inclusion change" type="radio" id="epilepsia-off" name="epilepsia" value="1">
                                    <label for="epilepsia-off">Não</label>
                                </p>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row mb-0">
                            <label for="miastenia" class="col-md-8 col-form-label text-md-right">{{ __('Paciente é portador de miastenia gravis?') }}</label>

                            <div class="col-md-2">
                                <p>
                                    <input class="change" type="radio" id="miastenia-on" name="miastenia" required value="0">
                                    <label for="miastenia-on">Sim</label>
                                </p>
                                <p>
                                    <input class="inclusion change" type="radio" id="miastenia-off" name="miastenia" value="1">
                                    <label for="miastenia-off">Não</label>
                                </p>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row mb-0">
                            <label for="glicose" class="col-md-8 col-form-label text-md-right">{{ __('Paciente é portador de deficiência de glicose-6-fosfato desidrogenase?') }}</label>

                            <div class="col-md-2">
                                <p>
                                    <input class="change" type="radio" id="glicose-on" name="glicose" required value="0">
                                    <label for="glicose-on">Sim</label>
                                </p>
                                <p>
                                    <input class="inclusion change" type="radio" id="glicose-off" name="glicose" value="1">
                                    <label for="glicose-off">Não</label>
                                </p>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row mb-0">
                            <label for="hepatica" class="col-md-8 col-form-label text-md-right">{{ __('Paciente é portador de doença/insuficiência hepática?') }}</label>

                            <div class="col-md-2">
                                <p>
                                    <input class="change" type="radio" id="hepatica-on" name="hepatica" required value="0">
                                    <label for="hepatica-on">Sim</label>
                                </p>
                                <p>
                                    <input class="inclusion change" type="radio" id="hepatica-off" name="hepatica" value="1">
                                    <label for="hepatica-off">Não</label>
                                </p>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row mb-0">
                            <label for="renal" class="col-md-8 col-form-label text-md-right">{{ __('Paciente é portador de doença renal crônica estágio 4 ou que requer diálise?') }}</label>

                            <div class="col-md-2">
                                <p>
                                    <input class="change" type="radio" id="renal-on" name="renal" required value="0">
                                    <label for="renal-on">Sim</label>
                                </p>
                                <p>
                                    <input class="inclusion change" type="radio" id="renal-off" name="renal" value="1">
                                    <label for="renal-off">Não</label>
                                </p>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row mb-0">
                            <label for="cloroquina" class="col-md-8 col-form-label text-md-right">{{ __('Paciente tem alergia conhecida à Cloroquina?') }}</label>

                            <div class="col-md-2">
                                <p>
                                    <input class="change" type="radio" id="cloroquina-on" name="cloroquina" required value="0">
                                    <label for="cloroquina-on">Sim</label>
                                </p>
                                <p>
                                    <input class="inclusion change" type="radio" id="cloroquina-off" name="cloroquina" value="1">
                                    <label for="cloroquina-off">Não</label>
                                </p>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row mb-0">
                            <button id="enviar" type="submit" class="btn btn-primary bt" disabled>
                                {{ __('Randomizar') }}
                            </button>
                        </div>

                        <div id="ermsg" class="titulo">
                            <strong>O paciente deve satisfazer todos os critérios de Inclusão e Exclusão</strong>
                        </div>
                    </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="title controle m-b-md" style="width: 100%">
        {{ Auth::user()->name }}
    </div>
</div>
@endsection

@section('script')
    <script src="/js/form.js"></script>
@endsection