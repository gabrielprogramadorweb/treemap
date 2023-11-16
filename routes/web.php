<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\DadoController;
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

// Use either the closure or controller method for the home route, not both.
Route::get('/', function () {
    return view('treemap');
});

Route::get('/', [ChartController::class, 'index'])->name('chart-index');
Route::get('/editar-dados', [DadoController::class, 'index']);
Route::post('/editar-dados', [DadoController::class, 'update']);
Route::post('/adicionar-dados', [DadoController::class, 'create']);
Route::delete('/excluir-dados', [DadoController::class, 'excluirDados']);




