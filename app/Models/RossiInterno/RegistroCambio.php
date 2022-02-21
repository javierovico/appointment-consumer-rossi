<?php


namespace App\Models\RossiInterno;


use App\Models\Rossi\Turno;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TurnoProcesado
 * @package App\Models\RossiInterno
 * @property array json_content
 * @property int estado_turno_id
 * @property string fecha_turno
 */
class RegistroCambio extends RossiInternoModel
{

    use HasFactory;
    const tableName = 'registro_cambios';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'id';
    const COLUMNA_TURNO_ID = 'turno_procesado_id';
    const COLUMNA_JSON_CONTENT = 'json_content';

    protected $casts = [
        self::COLUMNA_JSON_CONTENT => 'array',
    ];

    public static function makeFromTurnoProcesado(TurnoProcesado $turnoProcesado, $arrayExtra): RegistroCambio
    {
        $nuevo = new self();
        $nuevo->turnoProcesado()->associate($turnoProcesado);
        $nuevo->estado_turno_id = $turnoProcesado->estado_turno_id;
        $nuevo->fecha_turno = $turnoProcesado->fecha_turno;
        $nuevo->json_content = $arrayExtra;
        $nuevo->save();
        return $nuevo;
    }

    public function turnoProcesado() : BelongsTo
    {
        return $this->belongsTo(TurnoProcesado::class, self::COLUMNA_TURNO_ID);
    }


}
