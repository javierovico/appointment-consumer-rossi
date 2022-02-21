<?php


namespace App\Models\RossiInterno;


use App\Models\Rossi\Turno;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Http;

/**
 * Class TurnoProcesado
 * @package App\Models\RossiInterno
 * @property int id
 * @property int cantidad_fallos
 * @property int cantidad_agregada
 * @property int cantidad_modificada
 */
class CronHistorial extends RossiInternoModel
{

    use HasFactory;
    const tableName = 'cron_historical';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'id';

    public static function registrarEvento(int $fallos, int $agregados, int $modificados)
    {
        try {
            $nuevo = new self();
            $nuevo->cantidad_fallos = $fallos;
            $nuevo->cantidad_agregada = $agregados;
            $nuevo->cantidad_modificada = $modificados;
            $nuevo->save();
            if ($fallos !== 0) {
                Http::withToken(env('SCMA_TOKEN'))->post(env('SCMA_BASE_URL') . env('SCMA_URL_SEND_SMS'), [
                    "message" => [
                        "text" => "Fallo en Rossi " . $fallos
                    ],
                    "sender" => "0981000001",
                    "addresses" => ["595994467972"]
                ]);
            }
        } catch (\Throwable $e){

        }
    }
}
