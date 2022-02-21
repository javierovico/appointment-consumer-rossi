<?php


namespace App\Models\RossiInterno;


use App\Models\Rossi\Turno;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TurnoProcesado
 * @package App\Models\RossiInterno
 * @property string error
 * @property int turno_id
 */
class TurnoProcesadoError extends RossiInternoModel
{

    use HasFactory;
    const tableName = 'turnos_procesados_errores';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'id';
    const COLUMNA_ERROR = 'error';

    protected $casts = [
        self::COLUMNA_ERROR => 'array',
    ];

    public static function makeError($turnoId, \Throwable $e): self
    {
        $nuevo = new self();
        $nuevo->turno_id = $turnoId;
        $nuevo->error = [
            'message' => $e->getMessage(),
            'trace' => $e->getTrace(),
        ];
        $nuevo->save();
        return $nuevo;
    }
}
