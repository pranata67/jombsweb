<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Services\Desa\DesaService;
use Illuminate\Http\Request;

class ApiDesaController extends Controller
{
    protected $desaService;
    public function __construct(DesaService $desaService)
    {
        $this->desaService = $desaService;
    }

    public function getDesa(Request $request)
    {
        try {
            if($request->id_kecamatan){
                $data = $this->desaService->getByIdKecamatan($request->id_kecamatan);
            }else{
                $data = $this->desaService->getAll();
            }
            return ResponseFormatter::success($data, 'success');
        } catch(\Exception $e){
            return ResponseFormatter::error(null,$e->getMessage(),500);
        }
    }
}
