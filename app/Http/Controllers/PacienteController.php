<?php

namespace App\Http\Controllers;

use App\Models\Rossi\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function getPacienteByDNI(Request $request, $dni)
    {
        return Paciente::where(Paciente::COLUMNA_NRO_DOCUMENTO, $dni)->firstOrFail();
    }
}
