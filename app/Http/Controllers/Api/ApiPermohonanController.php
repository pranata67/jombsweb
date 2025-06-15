<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormPermohonanRequest;
use App\Http\Requests\FormPermohonanValidasi;
use App\Http\Resources\Permohonan\Catatan\PermohonanResource;
use App\Http\Resources\Permohonan\PetugasPermohonan\CekPetugasPermohonanResource;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Keperluan;
use App\Models\Permohonan;
use App\Models\PermohonanImages;
use App\Models\User;
use App\Services\Permohonan\PermohonanService;
use App\Services\PetugasLapang\PetugasLapangService;
use App\Services\PetugasValidasi\PetugasValidasiService;
use Barryvdh\DomPDF\Facade\Pdf;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Log;
use Storage;

class ApiPermohonanController extends Controller
{
    private $data;
    private $menuActive = "registrasi";
    private $submnActive = "";

    protected $petugasLapangService;
    protected $petugasValidasiService;
    protected $permohonanService;


    public function __construct(
        PetugasLapangService $petugasLapangService,
        PetugasValidasiService $petugasValidasiService,
        PermohonanService $permohonanService
    )
    {
        $this->petugasLapangService = $petugasLapangService;
        $this->petugasValidasiService = $petugasValidasiService;
        $this->permohonanService = $permohonanService;
    }

    public function index(Request $request) {
        // dd($request->all());
        if($request-> level_user=='2'){
            try{
                $level_user = $request->level_user;
                $id_user = $request->id_user;
                $data = Permohonan::select('id_permohonan','no_permohonan','nama_pemohon')
                ->with('kerjakan_permohonan_lapang.user',
                    'kerjakan_permohonan_lapang_validasi.user'
                )
                ->when($level_user == 2, function ($q) use ($id_user) { #lapangan
                    // $q->where('petugas_pemetaan_id',Auth::getUser()->level_user);
                    $q->where('status_pemetaan','Ke Lapangan')
                    ->whereNotNull('petugas_pemetaan')
                    ->whereNotNull('petugas_pemetaan_id')
                    ->whereNull('status_pengukuran')
                    ->where('petugas_lapang_id', $id_user);
                })
                ->orderBy('tgl_input','desc')
                ->orderBy('id_permohonan','desc')
                ->orderBy('no_permohonan','desc')
                ->get();
                // $data['file_dir'] = '/uploads/registrasi/';
                return response()->json(['code'=>200,'message'=>'Berhasil','data'=>$data],200);

            }catch(\Exception $e){
                return response()->json(['code'=>500,'message'=>$e->getMessage(),'file'=>$e->getFile(),'line'=>$e->getLine()],500);
            }
        }else{
            return response()->json(['code'=>400,'message'=>'Anda Tidak Diizinkan Mengakses Halaman ini'],400);
        }
    }

    public function formSuratLapangan(FormPermohonanRequest $request)
    {
        try {
            $validation = $request->validated();
            $kerjakanPermohonanLapang = null;

            DB::transaction(function () use ($validation, &$kerjakanPermohonanLapang) {
                $kerjakanPermohonanLapang = $this->petugasLapangService->create($validation);
                $this->permohonanService->updatePetugasLapang($validation, $validation['id_permohonan']);
            });
            return ResponseFormatter::success($kerjakanPermohonanLapang, 'Data berhasil disimpan');
        } catch (\Exception $e) {
            return ResponseFormatter::error([], $e->getMessage(), 500);
        }
    }

    public function suratlapangan(Request $request)
    {
        $request->validate([
            'id_permohonan' => 'required|exists:permohonan,id_permohonan',
        ]);
        $data['user'] = User::find($request->id_user, ['name','nip', 'pangkat_golongan', 'jabatan']);
        $data['data'] = Permohonan::with('kecamatan','desa')->find($request->id_permohonan);

        $pdf = Pdf::loadview('cetakan.api.surat-lapangan',$data);

        return $pdf->download('surat-lapangan.pdf');
    }



    public function detailCatatan(Request $request){
        try {
            $permohonan = Permohonan::with([
                'kerjakan_permohonan_lapang.user',
                'kerjakan_permohonan_lapang_validasi.user',
                'kerjakan_permohonan_pemetaan.user',
                'kerjakan_permohonan_suel.user',
                'kerjakan_permohonan_btel.user',
                'kerjakan_permohonan_verifikator.user',
                'kerjakan_permohonan_bt.user',
            ])->findOrFail($request->id);

            return ResponseFormatter::success(new PermohonanResource($permohonan), 'Data berhasil diambil');

        } catch(\Exception $e){
            return ResponseFormatter::error([], $e->getMessage(), 500);
        }
    }

    public function form(Request $request) {

        try{
            if($request->id){
                $data['data'] = Permohonan::with('kecamatan','desa')->where('id_permohonan',$request->id)->first();
                $data['data_gambar'] = PermohonanImages::where('permohonan_id',$request->id)->get();
                $data['no_permohonan'] = [];
            }else{
                $data['data'] = [];
                $data['data_gambar'] = [];
                $data['no_permohonan'] = Permohonan::generateNoAntrian();
            }
            if (!empty($request->id)) {
                $data['kecamatan']          = Kecamatan::where('kabupaten_id', '3516')->get();
                $data['desa']               = Desa::where('kecamatan_id', (int)$data['data']->kecamatan_id)->get();
            } else {
                $data['kecamatan'] = Kecamatan::where('kabupaten_id', '3516')->get();
                $data['desa'] = Desa::where('kecamatan_id', $request->id)->get();
            }
            // return $data;
            $data['keperluan'] = Keperluan::all();
            $data['petugas_lapangan'] = User::where('level_user',2)->get();
            $data['petugas_pemetaan'] = User::where('level_user',3)->get();
            $data['petugas_su_el'] = User::where('level_user',4)->get();
            $data['petugas_bt_el'] = User::where('level_user',5)->get();
            if(isset($request->detail) && $request->detail){
                $data['detail'] = true;
            }else{
                $data['detail'] = false;
            }

            return response()->json(['code'=>200,'message'=>'Berhasil','data'=>$data],200);
        }catch(\Exception $e){
            return response()->json(['code'=>500,'message'=>$e->getMessage(),'file'=>$e->getFile(),'line'=>$e->getLine()],500);
        }
    }

    public function cekPetugasPermohonan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'no_permohonan' => 'required|exists:permohonan,no_permohonan',
            ],
            [
                'no_permohonan.required' => 'No Permohonan tidak boleh kosong',
                'no_permohonan.exists' => 'No Permohonan tidak ditemukan',
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([], $validator->errors()->first(), 400);
            }

            $data = Permohonan::with([
                'kerjakan_permohonan_verifikator.user',
                'kerjakan_permohonan_pemetaan.user',
                'kerjakan_permohonan_lapang.user',
                'kerjakan_permohonan_lapang_validasi.user',
                'kerjakan_permohonan_suel.user',
                'kerjakan_permohonan_bt.user',
                'kerjakan_permohonan_btel.user'
            ])->where('no_permohonan', $request->no_permohonan)->first();

            return ResponseFormatter::success(new CekPetugasPermohonanResource($data), 'Data berhasil diambil');
        }catch (\Exception $e) {
            return ResponseFormatter::error([], $e->getMessage(), 500);
        }
    }

}
