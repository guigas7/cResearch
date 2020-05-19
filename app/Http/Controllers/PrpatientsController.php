<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Prpatient;
use App\Block;
use App\User;
use App\Hospital;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class PrpatientsController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Prpatient::select('prontuario', 'slug', 'study', 'inserted_on', 'hospital_id')
            ->orderBy('inserted_on', 'DESC')
            ->with('hospital')
            ->whereNotNull('prontuario')
            ->get();
        foreach ($patients as $patient) {
            $patient->inserted_on = date('d/m/Y H:i', strtotime($patient->inserted_on));
        }
        return view('prpatients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hospitals = Hospital::select('id', 'name')->where('pr', 1)->get();
        return view('prpatients.create', compact('hospitals'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credentials = ['login' => 'plantonista']; // fixed user
        $credentials = array_merge($credentials, $request->only('password')); // gets access key
        if (Auth::check() || Auth::attempt($credentials, 1)) {
            // Authentication passed...
            $hospital = Hospital::findOrFail($request->hospital);
            $next = $hospital->nextEmptySlotPr();
            // Se não há paciente para preencher
            if (is_null($next)) {
                // -- Gera novo bloco
                $max_block = $hospital->maxBlock();
                // randomiza bloco
                $block = Block::findOrFail(rand(1, $max_block));
                // cria pacientes para o novo bloco randomizado
                $size = strlen($block->sequence);
                for ($i = 0; $i < $size; $i++) {
                    $order = $hospital->getNextOrderPr();
                    Prpatient::create([
                        'order' => $order,
                        'study' => ($block->sequence[$i] == 'Y' ? 1 : 0),
                    ]);
                    $next = $hospital->nextEmptySlotPr();
                }
            }
            // Se há paciente para preencher
            $next->update([
                'prontuario' => $request->prontuario,
                'hospital_id' => $hospital->id,
                'inserted_on' => now().date(''),
            ]);
            $content = 'Paciente ' . $next->prontuario . ', do hospital ' . $next->hospital->name . ' foi randomizado para o grupo ' . ($next->study ? 'Prona' : 'controle') . '!';
            $show = $next;
            // Se esvaziou, cria novamente
            $next = $hospital->nextEmptySlotPr();
            if (is_null($next)) {
                // -- Gera novo bloco
                $max_block = $hospital->maxBlock();
                // randomiza bloco
                $block = Block::findOrFail(rand(1, $max_block));
                // cria pacientes para o novo bloco randomizado
                $size = strlen($block->sequence);
                for ($i = 0; $i < $size; $i++) {
                    $order = $hospital->getNextOrderPr();
                    Prpatient::create([
                        'order' => $order,
                        'study' => ($block->sequence[$i] == 'Y' ? 1 : 0),
                    ]);
                }
            } // não preenche nenhum dos blocos novos
            // Makes email
            $content.= "\nPróximos pacientes: ";
            $p = $hospital->patientsPr->whereNull('prontuario')
                ->sortBy('id');
            foreach ($p as $pat) {
                $content .= ($pat->study == 1 ? 'prona. ' : 'controle. ');
            }
            $content .= "\n";
            // Send email
            Mail::raw($content, function($message) {
                // $message->to('randomizacao.cepeti@gmail.com')
                $message->to('jimhorton7@outlook.com')
                ->subject('Novo paciente (Trial Prona/controle)');
            });
            return redirect(route('prpatients.show', $show));
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
    public function show(Prpatient $patient)
    {
        return view('prpatients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Prpatient $patient)
    {
        //   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prpatient $patient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prpatient $patient)
    {
        //
    }


    /**
     * Show the form to search for a patient.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        $hospitals = Hospital::select('id', 'name')->where('pr', 1)->get();
        return view('prpatients.search', compact('hospitals'));
    }

    /**
     * finds specified patient.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function find(Request $request)
    {
        $credentials = ['login' => 'plantonista']; // fixed user
        $credentials = array_merge($credentials, $request->only('password')); // gets access key
        if (Auth::check() || Auth::attempt($credentials, 1)) {
            // Authentication passed...
            $hospital = Hospital::findOrFail($request->hospital);
            $patient = $hospital->findPatientPr($request->prontuario);
            if ($patient == null) {
                $message = 'Paciente ' . $request->prontuario . ' não foi randomizado em ' . $hospital->name;
                throw ValidationException::withMessages(['prontuario' => $message]);
            } else {
                return view('prpatients.show', compact('patient'));
            }
        } else { // if not authenticated
            throw ValidationException::withMessages(['password' => 'Chave de acesso inválida']);
        } 
    }
}
