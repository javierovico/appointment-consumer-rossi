<?php


namespace App\Models\Rossi;


use Illuminate\Database\Eloquent\Factories\HasFactory;

class PracticaTurno extends RossiModel
{
    use HasFactory;
    const tableName = 'EGES_TEST.PRACTICA_TURNO';
    protected $table = self::tableName;
    protected $primaryKey = self::COLUMNA_ID;
    const COLUMNA_ID = 'prt_id';
    const COLUMNA_TURNO_ID = 'prt_turno_id';
    const COLUMNA_PRACTICA_ID = 'prt_practica_id';

}
