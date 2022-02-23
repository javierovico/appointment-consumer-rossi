<?php


namespace App\Models\Rossi;


use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Estado
 * @property string $tid_descripcion
 * @package App\Models\Rossi
 */
class TipoDocumento extends RossiModel
{
    use HasFactory;
    const tableName = 'EGES_TEST.TIPO_DOCUMENTO';
    const COLUMNA_DESCRIPCION = 'tid_descripcion';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'tid_id';
}
