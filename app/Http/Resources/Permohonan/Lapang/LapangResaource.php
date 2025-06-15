<?php

namespace App\Http\Resources\Permohonan\Lapang;

use Illuminate\Http\Resources\Json\JsonResource;

class LapangResaource extends JsonResource
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
            'id' => $this->id_kerjakan_permohonan_lapang,
            'id_permohonan' => $this->permohonan_id,
            'id_user' => $this->user_id,
            'proses_pengukuran_lapang' => $this->proses_pengukuran_lapang,
            'catatan' => $this->catatan_lapangan,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
