<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\DadoController;



Route::get('/', function () {
    return view('treemap');
});

Route::get('/', [ChartController::class, 'index'])->name('chart-index');
Route::get('/editar-dados', [DadoController::class, 'index']);
Route::post('/editar-dados', [DadoController::class, 'update']);
Route::post('/adicionar-dados', [DadoController::class, 'create']);
Route::delete('/excluir-dados', [DadoController::class, 'excluirDados']);




