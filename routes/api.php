<?php

use App\Http\Controllers\Webhook\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiLoginController;
use App\Http\Controllers\Api\ApiPermohonanController;
use App\Http\Controllers\Api\ApiKerjakanPermohonanController;
use App\Http\Controllers\Api\ApiPermohonanDikerjakanController;
use App\Http\Controllers\Api\ApiPermohonanDikembalikanController;

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

Route::post('/wa-bot', [WebhookController::class, 'chatBot'])->name('wa-bot-test');

Route::any('/login',[ApiLoginController::class, 'doLogin']);

Route::any('/account',[ApiLoginController::class, 'updatePassword']);

Route::any('/registrasi',[ApiPermohonanController::class, 'index']);

Route::any('/form-petugas-lapang',[ApiPermohonanController::class, 'formSuratLapangan']);
Route::any('/surat-petugas-lapang',[ApiPermohonanController::class, 'suratlapangan']);

Route::any('/detail-catatan',[ApiPermohonanController::class, 'detailCatatan']);

Route::any('/form-registrasi',[ApiPermohonanController::class, 'form']);

Route::any('/form-kerjakan',[ApiKerjakanPermohonanController::class, 'kerjakanForm']);

Route::any('/registrasi-dikerjakan',[ApiPermohonanDikerjakanController::class, 'index']);

Route::any('/registrasi-dikembalikan',[ApiPermohonanDikembalikanController::class, 'getPermohonanDikembalikan']);

Route::any('/validasi',[ApiPermohonanDikerjakanController::class, 'getPermohonanValidasi']);

Route::any('/petugas-lapang-validasi',[ApiPermohonanDikerjakanController::class, 'savePermohonanValidasi']);

// Cek petugas Permohonan
Route::any('/cek-petugas-permohonan',[ApiPermohonanController::class, 'cekPetugasPermohonan']);

//kecamatan
Route::any('/kecamatan',[\App\Http\Controllers\Api\ApiKecamatanController::class, 'getKecamatan']);

//desa
Route::any('/desa',[\App\Http\Controllers\Api\ApiDesaController::class, 'getDesa']);

