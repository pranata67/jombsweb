<?php

namespace App\Http\Requests;

use App\Helpers\ResponseFormatter;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FormPermohonanValidasi extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'permohonan_id' => 'required|exists:permohonan,id_permohonan',
            'no_hak' => 'required',
            'kecamatan_id' => 'required',
            'desa_id' => 'required',
            'foto_lokasi' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file_dwg' => 'required|mimes:pdf|max:2048',
            'sket_gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'txt_csv' => 'required|mimes:csv,txt|max:2048',
            'catatan' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'id user harus diisi.',
            'permohonan_id.required' => 'id permohonan harus diisi.',
            'no_hak.required' => 'no hak harus diisi.',
            'kecamatan_id.required' => 'kecamatan harus diisi.',
            'desa_id.required' => 'desa harus diisi.',

            'foto_lokasi.required' => 'foto lokasi harus diisi.',
            'foto_lokasi.mimes' => 'foto lokasi harus berupa gambar dengan format jpeg, png, jpg, gif, svg.',
            'foto_lokasi.max' => 'foto lokasi maksimal 2048kb.',

            'file_dwg.required' => 'file dwg harus diisi.',
            'file_dwg.mimes' => 'file dwg harus berupa pdf.',
            'file_dwg.max' => 'file dwg maksimal 2048kb.',

            'sket_gambar.required' => 'sket gambar harus diisi.',
            'sket_gambar.mimes' => 'sket gambar harus berupa gambar dengan format jpeg, png, jpg, gif, svg.',
            'sket_gambar.max' => 'sket gambar maksimal 2048kb.',

            'txt_csv.required' => 'txt csv harus diisi.',
            'txt_csv.mimes' => 'txt csv harus berupa csv atau txt.',
            'txt_csv.max' => 'txt csv maksimal 2048kb.',

            'catatan.required' => 'catatan harus diisi.',
            'user_id.exists' => 'id user tidak ditemukan.',
            'permohonan_id.exists' => 'id permohonan tidak ditemukan.',
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
