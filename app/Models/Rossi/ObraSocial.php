<?php


namespace App\Models\Rossi;


use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Estado
 * @package App\Models\Rossi
 * @property mixed $oso_nombre
 */
class ObraSocial extends RossiModel
{
    use HasFactory;
    const tableName = 'EGES_TEST.OBRA_SOCIAL';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'oso_id';
    const COLUMNA_NOMBRE = 'oso_nombre';
}
