<?php


namespace App\Models\Rossi;


use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Estado
 * @property string $tid_descripcion
 * @see TipoDocumento::getTidDescripcionAttribute()
 *
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

    const DESCRIPCION_DNI = 'DNI';

    public function getTidDescripcionAttribute(): string
    {
        return array_key_exists(self::COLUMNA_DESCRIPCION,$this->attributes) ? strtolower($this->attributes[self::COLUMNA_DESCRIPCION]): '';
    }
}
