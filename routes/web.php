<?php

use App\Models\Rossi\Estado;
use App\Models\Rossi\Orden;
use App\Models\Rossi\Turno;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return json_encode([
        'nombre' => config('app.name'),
        'env' => config('app.env'),
        'debug' => config('app.debug'),
    ], JSON_PRETTY_PRINT);
});

Route::any('phpinfo',function (){
    phpinfo();
});

Route::any('prueba',function(){
//    $tes = DB::connection('conexion_db_rossi')->select('select * from EGES_TEST.ESTADO_TURNO');
//    dd($tes);
//    return Orden::query()
//        ->whereHas('turnos',function(Builder $q) {
//            $q->whereBetween(Turno::COLUMNA_FECHA_TURNO, [CarbonImmutable::now()->format('Y-m-d H:i:s'),CarbonImmutable::now()->addDays(2)->format('Y-m-d H:i:s')]);
//        })
//        ->with('turnos')
//        ->take(10)
//        ->get()
//    ;

//    return Turno::find(15516415)->orden;
//    $inicio = CarbonImmutable::now()->format('Y-m-d H:i:s');
//    $fin = CarbonImmutable::now()->addDays(2)->format('Y-m-d H:i:s');
//
//    return Turno::query()
//        ->with(['orden.paciente.localidad','orden.paciente.provincia','estado','practicas'])
//        ->where(Turno::COLUMNA_ESTADO_ID, Estado::ID_ESTADO_RESERVADO)
//        ->whereBetween(Turno::COLUMNA_FECHA_TURNO, [$inicio, $fin])
//        ->first()
////        ->take(10)
////        ->get()
//    ;

//    return Turno::first();
    \Illuminate\Support\Facades\Artisan::call('rossi:insert-mas');
});
