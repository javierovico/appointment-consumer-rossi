<?php

namespace App\Http\Controllers;

use App\Models\Rossi\Paciente;
use App\Models\Rossi\TipoDocumento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PacienteController extends Controller
{

    const PACIENTES_DNI_TEST = [
        '23903400' => [
            'id' => 10,
            'nombre' => 'Daniel Esteban',
            'apellido' => 'Belarducci',
            'tipoDocumento' => 'dni',
            'nroDocumento' => '23903400',
            'calle' => 'Sin Nombre',
            'nroCasa' => '123',
            'localidad' => 'Localidad Nom',
            'provincia' => 'Provicia Nom',
            'telefono' => '5491168193810',
            'email' => 'nicolas.finelli@skytel.com.ar',
            'fechaNacimiento' => null,
        ],
        '25826740' => [
            'id' => 11,
            'nombre' => 'Carina',
            'apellido' => 'Trenes',
            'tipoDocumento' => 'dni',
            'nroDocumento' => '25826740',
            'calle' => 'Sin Nombre',
            'nroCasa' => '123',
            'localidad' => 'Localidad Nom',
            'provincia' => 'Provicia Nom',
            'telefono' => '5491168193752',
            'email' => 'nicolas.finelli@skytel.com.ar',
            'fechaNacimiento' => null,
        ],
        '44554184' => [
            'id' => 12,
            'nombre' => 'Aldana',
            'apellido' => 'Macrino',
            'tipoDocumento' => 'dni',
            'nroDocumento' => '44554184',
            'calle' => 'Sin Nombre',
            'nroCasa' => '123',
            'localidad' => 'Localidad Nom',
            'provincia' => 'Provicia Nom',
            'telefono' => '5491140250866',
            'email' => 'nicolas.finelli@skytel.com.ar',
            'fechaNacimiento' => null,
        ],
        '4047478' => [
            'id' => 13,
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
        ],
        '32144152' => [
            'id' => 14,
            'nombre' => 'Nicolas',
            'apellido' => 'Finelli',
            'tipoDocumento' => 'dni',
            'nroDocumento' => '32144152',
            'calle' => 'Sin Nombre',
            'nroCasa' => '123',
            'localidad' => 'Localidad Nico',
            'provincia' => 'Provicia Nico',
            'telefono' => '5491158102564',
            'email' => 'nicolas.finelli@skytel.com.ar',
            'fechaNacimiento' => null,
        ]
    ];

    public function getPaciente(Request $request)
    {
        $request->validate([
            'tipoDocumento' => 'required|in:DNI,LC,LE,CI,PASS',
            'numeroDocumento' => 'required'
        ]);
        $numeroDocumento = $request->get('numeroDocumento');
        switch ($request->get('tipoDocumento')) {
            case 'DNI':
                $tipoDocumento = TipoDocumento::DESCRIPCION_DNI;
                break;
            case 'LC':
                $tipoDocumento = TipoDocumento::DESCRIPCION_LC;
                break;
            case 'LE':
                $tipoDocumento = TipoDocumento::DESCRIPCION_LE;
                break;
            case 'CI':
                $tipoDocumento = TipoDocumento::DESCRIPCION_CI;
                break;
            case 'PASS':
                $tipoDocumento = TipoDocumento::DESCRIPCION_PASS;
                break;
            default:
                throw new \RuntimeException("Tipo Documento No definido");
        }
        /** INICIO Zona de pruebas temporales */
        if ($tipoDocumento == 'DNI' and array_key_exists($numeroDocumento, self::PACIENTES_DNI_TEST)) {
            return self::PACIENTES_DNI_TEST[$numeroDocumento];
        }
        /** FIN Zona de pruebas temporales */
        /** @var Paciente $paciente */
        $paciente =  Paciente::query()
            ->where(Paciente::COLUMNA_NRO_DOCUMENTO, $numeroDocumento)
            ->whereHas(Paciente::RELACION_TIPO_DOCUMENTO,fn(Builder $q) => $q->where(TipoDocumento::COLUMNA_DESCRIPCION, $tipoDocumento))
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

}
