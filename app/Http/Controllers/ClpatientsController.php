<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clpatient;
use App\Block;
use App\User;
use App\Hospital;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ClpatientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Clpatient::select('prontuario', 'slug', 'ventilator', 'study', 'inserted_on', 'hospital_id')
            ->orderBy('inserted_on', 'DESC')
            ->with('hospital')
            ->whereNotNull('prontuario')
            ->get();
        foreach ($patients as $patient) {
            $patient->inserted_on = date('d/m/Y H:i', strtotime($patient->inserted_on));
        }
        $groups = ([
            1 => "Com Ventilação",
            0 => "Sem Ventilação"
        ]);
        return view('clpatients.index', compact('patients', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hospitals = Hospital::select('id', 'name')->where('cl', 1)->get();
        return view('clpatients.create', compact('hospitals'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validatePassword($request->only('password'))->validate();
        $credentials = ['login' => 'plantonista']; // fixed user
        $credentials = array_merge($credentials, $request->only('password')); // gets access key
        if (Auth::check() || Auth::attempt($credentials, 1)) {
            $this->validatePatient($request)->validate();
            // Authentication passed...
            $hospital = Hospital::findOrFail($request->hospital);
            // Busca se há paciente sem estudo
            $next = $hospital->nextEmptySlotCl($request->ventilator);
            // Se não há paciente para preencher
            if (is_null($next)) {
                // -- Gera novo bloco
                $max_block = $hospital->maxBlock();
                // randomiza bloco
                $block = Block::findOrFail(rand(1, $max_block));
                // cria pacientes para o novo bloco randomizado
                $size = strlen($block->sequence);
                for ($i = 0; $i < $size; $i++) {
                    $order = $hospital->getNextOrderCl();
                    Clpatient::create([
                        'order' => $order,
                        'ventilator' => $request->ventilator,
                        'hospital_id' => $hospital->id,
                        'study' => ($block->sequence[$i] == 'Y' ? 1 : 0),
                    ]);
                    $next = $hospital->nextEmptySlotCl($request->ventilator);
                }
            }
            // Se há paciente para preencher
            $next->update([
                'prontuario' => $request->prontuario,
                'inserted_on' => now().date(''),
            ]);
            $content = 'Paciente ' . $next->prontuario . ', do hospital ' . $next->hospital->name . ' foi randomizado para o grupo ' . ($next->study ? 'cloroquina' : 'controle') . '!';
            $show = $next;
            // Se esvaziou, cria novamente
            $next = $hospital->nextEmptySlotCl($request->ventilator);
            if (is_null($next)) {
                // -- Gera novo bloco
                $max_block = $hospital->maxBlock();
                // randomiza bloco
                $block = Block::findOrFail(rand(1, $max_block));
                // cria pacientes para o novo bloco randomizado
                $size = strlen($block->sequence);
                for ($i = 0; $i < $size; $i++) {
                    $order = $hospital->getNextOrderCl();
                    Clpatient::create([
                        'order' => $order,
                        'ventilator' => $request->ventilator,
                        'hospital_id' => $hospital->id,
                        'study' => ($block->sequence[$i] == 'Y' ? 1 : 0),
                    ]);
                }
            } // não preenche nenhum dos blocos novos
            // Makes email
            $hospitals = Hospital::all();
            foreach ($hospitals as $hosp) {
                $content .= "\n ---" . $hosp->name . "---\n";
                for ($groups = 1; $groups >=0; $groups--) {
                    $content.= ($groups == 1 ? "\tCom Ventilação: " : "\tSem Ventilação: ");
                    $p = $hosp->patientsCl->whereNull('prontuario')
                        ->sortBy('id')
                        ->where('ventilator', $groups);
                    foreach ($p as $pat) {
                        $content .= ($pat->study == 1 ? 'cloroquina. ' : 'controle. ');
                    }
                    $content .= "\n";
                }
            }
            // Send email
            Mail::raw($content, function($message) {
                // $message->to('randomizacao.cepeti@gmail.com')
                $message->to('jimhorton7@outlook.com')
                ->subject('Novo paciente (Trial Cloroquina/controle)');
            });
            return redirect(route('clpatients.show', $show));
        } else { // if not authenticated
            throw ValidationException::withMessages(['password' => 'Chave de acesso inválida']);
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Clpatient $patient)
    {
        return view('clpatients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Clpatient $patient)
    {
        $patient = Clpatient::with('hospital')->find($patient->id);
        return view('clpatients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Clpatient $patient)
    {
        $this->validateEdit($request)->validate();
        $patient->update([
            'prontuario' => $request->prontuario,
        ]);
        return redirect('/cl/pacientes/' . $patient->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clpatient $patient)
    {
        $patient->update([
            'prontuario' => NULL,
            'inserted_on' => NULL,
        ]);
        return redirect('clpatients.index');
    }

    /**
     * Show the form to search for a patient.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        $hospitals = Hospital::select('id', 'name')->where('cl', 1)->get();
        return view('clpatients.search', compact('hospitals'));
    }

    /**
     * finds specified patient.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function find(Request $request)
    {
        $this->validatePassword($request->only('password'))->validate();
        $credentials = ['login' => 'plantonista']; // fixed user
        $credentials = array_merge($credentials, $request->only('password')); // gets access key
        if (Auth::check() || Auth::attempt($credentials, 1)) {
            $this->validateSearch($request)->validate();
            // Authentication passed...
            $hospital = Hospital::findOrFail($request->hospital);
            $patient = $hospital->findPatientCl($request->prontuario);
            return view('clpatients.show', compact('patient'));
        } else { // if not authenticated
            throw ValidationException::withMessages(['password' => 'Chave de acesso inválida']);
        } 
    }

    protected function validatePatient(Request $request)
    {
        $messages = [
            'prontuario.unique' => 'Paciente :input já foi randomizado no hospital selecionado',
        ];
        return Validator::make($request->all(), [
            'hospital' => ['required', 'integer'],
            'ventilator' => ['required', 'boolean'],
            'prontuario' => ['required', 'numeric',
                Rule::unique('App\Clpatient', 'prontuario')->where(function ($query) {
                    return $query->where('hospital_id', request('hospital'));
                })
            ],
        ], $messages);
    }

    protected function validatePassword($request)
    {
        $messages = [
            'password'  => 'Chave de acesso inválida',
        ];
        return Validator::make($request, [
            'password' => ['sometimes', 'string', 'required'],
        ], $messages);
    }

    protected function validateSearch(Request $request)
    {
        $messages = [
            'prontuario.exists' => 'Paciente :input não foi randomizado no hospital selecionado',
            'hospital.exists'  => 'O hospital selecionado não faz parte desse trial',
        ];
        return Validator::make($request->all(), [
            'hospital' => ['required', 'integer',
                Rule::exists('App\Hospital', 'id')->where(function ($query) {
                    $query->where('cl', 1);
                })],
            'prontuario' => ['required', 'numeric',
                Rule::exists('App\Clpatient', 'prontuario')->where(function ($query) {
                    $query->where('hospital_id', request('hospital'));
                 })
            ],
        ], $messages);
    }

    protected function validateEdit(Request $request)
    {
        $messages = [
            'prontuario.unique' => 'Paciente :input já foi randomizado no hospital selecionado',
        ];
        Validator::make($request->all(), [
            'prontuario' => ['required', 'numeric',
                Rule::unique('App\Clpatient', 'prontuario')->where(function ($query) {
                    return $query->where('hospital_id', request('hospital'));
                })
            ],
        ], $messages);
    }
}
