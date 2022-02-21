<?php

namespace App\Models\Rossi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Orden
 * @package App\Models\Rossi
 * @property mixed ord_id
 * @property mixed ord_cant_sesiones
 * @property mixed ord_cant_cumplidas
 * @property mixed ord_estado
 * @property mixed ord_es_multiple
 * @property mixed ord_lote_traslado_id
 * @property mixed ord_entrega_orden_id
 * @property mixed ord_posicion_en_lote
 * @property mixed ord_paciente_id
 * @property mixed ord_diagnostico
 * @property mixed ord_osp_id
 * @property mixed ord_fecha_emision_orden
 * @property mixed ord_medico_solicitante_id
 * @property mixed ord_protocolo_id
 * @property mixed ord_numero_afiliado
 * @property mixed ord_numero_autorizacion
 * @property mixed ord_debe_orden_id
 * @property mixed ord_sec_recepcion_id
 * @property mixed ord_pago_diferido_cliente_id
 * @property mixed ord_tue_id
 * @property mixed ord_info_internacion
 * @property mixed ord_creation_usr
 * @property mixed ord_creation_date
 * @property mixed ord_update_usr
 * @property mixed ord_update_date
 * @property mixed ord_osp_original_id
 * @property mixed ord_fecha_emision_autorizacion
 * @property mixed ord_version_credencial
 * @property mixed ord_codigo_seguridad
 * @property mixed ord_last_update_date
 * @property Paciente paciente
 */
class Orden extends RossiModel
{
    use HasFactory;
    const tableName = 'EGES_TEST.ORDEN';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'ord_id';
    const COLUMNA_PACIENTE_ID = 'ord_paciente_id';

    public function turnos(): HasMany
    {
        return $this->hasMany(Turno::class,Turno::COLUMNA_ORDEN_ID, self::COLUMNA_ID);
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, self::COLUMNA_PACIENTE_ID, Paciente::COLUMNA_ID);
    }
}
