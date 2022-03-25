<?php

namespace App\Models\Rossi;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Paciente
 * @package App\Models\Rossi
 * @property mixed pac_id
 * @property mixed pac_nombre
 * @property mixed pac_apellido
 * @property mixed pac_tipo_documento_id
 * @property mixed pac_nro_documento
 * @property mixed pac_domicilio_calle
 * @property mixed pac_domicilio_nro
 * @property mixed pac_domicilio_localidad
 * @property mixed pac_domicilio_id_provincia
 * @property mixed pac_domicilio_telefono
 *
 * @property string pac_telefono_movil pasa por
 * @see Paciente::COLUMNA_TELEFONO_MOVIL    contiene el valor de la columna
 * @see Paciente::getPacTelefonoMovilAttribute()    es el getter del atributo
 *
 * @property mixed pac_email
 * @property mixed pac_delete_flag
 * @property mixed pac_creation_usr
 * @property mixed pac_creation_date
 * @property mixed pac_update_usr
 * @property mixed pac_update_date
 * @property mixed pac_fecha_nacimiento
 * @property mixed pac_edad_anio
 * @property mixed pac_observaciones
 * @property mixed pac_altura
 * @property mixed pac_peso
 * @property mixed pac_claustrofobico
 * @property mixed pac_post
 * @property mixed pac_sexo
 * @property mixed pac_apellido_nombre
 * @property mixed pac_partido
 * @property mixed pac_domicilio_codigo_postal
 * @property mixed pac_telefono_laboral
 * @property mixed pac_importancia
 * @property mixed pac_turnos_realizados
 * @property mixed pac_turnos_ausentes
 * @property mixed pac_turnos_cancelados
 * @property mixed pac_edad_mes
 * @property mixed pac_forma_contacto
 * @property mixed pac_delete_date
 * @property mixed pac_delete_usr
 * @property mixed pac_discapacidad_permanente
 * @property mixed pac_historia_clinica
 * @property mixed pac_codigo_area
 * @property mixed pac_numero_valido
 * @property mixed pac_codigo_pais
 * @property mixed pac_ultimo_aviso_enviado
 * @property mixed pac_autoriza_inf_electronico
 * @property mixed pac_autoriza_invest_estudios
 * @property Localidad localidad
 * @property Provincia provincia
 * @property TipoDocumento tipoDocumento
 */
class Paciente extends RossiModel
{
    use HasFactory;
    const tableName = 'EGES_TEST.PACIENTE';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'pac_id';
    const COLUMNA_LOCALIDAD_ID = 'pac_domicilio_localidad';
    const COLUMNA_PROVINCIA_ID = 'pac_domicilio_id_provincia';
    const COLUMNA_TIPO_DOCUMENTO_ID = 'pac_tipo_documento_id';
    const COLUMNA_NRO_DOCUMENTO = 'pac_nro_documento';
    const COLUMNA_TELEFONO_MOVIL = 'pac_telefono_movil';

    const RELACION_LOCALIDAD = 'localidad';
    const RELACION_PROVINCIA = 'provincia';
    const RELACION_TIPO_DOCUMENTO = 'tipoDocumento';

    public static function getFromDni($dni): self
    {
        return Paciente::where(Paciente::COLUMNA_NRO_DOCUMENTO, $dni)
            ->whereHas(Paciente::RELACION_TIPO_DOCUMENTO,fn(Builder $q) => $q->where(TipoDocumento::COLUMNA_DESCRIPCION, TipoDocumento::DESCRIPCION_DNI))
            ->first()
        ;
    }

    public function localidad(): BelongsTo
    {
        return $this->belongsTo(Localidad::class, self::COLUMNA_LOCALIDAD_ID);
    }

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, self::COLUMNA_PROVINCIA_ID);
    }

    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, self::COLUMNA_TIPO_DOCUMENTO_ID);
    }

    /**
     * @property string Paciente::pac_telefono_movil
     */
    public function getPacTelefonoMovilAttribute(): string
    {
        if($this->attributes[self::COLUMNA_TELEFONO_MOVIL]) {
            return '549' . ($this->pac_codigo_area?:'') . $this->attributes[self::COLUMNA_TELEFONO_MOVIL];
        } else {
            return '';
        }
    }
}
