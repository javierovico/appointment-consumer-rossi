<?php


namespace App\Models\Rossi;


use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Estado
 * @package App\Models\Rossi
 * @property mixed $ses_nombre
 * @property mixed $ses_id
 */
class ServicioEspecialidad extends RossiModel
{

    use HasFactory;
    const tableName = 'EGES_TEST.SERVICIO_ESPECIALIDAD';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'ses_id';
    const COLUMNA_NOMBRE = 'ses_nombre';
    //CONSTANTES QUE NO TIENEN CODIGO
    const ID_ESPECIALIDAD_RESONANCIA_MAGNETICA = 15;
    const ID_ESPECIALIDAD_CONTRASTE = 17;
    const ID_ESPECIALIDAD_DESCARTABLE = 18;
}
