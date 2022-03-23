<?php

namespace App\Http\Controllers;

use App\Models\Rossi\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class PacienteController extends Controller
{
    public function getPacienteByDNI(Request $request, $dni)
    {
        return Paciente::where(Paciente::COLUMNA_NRO_DOCUMENTO, $dni)->firstOrFail();
    }
}
