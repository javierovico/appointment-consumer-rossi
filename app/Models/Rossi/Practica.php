<?php


namespace App\Models\Rossi;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Practica
 * @package App\Models\Rossi
 * @property mixed pra_id
 * @property mixed pra_codigo
 * @property mixed pra_region
 * @property mixed pra_descripcion
 * @property mixed pra_duracion
 * @property mixed pra_delete_flag
 * @property mixed pra_creation_usr
 * @property mixed pra_creation_date
 * @property mixed pra_update_usr
 * @property mixed pra_update_date
 * @property mixed pra_dias_informe
 * @property mixed pra_abreviacion
 * @property mixed pra_descripcion_interna
 * @property mixed pra_reemplazo_obstetrico_id
 * @property mixed pra_leyenda
 * @property mixed pra_es_subsiguiente
 * @property mixed pra_primera_exposicion_id
 * @property mixed pra_requiere_consulta_previa
 * @property mixed pra_requiere_profesional
 * @property mixed pra_exige_diagnostico
 * @property mixed pra_leyenda_para_presupuesto
 * @property mixed pra_envio_mail_rad
 * @property mixed pra_gre_id
 * @property mixed pra_cant_default
 * @property mixed pra_req_informe
 * @property mixed pra_rei_id
 * @property mixed pra_es_detallado
 * @property mixed pra_tpr_id
 * @property mixed pra_consulta_pra_id
 * @property mixed pra_consulta_relacionada_id
 * @property mixed pra_es_consulta
 * @property mixed pra_admite_tur_programados
 * @property mixed pra_es_facturable
 * @property mixed pra_es_entrevista
 * @property mixed pra_publica_informe
 * @property mixed pra_servicio_especialidad_id
 * @property mixed pra_preparacion_id
 * @property mixed pra_maximo_exposiciones
 * @property mixed pra_habilitada_web
 * @property mixed pra_horas_entrevista
 * @property mixed pra_es_nomenclada
 * @property ServicioEspecialidad servicioEspecialidad
 * @property bool $isPracticaRelevante
 */
class Practica extends RossiModel
{
    use HasFactory;
    const tableName = 'EGES_TEST.PRACTICA';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'pra_id';
    const COLUMNA_DESCRIPCION = 'pra_descripcion';
    const COLUMNA_SERVICIO_ESPECIALIDAD_ID = 'pra_servicio_especialidad_id';
    const COLUMNA_CREATION_DATE = 'pra_creation_date';
    const COLUMNA_CODIGO = 'pra_codigo';
    /** Constantes de las relaciones */
    const RELACION_SERVICIO_ESPECIALIDAD = 'servicioEspecialidad';

    public function servicioEspecialidad(): BelongsTo
    {
        return $this->belongsTo(ServicioEspecialidad::class,self::COLUMNA_SERVICIO_ESPECIALIDAD_ID);
    }

    /**
     * Definimos si la practica es o no relevante (hay practicas que no se consideran, como "desechables" y "contraste")
     * @return bool
     */
    public function getIsPracticaRelevanteAttribute(): bool
    {
        return !in_array($this->pra_servicio_especialidad_id,[ServicioEspecialidad::ID_ESPECIALIDAD_CONTRASTE, ServicioEspecialidad::ID_ESPECIALIDAD_DESCARTABLE]);
    }
}
