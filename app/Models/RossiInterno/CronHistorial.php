<?php


namespace App\Models\RossiInterno;


use App\Models\Rossi\Turno;
use Carbon\CarbonImmutable;
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
 * @property mixed|string $inicio_ejecucion
 * @property float|int|mixed $tiempo_ejecucion
 */
class CronHistorial extends RossiInternoModel
{

    use HasFactory;
    const tableName = 'cron_historical';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'id';
    const COLUMNA_CANTIDAD_FALLOS = 'cantidad_fallos';

    public static function registrarEvento(int $fallos, int $agregados, int $modificados, CarbonImmutable $inicioEjecucionTarea)
    {
        try {
            $nuevo = new self();
            $nuevo->cantidad_fallos = $fallos;
            $nuevo->cantidad_agregada = $agregados;
            $nuevo->cantidad_modificada = $modificados;
            $nuevo->inicio_ejecucion = $inicioEjecucionTarea->timezone('UTC')->format('Y-m-d H:i:s');
            $nuevo->tiempo_ejecucion = $inicioEjecucionTarea->diffInSeconds(CarbonImmutable::now());
            $nuevo->save();
            if ($fallos !== 0) {
                Http::accept('application/json')->withToken(env('SCMA_TOKEN'))->post(env('SCMA_BASE_URL') . env('SCMA_URL_SEND_SMS'), [
                    "message" => [
                        "text" => "Fallo en Rossi " . $fallos
                    ],
                    "sender" => env('SMS_ERROR_EMISOR'),
                    "addresses" => explode(',',env('SMS_ERROR_RECEPTOR'))
                ]);
            }
        } catch (\Throwable $e){

        }
    }

    /**
     * Trae la fecha de la ultima vez que se ejecuto sin ningun fallo
     * @return CarbonImmutable
     */
    public static function getDatetimeUltimaEjecucionSuccess() : ?CarbonImmutable
    {
        /** @var self $ultimoRegistroExtitoso */
        $ultimoRegistroExtitoso = self::query()
            ->where(self::COLUMNA_CANTIDAD_FALLOS, '=', 0)
            ->orderByDesc(self::COLUMNA_ID)
            ->first()
        ;
        return $ultimoRegistroExtitoso ? CarbonImmutable::make($ultimoRegistroExtitoso->inicio_ejecucion) : null;
    }
}
