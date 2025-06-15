<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KeperluanController;
use App\Http\Controllers\Pendaftaran\PendaftaranValidasiController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PermohonanController;
use App\Http\Controllers\PermohonanDitolakController;
use App\Http\Controllers\KerjakanPermohonanController;
use App\Http\Controllers\PetugasBTELController;
use App\Http\Controllers\PetugasLapanganController;
use App\Http\Controllers\PetugasPemetaanController;
use App\Http\Controllers\PetugasRegistrasiController;
use App\Http\Controllers\PetugasSUELController;
use App\Http\Controllers\OperatorBPNController;
use App\Http\Controllers\PermohonanDikembalikanController;
use App\Http\Controllers\PermohonanDikerjakanController;
use App\Http\Controllers\PermohonanSelesaiDikerjakanController;
use App\Http\Controllers\PetugasVerifikatorController;
use App\Http\Controllers\PetugasBTController;
use App\Http\Controllers\ValidasiBidangController;
use App\Http\Controllers\Webhook\WebhookController;
use App\Models\KodeRegistrasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use WebGarden\UrlShortener\Model\ValueObjects\Url;
use WebGarden\UrlShortener\UrlShortener;

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
    return redirect()->route('dashboard');
});

Route::get('/uri-tes', function () {

    // $link = $shortener->shorten(new Url('https://github.com/andrzejkupczyk/url-shortener'));

    // print($link->shortUrl()); // http://bit.ly/2Dkm8SJ
    // $apiUri = 'http://103.187.215.99/bpn_mojokerto/public/pendaftaran';
    // $apiKey = 'e6071e4883a6d23098dbd356511ef0ca7cf52f00';
    // $shortener = UrlShortener::bitly($apiUri, $apiKey);
    // $Uri = $shortener->shorten(new Url('https://github.com/andrzejkupczyk/url-shortener'));
    // $randPermohonan = mt_rand(1000000, 9999999);
    // $randPermohonan = rand(10000, 99999);
    // return $randPermohonan;
    // return date("H:i:s", time());
    // $data = KodeRegistrasi::select(DB::raw('DATE(created_at) as tanggal'), 'no_hp', DB::raw('COUNT(*) as total'))
    // ->groupBy('tanggal', 'no_hp')
    // ->get();
    // return $data;
    $file = App\Models\Permohonan::select('file_sertifikat')->whereNotNull('file_sertifikat')->get();
    try {
        $data = User::select('id','name')->where('level_user',2)->with(['permohonan_user_lapang:petugas_lapang_id,petugas_pengukuran,status_pengukuran'])->get();
        // $data = App\Models\Permohonan::where('file_sertifikat', 'pakta integritas ulil.pdf')->get();
        // $duplicates = App\Models\Permohonan::select('file_sertifikat')
        //     ->groupBy('file_sertifikat')
        //     ->havingRaw('COUNT(file_sertifikat) > 1')
        //     ->pluck('file_sertifikat');

        // $data = App\Models\Permohonan::whereIn('file_sertifikat', $duplicates)->get();
        // $data = App\Models\Permohonan::select('file_sertifikat')
        //     ->groupBy('file_sertifikat')
        //     ->havingRaw('COUNT(file_sertifikat) > 1')
        //     ->get();
        return count($data);
        return response()->json($data);
    } catch(\Exception $e) {
        return response()->json(['message' => $e->getMessage()]);
    }
});

Route::get('/rand-string', [WebhookController::class, 'generateSimpleRandomString']);

