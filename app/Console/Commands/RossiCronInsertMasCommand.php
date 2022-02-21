<?php

namespace App\Console\Commands;

use App\Models\Rossi\Estado;
use App\Models\Rossi\Turno;
use App\Models\RossiInterno\CronHistorial;
use App\Models\RossiInterno\TurnoProcesado;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class RossiCronInsertMasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rossi:insert-mas {--inicio=now} {--cantidadDias=2}';

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
        $inicio = CarbonImmutable::make($inicioOpt)->timezone('America/asuncion');
        $fin = $inicio->addDays($cantidadDiasOpt);
        $turnosErrores = 0;
        $turnosActualizados = 0;
        $turnosCreados = 0;
        $this->info('Desde: ' . $inicio->format('Y-m-d H:i:s'));
        $this->info('Hasta: ' . $fin->format('Y-m-d H:i:s'));
        Turno::query()
            ->with(['orden.paciente.localidad','orden.paciente.provincia','estado','practicas'])
//            ->where(Turno::COLUMNA_ESTADO_ID, Estado::ID_ESTADO_RESERVADO)
            ->whereBetween(Turno::COLUMNA_FECHA_TURNO, [$inicio->format('Y-m-d H:i:s'), $fin->format('Y-m-d H:i:s')])
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
        CronHistorial::registrarEvento($turnosErrores, $turnosCreados, $turnosActualizados);
        return -$turnosErrores;
    }
}
