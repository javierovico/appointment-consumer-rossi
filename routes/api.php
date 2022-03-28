<?php

use App\Http\Controllers\PacienteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('paciente')->group(function(){
    Route::get('',[PacienteController::class,'getPaciente']);
    Route::get('from-dni/32144152',[PacienteController::class,'getPacienteNico']);
    Route::get('from-dni/4047478',[PacienteController::class,'getPacienteAldo']);
    Route::get('from-dni/{dni}',[PacienteController::class,'getPacienteByDNI']);
});
