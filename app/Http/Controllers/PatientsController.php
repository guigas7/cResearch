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
        $patients = Patient::select('name', 'ventilator', 'estudo', 'inserted_on')
            ->orderBy('order')
            ->where('user_id', '=', Auth::id())
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
        // Busca se há paciente sem estudo
        $next = Patient::where('user_id', '=', Auth::id())
            ->whereNull('name')
            ->orderBy('order')
            ->first();
        // Se não há paciente para preencher
        if (is_null($next)) {
            // Gera novo bloco
            $max_block = Block::select('id')
                ->orderBy('id', 'DESC')
                ->first()->id;
            $block = Block::findOrFail(rand(1, $max_block));
            // create patients for the randomized block
            for ($i = 0; $i < strlen($block->sequence); $i++) {
                $order = Patient::where('user_id', '=', Auth::id())
                    ->orderBy('order', 'desc')
                    ->first();
                if (is_null($order)) { // Se não há nenhum registro
                    $order = 1;
                } else {
                    $order = $order->order + 1;
                }
                Patient::create([
                    'order' => $order,
                    'user_id' => Auth::id(),
                    'estudo' => ($block->sequence[$i] == 'Y' ? 1 : 0),
                ]);
                $next = Patient::where('user_id', '=', Auth::id())
                    ->whereNull('name')
                    ->orderBy('order')
                    ->first();
            }
        }
        // Se há paciente para preencher
        $next->ventilator = $request->ventilator;
        $next->name = $request->name;
        $next->inserted_on = now().date('');
        $next->save();
        return redirect('/pacientes');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        
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
