<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Services\Kecamatan\KecamatanService;
use Illuminate\Http\Request;

class ApiKecamatanController extends Controller
{
    protected $kecamatanService;
    public function __construct(KecamatanService $kecamatanService)
    {
        $this->kecamatanService = $kecamatanService;
    }
    // get all kecamatan or get kecamatan by id

    public function getKecamatan(Request $request)
    {
        try {
            if($request->id_kabupaten){
                $data = $this->kecamatanService->getByIdKabupaten($request->id_kabupaten);
            }else{
                $data = $this->kecamatanService->getAll();
            }
            return ResponseFormatter::success($data, 'success');
        } catch(\Exception $e){
            return ResponseFormatter::error(null,$e->getMessage(),500);
        }
    }

}
