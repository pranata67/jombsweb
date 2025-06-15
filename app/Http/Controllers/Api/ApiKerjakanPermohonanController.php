<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Permohonan\PermohonanResource;
use App\Models\Kecamatan;
use App\Models\Permohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiKerjakanPermohonanController extends Controller
{
    public function kerjakanForm(Request $request){
        try {
            $data = Permohonan::with([
                'permohonan_images',
                'desa',
                'kecamatan',
                'kerjakan_permohonan_lapang.user',
                'kerjakan_permohonan_lapang_validasi.user',
                'kerjakan_permohonan_verifikator.user',
                'kerjakan_permohonan_pemetaan.user',
                'kerjakan_permohonan_lapang.user',
                'kerjakan_permohonan_suel.user',
                'kerjakan_permohonan_bt.user',
                'kerjakan_permohonan_btel.user'
            ])->where('id_permohonan',$request->id)->first();

            return ResponseFormatter::success(new PermohonanResource($data), 'success');
        } catch(\Exception $e){
            return ResponseFormatter::error([],$e->getMessage(),500);
        }
    }
}
