<?php

namespace App\Http\Resources\Permohonan\Validasi;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class ValidasiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id_kerjakan_permohonan_lapang_validasi,
            'permohonan_id' => $this->permohonan_id,
            'user_id' => $this->user_id,
            'no_hak' => $this->no_hak,
            'file_sertifikat' => URL::to('/') . $this->file_sertifikat,
            'foto_lokasi' => URL::to('/') . '/uploads/validasi/' . $this->foto_lokasi,
            'file_dwg' => URL::to('/') . '/uploads/validasi/' . $this->file_dwg,
            'sket_gambar' => URL::to('/') . '/uploads/validasi/' . $this->sket_gambar,
            'txt_csv' => URL::to('/') . '/uploads/validasi/' . $this->txt_csv,
            'catatan' => $this->catatan,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,

        ];
    }
}
