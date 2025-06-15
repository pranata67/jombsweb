<?php

namespace App\Http\Resources\Permohonan\Catatan;

use Illuminate\Http\Resources\Json\JsonResource;

class CatatanResource extends JsonResource
{
    private function getPetugasLabel()
    {
        $levelMap = [
            '2' => 'Petugas Lapangan',
            '3' => 'Petugas Pemetaan',
            '4' => 'Petugas SUEL',
            '5' => 'Petugas BTEL',
            '11' => 'Petugas Verifikator',
            '12' => 'Petugas BT'
        ];


        return $levelMap[$this->user->level_user] ?? '-';
    }

    private function getCatatan()
    {
        $catatanMap = [
            '2' => $this->catatan_lapangan,
            '3' => $this->catatan_pemetaan,
            '4' => $this->catatan_su_el,
            '5' => $this->catatan_bt_el,
            '11' => $this->catatan,
            '12' => $this->catatan_bt,
        ];

        return $catatanMap[$this->user->level_user] ?? '-';
    }
    public function toArray($request)
    {
        return [
            'petugas' => $this->getPetugasLabel(),
            'catatan' => $this->getCatatan(),
            'created_at' => $this->created_at ? $this->created_at->isoFormat('dddd, DD MMMM YYYY, HH:mm') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->isoFormat('dddd, DD MMMM YYYY, HH:mm') : null,
            'user' => [
                'id' => $this->user ? $this->user->id : null,
                'name' => $this->user ? $this->user->name : null,
            ],
        ];
    }
}
