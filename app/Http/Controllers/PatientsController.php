<?php

namespace App\Http\Controllers;

use App\Patient;
use App\Block;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class PatientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::select('prontuario', 'slug', 'ventilator', 'study', 'inserted_on', 'hospital_id')
            ->orderBy('id', 'DESC')
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
        return view('patients.index', compact('patients', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hospitais = User::select('id', 'name')->get();
        return view('patients.create', compact('hospitais'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hospital = User::findOrFail($request->hospital);
        // Busca se há paciente sem estudo
        $next = $hospital->nextEmptySlot($request->ventilator, $hospital->id);
        // Se não há paciente para preencher
        if (is_null($next)) {
            // -- Gera novo bloco
            $max_block = $hospital->maxBlock();
            // randomiza bloco
            $block = Block::findOrFail(rand(1, $max_block));
            // cria pacientes para o novo bloco randomizado
            $size = strlen($block->sequence);
            for ($i = 0; $i < $size; $i++) {
                $order = $hospital->getNextOrder($hospital->id);
                Patient::create([
                    'order' => $order,
                    'ventilator' => $request->ventilator,
                    'hospital_id' => $hospital->id,
                    'study' => ($block->sequence[$i] == 'Y' ? 1 : 0),
                ]);
                $next = $hospital->nextEmptySlot($request->ventilator, $hospital->id);
            }
        }
        // Se há paciente para preencher
        $next->update([
            'prontuario' => $request->prontuario,
            'inserted_on' => now().date(''),
        ]);
        // Send email
        $content = 'Paciente ' . $next->prontuario . ', do hospital ' . $next->hospital->name . ' foi randomizado para o grupo ' . ($next->study ? 'cloroquina' : 'controle') . '!';
        Mail::raw($content, function($message) {
            $message->to('randomizacao.cepeti@gmail.com')
            ->subject('Novo paciente');
        });
        //dd($next);
        return redirect(route('patients.show', $next));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
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
    public function update(Request $request, Patient $patient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        //
    }
}
