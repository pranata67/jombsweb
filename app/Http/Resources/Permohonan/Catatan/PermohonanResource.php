<?php

namespace App\Http\Resources\Permohonan\Catatan;

use Illuminate\Http\Resources\Json\JsonResource;

class PermohonanResource extends JsonResource
{
    public function toArray($request)
    {
        $catatan = collect([
            $this->kerjakan_permohonan_lapang,
            $this->kerjakan_permohonan_lapang_validasi,
            $this->kerjakan_permohonan_pemetaan,
            $this->kerjakan_permohonan_suel,
            $this->kerjakan_permohonan_btel,
            $this->kerjakan_permohonan_verifikator,
            $this->kerjakan_permohonan_bt,
        ])->filter()->values();

        return [
            'id_permohonan' => $this->id_permohonan,
            'catatan' => CatatanResource::collection($catatan),
        ];
    }
}
