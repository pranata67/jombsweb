<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Services\Permohonan\PermohonanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiPermohonanDikembalikanController extends Controller
{
    protected $permohonanService;
    public function __construct(PermohonanService $permohonanService)
    {
        $this->permohonanService = $permohonanService;
    }

    public function getPermohonanDikembalikan(Request $request)
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

            $data = $this->permohonanService->getPermohonanDikembalikan($request->id_user);
            return ResponseFormatter::success($data, 'Data berhasil diambil');
        } catch (\Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }

}
