<?php

use App\Http\Controllers\DoseController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::resource('/rules', RuleController::class)->except(['show']);

Route::get('/doses', [DoseController::class, 'index'])->name('doses.index');
Route::get('/doses/update', [DoseController::class, 'update'])->name('doses.update');
Route::delete('/doses/{dose}', [DoseController::class, 'destroy'])->name('doses.destroy');
