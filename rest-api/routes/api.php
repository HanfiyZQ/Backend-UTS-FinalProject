<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientsController;
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

//route untuk authentication untuk akses endpoint
Route::middleware(['auth:sanctum'])->group(function () {
    //route untuk melihat seluruh data patients
    Route::get('/patients', [PatientsController::class, 'index']);

    //route untuk add patient data baru
    Route::post('/patients', [PatientsController::class, 'store']);

    //route untuk mendapatkan Detail data patient
    Route::get("/patients/{id}", [PatientsController::class, 'show']);

    //route untuk meng-Update data patient
    Route::put('/patients/{id}', [PatientsController::class, 'update']);

    //route untuk menghapus data Patient
    Route::delete('/patients/{id}', [PatientsController::class, 'destroy']);

    //route untuk mencari data patient berdasarkan nama patient
    Route::get("/patients/search/{name}", [PatientsController::class, 'search']);

    //route untuk mencari data patient berdasarkan status positive (positif)
    Route::get("/patients/status/positive", [PatientsController::class, 'positive']);

    //route untuk mencari data patient berdasarkan status recovered (sembuh)
    Route::get("/patients/status/recovered", [PatientsController::class, 'recovered']);

    //route untuk mencari data patient berdasarkan status dead (sembuh)
    Route::get("/patients/status/dead", [PatientsController::class, 'dead']);
});

//route untuk register dan login
Route::post("/register", [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login']);