Route::post('getProvinsi', [DashboardController::class, 'getProvinsi'])->name('getProvinsi');
Route::post('getKabupaten', [DashboardController::class, 'getKabupaten'])->name('getKabupaten');
Route::post('getKecamatan', [DashboardController::class, 'getKecamatan'])->name('getKecamatan');
Route::post('getDesa', [DashboardController::class, 'getDesa'])->name('getDesa');
// Route::get('getDesa', function(){
//     return 'tes';
// })->name('getDesa');
Route::get('import-permohonan', [DashboardController::class, 'importPermohonanExcel'])->name('import-permohonan');



Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/do-login', [LoginController::class, 'doLogin'])->name('do-login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [LoginController::class, 'forgotPassword'])->middleware('guest')->name('forgot-password');
Route::post('/reset-password', [LoginController::class, 'sendEmail'])->middleware('guest')->name('send-email');
Route::get('/forgot-password', [LoginController::class, 'forgotPassword'])->name('forgot-password'); # Menampilkan view lupa password
// Route::get('/reset-password', [LoginController::class, 'resetPassword'])->name('reset-password'); # Menampilkan view reset password

Route::post('/send-email', [LoginController::class, 'sendEmail'])->middleware('guest')->name('password.reset');

Route::get('/reset-ulang-password/{token}', [LoginController::class, 'resetPassword'])->middleware('guest')->name('form-reset-ulang-password');
Route::post('/reset-ulang', [LoginController::class, 'resetUlangPassword'])->middleware('guest')->name('reset-ulang-password');

# INI PERMOHONAN DI WEB VIEW
Route::group(array('prefix' => 'pendaftaran'), function () {
    Route::get('/{kode_acak}', [PendaftaranValidasiController::class, 'index'])->name('main-pendaftaran');
    Route::post('/store', [PendaftaranValidasiController::class, 'store'])->name('store-pendaftaran');
    Route::post('/cek-no-sertifikat', [PendaftaranValidasiController::class, 'cekNoSertifikat'])->name('cek-no-sertifikat');
});
Route::get('/service-unavailable', [PendaftaranValidasiController::class, 'serviceUnavailable'])->name('service-unavailable');


