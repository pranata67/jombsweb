<?php

namespace App\Http\Requests;

use App\Helpers\ResponseFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FormPermohonanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'petugas_lapang' => 'required|exists:users,id',
            'nama_petugas_lapang' => 'required',
            'id_permohonan' => 'required|exists:permohonan,id_permohonan',
            'proses_pengukuran_lapang' => 'required',
            'catatan_lapangan' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'petugas_lapang.required' => 'id user harus diisi.',
            'nama_petugas_lapang.required' => 'nama petugas lapang harus diisi.',
            'id_permohonan.required' => 'id permohonan harus diisi.',
            'proses_pengukuran_lapang.required' => 'proses pengukuran lapang harus diisi.',
            'catatan_lapangan.required' => 'catatan lapangan harus diisi.',
            'id_user.exists' => 'id user tidak ditemukan.',
            'id_permohonan.exists' => 'id permohonan tidak ditemukan.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = [];
        foreach ($validator->errors()->getMessages() as $field => $messages) {
            $errors[$field] = $messages[0];
        }
        throw new HttpResponseException(
            ResponseFormatter::error($errors,'Validation Error',422)
        );
    }
}
