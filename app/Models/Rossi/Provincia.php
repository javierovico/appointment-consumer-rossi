<?php


namespace App\Models\Rossi;


use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Provincia
 * @package App\Models\Rossi
 * @property mixed pro_id
 * @property string pro_nombre
 * @property mixed pro_pais_id
 * @property  mixed pro_creation_usr
 * @property  mixed pro_creation_date
 * @property  mixed pro_update_usr
 * @property  mixed pro_update_date
 * @property  mixed pro_erp_codigo
 * @property  mixed pro_hl7_codigo
 *
 */
class Provincia extends RossiModel
{

    use HasFactory;
    const tableName = 'EGES_TEST.PROVINCIA';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'pro_id';
}
