<?php


namespace App\Models\Rossi;


use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Localidad
 * @package App\Models\Rossi
 * @property mixed loc_id
 * @property mixed loc_nombre
 * @property mixed loc_provincia_id
 * @property mixed loc_partido_ioma
 */
class Localidad extends RossiModel
{

    use HasFactory;
    const tableName = 'EGES_TEST.LOCALIDAD';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'loc_id';
}
