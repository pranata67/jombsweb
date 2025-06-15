<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormPermohonanValidasi;
use App\Services\Permohonan\PermohonanService;
use App\Services\PetugasValidasi\PetugasValidasiService;
use Illuminate\Http\Request;
use App\Models\Permohonan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiPermohonanDikerjakanController extends Controller
{
    protected $petugasValidasiService;
    protected $permohonanService;


    public function __construct(
        PetugasValidasiService $petugasValidasiService,
        PermohonanService $permohonanService
    )
    {
        $this->petugasValidasiService = $petugasValidasiService;
        $this->permohonanService = $permohonanService;
    }

    public function getPermohonanValidasi(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_user' => 'required|exists:users,id',
            ], [
                'id_user.required' => 'ID User tidak boleh kosong',
                'id_user.exists' => 'ID User tidak ditemukan',
            ]);

            if ($validator->fails()) {
                $errors = [];
                foreach ($validator->errors()->getMessages() as $field => $messages) {
                    $errors[$field] = $messages[0];
                }
                return ResponseFormatter::error($errors, 'Validation Error', 422);
            }

            $data = $this->permohonanService->getPermohonanValidasi($request->id_user);
            return ResponseFormatter::success($data, 'Data berhasil diambil');
        } catch (\Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }

    public function savePermohonanValidasi(FormPermohonanValidasi $request)
    {
        $data = null;
        try {
            DB::transaction(function () use ($request, &$data) {
                $validation = $request->validated();
                if ($request->id_validasi) {
                    $data = $this->petugasValidasiService->update($validation, $request->id_validasi);
                } else {
                    $data = $this->petugasValidasiService->create($validation);
                }
                $this->permohonanService->updatePetugasValidasi($validation['permohonan_id']);
            });
            $message = $request->id_validasi ? "diupdate" : "disimpan";
            return ResponseFormatter::success($data, "Data berhasil $message");
        } catch (\Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }

    }
}
