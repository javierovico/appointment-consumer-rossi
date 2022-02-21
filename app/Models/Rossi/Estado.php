<?php


namespace App\Models\Rossi;


use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Estado
 * @package App\Models\Rossi
 * @property mixed est_id
 * @property mixed est_nombre
 * @property mixed est_descripcion
 * @property mixed est_circuito
 * @property mixed est_atendido
 * @property mixed est_cancelado
 * @property mixed est_pendiente
 * @property mixed est_color
 * @property mixed est_ausente
 * @property mixed est_facturable
 * @property mixed est_informado
 * @property mixed est_informable
 * @property mixed est_visible_wl
 */
class Estado extends RossiModel
{

    use HasFactory;
    const tableName = 'EGES_TEST.ESTADO_TURNO';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'est_id';

    /** Ids de estados conocidos */
    const ID_ESTADO_RESERVADO = 4;
}
