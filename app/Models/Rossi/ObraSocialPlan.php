<?php


namespace App\Models\Rossi;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Estado
 * @property mixed $osp_nombre
 * @property ObraSocial $obraSocial
 * @package App\Models\Rossi
 */
class ObraSocialPlan extends RossiModel
{
    use HasFactory;
    const tableName = 'EGES_TEST.OBRA_SOCIAL_PLAN';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'osp_id';
    const COLUMNA_OBRA_SOCIAL_ID = 'osp_obra_social_id';
    const COLUMNA_NOMBRE = 'osp_nombre';

    const RELACION_OBRA_SOCIAL = 'obraSocial';

    public function obraSocial(): BelongsTo
    {
        return $this->belongsTo(ObraSocial::class, self::COLUMNA_OBRA_SOCIAL_ID);
    }
}
