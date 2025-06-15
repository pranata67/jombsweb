<?php

namespace App\Http\Resources\Permohonan;

use App\Http\Resources\Permohonan\Lapang\LapangResaource;
use App\Http\Resources\Permohonan\Validasi\ValidasiResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class PermohonanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id_permohonan,
            'tanggal_permohonan' => $this->tanggal_pengajuan,
            'nomor_registrasi' => $this->no_permohonan,
            'nama_pemohon' => $this->nama_pemohon,
            'no_wa_pemohon' => $this->telepom_pemohon,
            'nama_kuasa' => $this->nama_kuasa,
            'no_wa_kuasa' => $this->telepon_kuasa,
            'jenis_hak' => $this->jenis_hak,
            'nomor_sertifikat' => $this->no_sertifikat,


            // desa
            'desa' => [
                'id' => $this->desa->id ?? null,
                'nama' => $this->desa->nama ?? null
            ],
            // kecamatan
            'kecamatan' => [
                'id' => $this->kecamatan->id ?? null,
                'nama' => $this->kecamatan->nama ?? null
            ],
            // kabupaten
            'kabupaten' => [
                'id' => $this->kabupaten->id ?? null,
                'nama' => $this->kabupaten->nama ?? null
            ],
            // provinsi
            'provinsi' => [
                'id' => $this->provinsi->id ?? null,
                'nama' => $this->provinsi->nama ?? null
            ],

            // gambar yang diupload
            'file_gambar' => $this->whenLoaded('permohonan_images', function() {
                return $this->permohonan_images->map(function($image) {
                    return [
                        'id' => $image->id_permohonan_images,
                        'url' => URL::to('/') . "/uploads/registrasi/$image->gambar",
                    ];
                });
            }),

            // File Sertifikat
            'file_sertifikat' => URL::to('/') . $this->file_sertifikat,

            // File pendukung
            'file_pendukung' => empty($this->file_pendukung)
                ? []
                : array_map(function ($item) {
                    return url($item);
                }, $this->file_pendukung),


            // Processing stages with assigned users
            'petugas' => [
                'lapang' => $this->whenLoaded('kerjakan_permohonan_lapang', function() {
                    return [
                        'id' => $this->kerjakan_permohonan_lapang->user->id ?? null,
                        'nama' => $this->kerjakan_permohonan_lapang->user->name ?? null
                    ];
                }),
                'verifikator' => $this->whenLoaded('kerjakan_permohonan_verifikator', function() {
                    return [
                        'id' => $this->kerjakan_permohonan_verifikator->user->id ?? null,
                        'nama' => $this->kerjakan_permohonan_verifikator->user->name ?? null
                    ];
                }),
                'pemetaan' => $this->whenLoaded('kerjakan_permohonan_pemetaan', function() {
                    return [
                        'id' => $this->kerjakan_permohonan_pemetaan->user->id ?? null,
                        'nama' => $this->kerjakan_permohonan_pemetaan->user->name ?? null
                    ];
                }),
                'suel' => $this->whenLoaded('kerjakan_permohonan_suel', function() {
                    return [
                        'id' => $this->kerjakan_permohonan_suel->user->id ?? null,
                        'nama' => $this->kerjakan_permohonan_suel->user->name ?? null
                    ];
                }),
                'bt' => $this->whenLoaded('kerjakan_permohonan_bt', function() {
                    return [
                        'id' => $this->kerjakan_permohonan_bt->user->id ?? null,
                        'nama' => $this->kerjakan_permohonan_bt->user->name ?? null
                    ];
                }),
                'btel' => $this->whenLoaded('kerjakan_permohonan_btel', function() {
                    return [
                        'id' => $this->kerjakan_permohonan_btel->user->id ?? null,
                        'nama' => $this->kerjakan_permohonan_btel->user->name ?? null
                    ];
                }),
            ],

            // lokasi logitude dan latitude
            'lokasi' => [
                'longitude' => $this->longitude,
                'latitude' => $this->latitude
            ],

            // Catatan
            'catatan' => $this->catatan_permohonan,

            // kerjakan permohonan lapang
            'kerjakan_permohonan_lapang' => new LapangResaource($this->whenLoaded('kerjakan_permohonan_lapang')),

            // kerjakan permohonan lapang validasi
            'kerjakan_permohonan_lapang_validasi' => new ValidasiResource($this->whenLoaded('kerjakan_permohonan_lapang_validasi')),
            // Timestamps
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
