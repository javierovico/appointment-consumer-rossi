<?php

namespace App\Models\Rossi;

use App\Models\RossiInterno\TurnoProcesado;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Http;


/**
 * Class Turno
 * @package App\Models\Rossi
 * @property mixed tur_id
 * @property mixed tur_equipo_id
 * @property mixed tur_fecha
 * @property mixed tur_observaciones
 * @property mixed tur_delete_flag
 * @property mixed tur_creation_usr
 * @property mixed tur_creation_date
 * @property mixed tur_update_usr
 * @property mixed tur_update_date
 * @property mixed tur_duracion
 * @property mixed tur_combo_id
 * @property mixed tur_huerfano
 * @property mixed tur_tipo_turno_id
 * @property mixed tur_activo
 * @property mixed tur_estado
 * @property mixed tur_original_id
 * @property mixed tur_fecha_entrega_informe
 * @property mixed tur_insumos_detallados
 * @property mixed tur_cantidad_estudios
 * @property mixed tur_tcf_id
 * @property mixed tur_texto_os_particular
 * @property mixed tur_tir_id
 * @property mixed tur_etm_id
 * @property mixed tur_tie_id
 * @property mixed tur_sobreturno
 * @property mixed tur_tau_id
 * @property mixed tur_tcn_id
 * @property mixed tur_k_anestesia
 * @property mixed tur_k_contraste
 * @property mixed tur_por_tolerancia
 * @property mixed tur_requiere_pediatra
 * @property mixed tur_validacion_estado_id
 * @property mixed tur_motivo_no_facturar
 * @property mixed tur_orden_id
 * @property mixed tur_cantidad_recitaciones
 * @property mixed tur_centro_control_diario
 * @property mixed tur_fecha_control_diario
 * @property mixed tur_observacion_paciente
 * @property mixed tur_ultimo_lote_validacion
 * @property mixed tur_observaciones_cancelacion
 * @property mixed tur_discapacidad_temporal
 * @property mixed tur_embarazada_o_bebe
 * @property mixed tur_tcf_particular_anterior_id
 * @property mixed tur_es_guardia
 * @property mixed tur_turno_template_id
 * @property mixed tur_empresa_facturacion_id
 * @property mixed tur_numero_visita
 * @property mixed tur_sec_creacion_id
 * @property mixed tur_motivo_reprogramacion
 * @property mixed tur_tse_id
 * @property mixed tur_medio_reserva_id
 * @property mixed tur_last_update_date
 * @property Orden orden
 * @property Estado estado
 * @property Collection<Practica> practicas
 * @property string sha1
 * @property array arrayMas
 * @property array postMas
 * @property TurnoProcesado turnoProcesado
 * @property bool isTurnoMasEnviable
 * @property bool isTurnoMasExistente
 */
class Turno extends RossiModel
{
    use HasFactory;
    const tableName = 'EGES_TEST.TURNO';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'tur_id';
    const COLUMNA_ORDEN_ID = 'tur_orden_id';
    const COLUMNA_FECHA_TURNO = 'tur_fecha';
    const COLUMNA_ESTADO_ID = 'tur_estado';

    public function orden(): BelongsTo
    {
        return $this->belongsTo(Orden::class,self::COLUMNA_ORDEN_ID . '', Orden::COLUMNA_ID . '');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class,self::COLUMNA_ESTADO_ID, Estado::COLUMNA_ID);
    }

    public function practicaTurnos(): HasMany
    {
        return $this->hasMany(PracticaTurno::class, PracticaTurno::COLUMNA_TURNO_ID, self::COLUMNA_ID);
    }

    public function practicas(): BelongsToMany
    {
        return $this->belongsToMany(Practica::class, PracticaTurno::tableName, PracticaTurno::COLUMNA_TURNO_ID,PracticaTurno::COLUMNA_PRACTICA_ID);//,self::COLUMNA_ID,Practica::COLUMNA_ID);
    }

    /**
     * retorna la carga util secundaria de un turno (para el mas)
     * aca no hace falta tener en cuenta la clave primaria ni los parametros especificos (fecha_turno,estado_turno)
     */
    public function getArrayMasAttribute(): array
    {
        return [
            'practicas' => $this->practicas->map(fn(Practica $p) => collect($p->toArray())->only([Practica::COLUMNA_ID, Practica::COLUMNA_DESCRIPCION])->all()),
        ];
    }

    /**
     * retorna el arra post que se le envia al scma
     * @return array
     */
    public function getPostMasAttribute() : array
    {
        return [
            "codeCliente" =>  $this->tur_id,
            "fechaTurno" => $this->tur_fecha,
            "estadoTurno" => $this->estado->est_nombre,
            "nombreMas" => $this->orden->paciente->pac_nombre ?: $this->orden->paciente->pac_apellido,
            "apellidoMas" => $this->orden->paciente->pac_nombre? $this->orden->paciente->pac_apellido :'',
            "emailMas" => $this->orden->paciente->pac_email,
            "titleMas" => "SCMA",
            "phoneMas" => ($this->orden->paciente->pac_telefono_movil ?: $this->orden->paciente->pac_telefono_laboral) ?: $this->orden->paciente->pac_domicilio_telefono,
            "mobileMas" => $this->orden->paciente->pac_telefono_laboral ?: $this->orden->paciente->pac_domicilio_telefono,
            "addressMas" => $this->orden->paciente->pac_domicilio_calle . ' ' . $this->orden->paciente->pac_domicilio_nro,
            "stateMas" => $this->orden->paciente->provincia->pro_nombre,
            "cityMas" => $this->orden->paciente->localidad->loc_nombre,
            "commentsMas" => "Insertado Nuevo",
            "customDataMas" => $this->arrayMas,
        ];
    }

    public function getSha1Attribute(): string
    {
        $array = $this->arrayMas;
        ksort($array);
        return sha1(json_encode($array));
    }

    /**
     * Se encarga de hacer la petiion de envio hacia mas
     */
    public function enviarAMas() : bool
    {
        try {
            return Http::withToken(env('SCMA_TOKEN'))->post(env('SCMA_BASE_URL') . env('SCMA_URL_ROSI_PUSH'), $this->postMas)->successful();
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function turnoProcesado(): HasOne
    {
        return $this->hasOne(TurnoProcesado::class, TurnoProcesado::COLUMNA_TURNO_ID);
    }

    /**
     * Determinamos si este turno debe ser enviado para a mas para su insercion/actualizacion
     * Si no existia o si cambio algo con relacion a los datos que ya estaban
     * @return bool
     */
    public function getIsTurnoMasEnviableAttribute() : bool
    {
        return !$this->isTurnoMasExistente || ($this->turnoProcesado->isReemplazable($this));
    }

    /**
     * Determinamos si este turno ya fue alguna vez enviado a MAS
     * @return bool
     */
    public function getIsTurnoMasExistenteAttribute() : bool
    {
        return !!$this->turnoProcesado;
    }

    public function guardarProcesado()
    {
        TurnoProcesado::guardar($this);
    }
}
