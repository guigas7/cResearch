<?php

namespace App\Http\Controllers;

use App\Patient;
use App\Block;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PatientsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::select('name', 'slug', 'ventilator', 'study', 'inserted_on')
            ->orderBy('order')
            ->where('hospital_id', '=', Auth::id())
            ->whereNotNull('name')
            ->get();
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
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hospital = Auth::user();
        // Busca se há paciente sem estudo
        $next = $hospital->nextEmptySlot();
        // Se não há paciente para preencher
        if (is_null($next)) {
            // -- Gera novo bloco
            $max_block = $hospital->maxBlock();
            // randomiza bloco
            $block = Block::findOrFail(rand(1, $max_block));
            // cria pacientes para o novo bloco randomizado
            $size = strlen($block->sequence);
            for ($i = 0; $i < $size; $i++) {
                $order = $hospital->getNextOrder();
                Patient::create([
                    'order' => $order,
                    'hospital_id' => $hospital->id,
                    'study' => ($block->sequence[$i] == 'Y' ? 1 : 0),
                ]);
                $next = $hospital->nextEmptySlot();
            }
        }

        request()->validate([
            'name' => 'required',
            'ventilator' => 'required|boolean',
            'tcle' => 'required|accepted',
            'age' => 'required|accepted',
            'internado' => 'required|accepted',
            'coleta' => 'required|accepted',
            'sintomas' => 'required',
            'sintomas.*' => 'in:coriza,tosse,garganta,febre',
            'gravidade' => 'required',
            'gravidade.*' => 'in:radiografia,estertores,oxigeoterapia,ventilacao',
            'gravido' => 'required|accepted',
            'esfoliativa' => 'required|accepted',
            'porfiria' => 'required|accepted',
            'epilepsia' => 'required|accepted',
            'miastenia' => 'required|accepted',
            'glicose' => 'required|accepted',
            'hepatica' => 'required|accepted',
            'renal' => 'required|accepted',
            'cloroquina' => 'required|accepted',
        ]);
        // Se há paciente para preencher
        $next->update([
            'ventilator' => $request->ventilator,
            'name' => $request->name,
            'inserted_on' => now().date(''),
        ]);
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
        if ($patient->hospital->id == Auth::id()) {
            return view('patients.show', compact('patient'));
        } else {
            return redirect(route('patients.index'));
        }
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
