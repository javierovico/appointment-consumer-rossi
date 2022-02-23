<?php

namespace App\Console\Commands;

use App\Models\Rossi\Estado;
use App\Models\Rossi\ObraSocialPlan;
use App\Models\Rossi\Orden;
use App\Models\Rossi\Paciente;
use App\Models\Rossi\Practica;
use App\Models\Rossi\ServicioEspecialidad;
use App\Models\Rossi\Turno;
use App\Models\RossiInterno\CronHistorial;
use App\Models\RossiInterno\TurnoProcesado;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class RossiCronInsertMasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rossi:insert-mas {--inicio=now} {--cantidadDias=3} {--ultimaEjecucion}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserta los nuevos turnos de rossi a mas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * retorna 0 si fue success
     * retorna un numero negativo indicando la cantidad de cargas fallidas en caso de error
     * @return int
     */
    public function handle(): int
    {
        $inicioOpt = $this->option('inicio');
        $cantidadDiasOpt = $this->option('cantidadDias');
        $inicioEjecucionTarea = CarbonImmutable::now('America/Argentina/Buenos_Aires');                 //cuando la tarea se inicio
        $inicio = new CarbonImmutable($inicioOpt,'America/Argentina/Buenos_Aires');      //desde cuando deben tomarse los turnos, default: now()
        if ($ultimaEjecucionOpt = $this->option('ultimaEjecucion')) {
            //si tenemos de forma explicita este argumento, lo usamos
            $ultimaEjecucionTarea = new CarbonImmutable($ultimaEjecucionOpt,'America/Argentina/Buenos_Aires');
        } else if (CronHistorial::getDatetimeUltimaEjecucionSuccess()) {
            //Sino tratamos de optener la fecha de la ultima ejecucion exitosa
            $ultimaEjecucionTarea = CronHistorial::getDatetimeUltimaEjecucionSuccess()->addMinutes(-5); //le agregamos 5 minutos de tolerancia
        } else {
            //por defecto asumimos que debemos traer las que se modificaron en las ultimas cinco horas
            $ultimaEjecucionTarea = CarbonImmutable::now()->addHours(-5);
        }
        $ultimaEjecucionTarea = $ultimaEjecucionTarea->setTimezone('America/Argentina/Buenos_Aires');       //estableemos a la hora local argentina, porque es la hora de nuestra base de datos
        $fin = $inicio->addDays($cantidadDiasOpt);
        $turnosErrores = 0;
        $turnosActualizados = 0;
        $turnosCreados = 0;
        $this->info('Desde: ' . $inicio->format('Y-m-d H:i:s'));
        $this->info('Hasta: ' . $fin->format('Y-m-d H:i:s'));
        $this->info('Ultima ejecucion Tomada: ' . $ultimaEjecucionTarea->format('Y-m-d H:i:s'));
        Turno::query()
            ->with([
                Turno::RELACION_ORDEN . '.' . Orden::RELACION_OBRA_SOCIAL_PLAN . '.' . ObraSocialPlan::RELACION_OBRA_SOCIAL,
                Turno::RELACION_ORDEN . '.' . Orden::RELACION_PACIENTE . '.' . Paciente::RELACION_LOCALIDAD,
                Turno::RELACION_ORDEN . '.' . Orden::RELACION_PACIENTE . '.' . Paciente::RELACION_PROVINCIA,
                Turno::RELACION_ORDEN . '.' . Orden::RELACION_PACIENTE . '.' . Paciente::RELACION_TIPO_DOCUMENTO,
                Turno::RELACION_ESTADO,
                Turno::RELACION_PRACTICAS . '.' . Practica::RELACION_SERVICIO_ESPECIALIDAD
            ])
            /** Solo le pasamos los estados reservados */
//            ->where(Turno::COLUMNA_ESTADO_ID, Estado::ID_ESTADO_RESERVADO)
            /** Solo le pasamos los que tienen al menos una practica del tipo resonancia */
            ->whereHas('practicas',fn(Builder $q)=> $q->where(Practica::COLUMNA_SERVICIO_ESPECIALIDAD_ID, ServicioEspecialidad::ID_ESPECIALIDAD_RESONANCIA_MAGNETICA))
            ->where(function(Builder $q) use ($ultimaEjecucionTarea, $fin, $inicio) {
                /** Solo le pasamos los que tengan fecha de turno dentro del rango */
                $q->whereBetween(Turno::COLUMNA_FECHA_TURNO, [$inicio->format('Y-m-d H:i:s'), $fin->format('Y-m-d H:i:s')]);
                /** Solo o las que se modificaron recientemente */
                $q->orWhere(Turno::COLUMNA_LAST_UPDATE_DATE, '>=', $ultimaEjecucionTarea->format('Y-m-d H:i:s'));
            })
            ->chunk(500,function($turnos) use (&$turnosErrores, &$turnosActualizados, &$turnosCreados) {
                /** @var Turno $turno */
                foreach ($turnos as $turno) {
                    if ($turno->isTurnoMasEnviable) {
                        if ($turno->enviarAMas()) {
                            if ($turno->isTurnoMasExistente) {
                                $turnosActualizados++;
                                $this->info("Turno Actualizado " . $turno->tur_id);
                            } else {
                                $turnosCreados++;
                                $this->info("Turno Creado " . $turno->tur_id);
                            }
                            $turno->guardarProcesado();
                        } else {
                            $this->error("Fallo en la conexion con SCMA");
                            $turnosErrores++;
                        }
                    } else {
                        $this->info("Turno ya insertado " . $turno->tur_id);
                    }
                }
            })
        ;
        if ($turnosErrores) {
            $this->error("Fallos: " . $turnosErrores);
        }
        $this->info("Creados: " . $turnosCreados);
        $this->info("Actualizados: " . $turnosActualizados);
        CronHistorial::registrarEvento($turnosErrores, $turnosCreados, $turnosActualizados, $inicioEjecucionTarea);
        return -$turnosErrores;
    }
}
