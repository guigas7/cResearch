<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clpatient;
use App\Block;
use App\User;
use App\Hospital;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
        $hospitais = Hospital::select('id', 'name')->get();
        return view('clpatients.create', compact('hospitais'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hospital = Hospital::findOrFail($request->hospital);
        // Busca se há paciente sem estudo
        $next = $hospital->nextEmptySlotCl($request->ventilator, $hospital->id);
        // Se não há paciente para preencher
        if (is_null($next)) {
            // -- Gera novo bloco
            $max_block = $hospital->maxBlock();
            // randomiza bloco
            $block = Block::findOrFail(rand(1, $max_block));
            // cria pacientes para o novo bloco randomizado
            $size = strlen($block->sequence);
            for ($i = 0; $i < $size; $i++) {
                $order = $hospital->getNextOrderCl($hospital->id);
                Clpatient::create([
                    'order' => $order,
                    'ventilator' => $request->ventilator,
                    'hospital_id' => $hospital->id,
                    'study' => ($block->sequence[$i] == 'Y' ? 1 : 0),
                ]);
                $next = $hospital->nextEmptySlotCl($request->ventilator, $hospital->id);
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
        $next = $hospital->nextEmptySlotCl($request->ventilator, $hospital->id);
        if (is_null($next)) {
            // -- Gera novo bloco
            $max_block = $hospital->maxBlock();
            // randomiza bloco
            $block = Block::findOrFail(rand(1, $max_block));
            // cria pacientes para o novo bloco randomizado
            $size = strlen($block->sequence);
            for ($i = 0; $i < $size; $i++) {
                $order = $hospital->getNextOrderCl($hospital->id);
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
                    ->sortBy('order')
                    ->where('ventilator', $groups);
                foreach ($p as $pat) {
                    $content .= ($pat->study == 1 ? 'cloroquina. ' : 'controle. ');
                }
                $content .= "\n";
            }
        }
        // Send email
        Mail::raw($content, function($message) {
            $message->to('jimhorton7@outlook.com')
            ->subject('Novo paciente');
        });
        return redirect(route('clpatients.show', $show));
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
        //   
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clpatient $patient)
    {
        //
    }
}