Route::get('/link-reset-password', function () {
    return view('auth.forgot-password.link-toreset-password');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/data-lapangan', [DashboardController::class, 'dataLapangan'])->name('dataLapangan');
    Route::get('/cek-status', [DashboardController::class, 'cekStatusPermohonan'])->name('cekStatusPermohonanDashboard');
    Route::get('/cek-petugas', [DashboardController::class, 'cekPetugasPermohonan'])->name('cekPetugasPermohonanDashboard');

    Route::post('/store-permohonan', [WebhookController::class, 'store'])->name('store-permohonan');

    # INI PERMOHONAN DI WEB ADMIN
    Route::group(array('prefix' => 'registrasi'), function () {
        Route::get('/', [PermohonanController::class, 'index'])->name('main-registrasi');
        Route::get('/form-registrasi', [PermohonanController::class, 'form'])->name('form-registrasi');
        Route::get('/detail-registrasi', [PermohonanController::class, 'detail'])->name('detail-registrasi');
        Route::post('/save-registrasi', [PermohonanController::class, 'save'])->name('save-registrasi');
        Route::delete('/delete-gambar-registrasi', [PermohonanController::class, 'deleteGambar'])->name('delete-gambar-registrasi');
        Route::delete('/delete-sertifikat-registrasi', [PermohonanController::class, 'deleteSertif'])->name('delete-sertifikat-registrasi');
        Route::delete('/delete-registrasi', [PermohonanController::class, 'delete'])->name('delete-registrasi');
        Route::get('/export-registrasi', [PermohonanController::class, 'export'])->name('export-registrasi');
        Route::get('/sync', [PermohonanController::class, 'syncronUserPetugasPermohonan'])->name('syncronUserPetugasPermohonan');
        Route::get('/sync-by-id', [PermohonanController::class, 'syncronUserPetugasPermohonanByID'])->name('syncronUserPetugasPermohonanByID');

        Route::get('/surat-petugas-lapang/{id_permohonan}', [PermohonanController::class, 'suratlapangan'])->name('surat-lapangan');

        Route::get('/datail-catatan', [PermohonanController::class, 'detailCatatan'])->name('datail-catatan');

        Route::get('/show-pindahkan-berkas', [PermohonanController::class, 'showPindahBerkas'])->name('show-pindahkan-berkas');
        Route::post('/get-petugas', [PermohonanController::class, 'getPetugasByLevelUser'])->name('get-petugas');
        Route::post('/store-pindahkan-berkas', [PermohonanController::class, 'storePindahkanBerkas'])->name('store-pindahkan-berkas');

        Route::group(['prefix' => 'kerjakan'], function(){
            Route::post('/form-kerjakan', [KerjakanPermohonanController::class, 'kerjakanForm'])->name('form-kerjakan');
            Route::post('/kerjakan', [KerjakanPermohonanController::class, 'kerjakan'])->name('kerjakan');
            Route::post('/tutup-kerjakan', [KerjakanPermohonanController::class, 'tutupKerjakan'])->name('tutup-kerjakan');
            Route::delete('/tolak-kerjakan', [KerjakanPermohonanController::class, 'tolakKerjakan'])->name('tolak-kerjakan');
            Route::post('/delete-file-validasi', [KerjakanPermohonanController::class, 'deleteFileValidasi'])->name('delete-file-validasi');
        });

    });

    Route::group(['prefix' => 'validasi-bidang'], function(){
        Route::get('/validasi-bidang', [ValidasiBidangController::class, 'index'])->name('validasi-bidang');
        Route::get('/', [ValidasiBidangController::class, 'mainValidasiBidang'])->name('main-validasi-bidang');
    });

    Route::group(array('prefix' => 'registrasi-dikerjakan'), function () {
        Route::get('/', [PermohonanDikerjakanController::class, 'index'])->name('main-registrasi-dikerjakan');
        Route::post('/form-registrasi-dikerjakan', [PermohonanDikerjakanController::class, 'form'])->name('form-registrasi-dikerjakan');
        Route::post('/detail-registrasi-dikerjakan', [PermohonanDikerjakanController::class, 'detail'])->name('detail-registrasi-dikerjakan');
        Route::get('/get-petugas-terakhir', [PermohonanDikerjakanController::class, 'getUpdateTerakhir'])->name('get-petugas-terakhir');
    });

    Route::group(array('prefix' => 'registrasi-ditolak'), function () {
        Route::get('/', [PermohonanDitolakController::class, 'index'])->name('main-registrasi-ditolak');
        Route::get('/export', [PermohonanDitolakController::class, 'export'])->name('export-registrasi-ditolak');
    });

    Route::group(array('prefix' => 'registrasi-dikembalikan'), function () {
        Route::get('/', [PermohonanDikembalikanController::class, 'index'])->name('main-registrasi-dikembalikan');
    });

    Route::group(array('prefix' => 'registrasi-selesai'), function () {
        Route::get('/', [PermohonanSelesaiDikerjakanController::class, 'index'])->name('main-registrasi-selesai');
    });

    Route::group(array('prefix' => 'keperluan'), function () {
        Route::get('/', [KeperluanController::class, 'index'])->name('main-keperluan');
        Route::post('/form-keperluan', [KeperluanController::class, 'form'])->name('form-keperluan');
        Route::post('/store-keperluan', [KeperluanController::class, 'store'])->name('store-keperluan');
        Route::post('/delete-keperluan', [KeperluanController::class, 'destroy'])->name('delete-keperluan');
    });

    Route::group(array('prefix' => 'operator-bpn'), function () {
        Route::get('/', [OperatorBPNController::class, 'index'])->name('main-operator-bpn');
        Route::post('/form-operator-bpn', [OperatorBPNController::class, 'form'])->name('form-operator-bpn');
        Route::post('/store-operator-bpn', [OperatorBPNController::class, 'store'])->name('store-operator-bpn');
        Route::post('/delete-operator-bpn', [OperatorBPNController::class, 'destroy'])->name('delete-operator-bpn');
    });

    Route::group(array('prefix' => 'petugas-verifikator'), function () {
        Route::get('/', [PetugasVerifikatorController::class, 'index'])->name('main-petugas-verifikator');
        Route::post('/form-petugas-verifikator', [PetugasVerifikatorController::class, 'form'])->name('form-petugas-verifikator');
        Route::post('/store-petugas-verifikator', [PetugasVerifikatorController::class, 'store'])->name('store-petugas-verifikator');
        Route::post('/delete-petugas-verifikator', [PetugasVerifikatorController::class, 'destroy'])->name('delete-petugas-verifikator');
    });

    Route::group(array('prefix' => 'petugas-lapangan'), function () {
        Route::get('/', [PetugasLapanganController::class, 'index'])->name('main-petugas-lapangan');
        Route::post('/form-petugas-lapangan', [PetugasLapanganController::class, 'form'])->name('form-petugas-lapangan');
        Route::post('/store-petugas-lapangan', [PetugasLapanganController::class, 'store'])->name('store-petugas-lapangan');
        Route::post('/delete-petugas-lapangan', [PetugasLapanganController::class, 'destroy'])->name('delete-petugas-lapangan');
    });

    Route::group(array('prefix' => 'petugas-pemetaan'), function () {
        Route::get('/', [PetugasPemetaanController::class, 'index'])->name('main-petugas-pemetaan');
        Route::post('/form-petugas-pemetaan', [PetugasPemetaanController::class, 'form'])->name('form-petugas-pemetaan');
        Route::post('/store-petugas-pemetaan', [PetugasPemetaanController::class, 'store'])->name('store-petugas-pemetaan');
        Route::post('/delete-petugas-pemetaan', [PetugasPemetaanController::class, 'destroy'])->name('delete-petugas-pemetaan');
    });

    Route::group(array('prefix' => 'petugas-su-el'), function () {
        Route::get('/', [PetugasSUELController::class, 'index'])->name('main-petugas-su-el');
        Route::post('/form-petugas-su-el', [PetugasSUELController::class, 'form'])->name('form-petugas-su-el');
        Route::post('/store-petugas-su-el', [PetugasSUELController::class, 'store'])->name('store-petugas-su-el');
        Route::post('/delete-petugas-su-el', [PetugasSUELController::class, 'destroy'])->name('delete-petugas-su-el');
    });

    Route::group(array('prefix' => 'petugas-bt-el'), function () {
        Route::get('/', [PetugasBTELController::class, 'index'])->name('main-petugas-bt-el');
        Route::post('/form-petugas-bt-el', [PetugasBTELController::class, 'form'])->name('form-petugas-bt-el');
        Route::post('/store-petugas-bt-el', [PetugasBTELController::class, 'store'])->name('store-petugas-bt-el');
        Route::post('/delete-petugas-bt-el', [PetugasBTELController::class, 'destroy'])->name('delete-petugas-bt-el');
    });

    Route::group(array('prefix' => 'petugas-bt'), function () {
        Route::get('/', [PetugasBTController::class, 'index'])->name('main-petugas-bt');
        Route::post('/form-petugas-bt', [PetugasBTController::class, 'form'])->name('form-petugas-bt');
        Route::post('/store-petugas-bt', [PetugasBTController::class, 'store'])->name('store-petugas-bt');
        Route::post('/delete-petugas-bt', [PetugasBTController::class, 'destroy'])->name('delete-petugas-bt');
    });

    Route::group(array('prefix' => 'petugas-registrasi'), function () {
        Route::get('/', [PetugasRegistrasiController::class, 'index'])->name('main-petugas-registrasi');
        Route::post('/form-petugas-registrasi', [PetugasRegistrasiController::class, 'form'])->name('form-petugas-registrasi');
        Route::post('/store-petugas-registrasi', [PetugasRegistrasiController::class, 'store'])->name('store-petugas-registrasi');
        Route::post('/delete-petugas-registrasi', [PetugasRegistrasiController::class, 'destroy'])->name('delete-petugas-registrasi');
    });

    Route::group(array('prefix' => 'pengaturan'), function () {
        Route::get('/', [PengaturanController::class, 'index'])->name('main-pengaturan');
        Route::post('/update-password', [PengaturanController::class, 'updatePassword'])->name('update-password');
        Route::post('/reset-password', [PengaturanController::class, 'resetPw'])->name('reset-password');
    });
});
