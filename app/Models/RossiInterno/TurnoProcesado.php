<?php


namespace App\Models\RossiInterno;


use App\Models\Rossi\Turno;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TurnoProcesado
 * @package App\Models\RossiInterno
 * @property string sha1
 * @property int estado_turno_id
 * @property string fecha_turno
 */
class TurnoProcesado extends RossiInternoModel
{

    use HasFactory;
    const tableName = 'turnos';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'id';
    const COLUMNA_TURNO_ID = 'turno_id';

    public static function getTurnoByIdTurno($id) : ?self
    {
        return self::where(self::COLUMNA_TURNO_ID,$id)->first();
    }


    /**
     * Determina si el turno ya existe en la base (solo si existe, no si es necesario actualizar)
     * @param Turno $turno
     * @return bool
     */
    public static function existente(Turno $turno) : bool
    {
        return !!self::getTurnoByIdTurno($turno->tur_id);
    }

    /**
     * Determina si hace falta volver a enviar el turno a SCMA para actualizar/agregar
     * @param Turno $turno
     * @return bool
     */
    public static function reemplazarble(Turno $turno) : bool
    {
        $turnoProcesado = self::getTurnoByIdTurno($turno->tur_id);
        return !$turnoProcesado || ($turnoProcesado->estado_turno_id != $turno->estado->est_id) || ($turnoProcesado->fecha_turno != $turno->tur_fecha) || ($turnoProcesado->sha1 != $turno->sha1);
    }

    public static function guardar(Turno $turno): TurnoProcesado
    {
        if (!($turnoProcesado = self::getTurnoByIdTurno($turno->tur_id))) {
            $turnoProcesado = new self();
            $turnoProcesado->turno()->associate($turno);
        }
        $turnoProcesado->estado_turno_id = $turno->tur_estado;
        $turnoProcesado->fecha_turno = $turno->tur_fecha;
        $turnoProcesado->sha1 = $turno->sha1;
        $turnoProcesado->save();
        RegistroCambio::makeFromTurnoProcesado($turnoProcesado, $turno->postMas);
        return $turnoProcesado;
    }

    public function turno() : BelongsTo
    {
        return $this->belongsTo(Turno::class, self::COLUMNA_TURNO_ID);
    }

    public function isReemplazable(Turno $turno) : bool
    {
        return ($this->estado_turno_id != $turno->estado->est_id) || ($this->fecha_turno != $turno->tur_fecha) || ($this->sha1 != $turno->sha1);
    }


}
