<?php

namespace App\Http\Controllers;

use App\Models\Rossi\Paciente;
use App\Models\Rossi\TipoDocumento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function getPacienteByDNI(Request $request, $dni)
    {
        /** @var Paciente $paciente */
        $paciente =  Paciente::query()
            ->where(Paciente::COLUMNA_NRO_DOCUMENTO, $dni)
            ->whereHas(Paciente::RELACION_TIPO_DOCUMENTO,fn(Builder $q) => $q->where(TipoDocumento::COLUMNA_DESCRIPCION, TipoDocumento::DESCRIPCION_DNI))
            ->firstOrFail()
        ;
        return [
            'id' => $paciente->pac_id,
            'nombre' => $paciente->pac_nombre,
            'apellido' => $paciente->pac_apellido,
            'tipoDocumento' => $paciente->tipoDocumento->tid_descripcion,
            'nroDocumento' => $paciente->pac_nro_documento,
            'calle' => $paciente->pac_domicilio_calle?:'',
            'nroCasa' => $paciente->pac_domicilio_nro?:'',
            'localidad' => $paciente->localidad?$paciente->localidad->loc_nombre:'',
            'provincia' => $paciente->provincia?$paciente->provincia->pro_nombre:'',
            'telefono' => $paciente->pac_telefono_movil,
            'email' => $paciente->pac_email?:'',
            'fechaNacimiento' => $paciente->pac_fecha_nacimiento,
            'crudo' => $paciente,
        ];
    }

    public function getPacienteNico(Request $request)
    {
        $paciente = Paciente::getFromDni('32144152');
        return [
            'id' => $paciente->pac_id,
            'nombre' => $paciente->pac_nombre,
            'apellido' => $paciente->pac_apellido,
            'tipoDocumento' => $paciente->tipoDocumento->tid_descripcion,
            'nroDocumento' => $paciente->pac_nro_documento,
            'calle' => $paciente->pac_domicilio_calle?:'Sin Nombre',
            'nroCasa' => $paciente->pac_domicilio_nro?:'123',
            'localidad' => $paciente->localidad?$paciente->localidad->loc_nombre:'Localidad Nico',
            'provincia' => $paciente->provincia?$paciente->provincia->pro_nombre:'Provicia Nico',
            'telefono' => $paciente->pac_telefono_movil?:'5491158102564',
            'email' => $paciente->pac_email?:'nicolas.finelli@skytel.com.ar',
            'fechaNacimiento' => $paciente->pac_fecha_nacimiento,
            'crudo' => $paciente,
        ];
    }

    public function getPacienteAldo(Request $request)
    {
        return [
            'id' => 1,
            'nombre' => 'Aldo',
            'apellido' => 'Ibarra',
            'tipoDocumento' => 'dni',
            'nroDocumento' => '4047478',
            'calle' => 'Sin Nombre',
            'nroCasa' => '123',
            'localidad' => 'Localidad Aldo',
            'provincia' => 'Provicia Aldo',
            'telefono' => '595971639734',
            'email' => 'aldo.ibarra@skytel.com.py',
            'fechaNacimiento' => null,
        ];
    }
}
