<?php

namespace App\Http\Resources\Permohonan\PetugasPermohonan;

use Illuminate\Http\Resources\Json\JsonResource;

class CekPetugasPermohonanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Relasi terkait
        $verifikator = $this->kerjakan_permohonan_verifikator ?? null;
        $pemetaan = $this->kerjakan_permohonan_pemetaan ?? null;
        $lapang = $this->kerjakan_permohonan_lapang ?? null;
        $suel = $this->kerjakan_permohonan_suel ?? null;

        // Timestamps dari setiap proses
        $timestamps = [
            'verifikator' => $verifikator ? $verifikator->created_at : '',
            'pemetaan' => $pemetaan ? $pemetaan->created_at : '',
            'lapang' => $lapang ? $lapang->created_at : '',
            'suel' => $suel ? $suel->created_at : '',
        ];

        // Proses terakhir berdasarkan waktu
        $maxTimestamp = max($timestamps);
        $latestProcess = array_search($maxTimestamp, $timestamps);

        $status = '';
        $petugas = null;
        $nama = '';

        if ($verifikator) {
            if ($this->updated_at) {
                switch ($latestProcess) {
                    case 'verifikator':
                        $status = $this->jenis_pemetaan !== 'TOLAK' ? 'Menunggu Diproses' : 'Ditolak';
                        $petugas = $status === 'Ditolak' ? 'Verifikator' : 'Pemetaan';
                        $nama = $status === 'Ditolak' ? '(' . $verifikator->user->name . ')' : '';
                        break;

                    case 'pemetaan':
                        if ($this->status_pemetaan !== 'Tolak') {
                            $status = $this->status_pemetaan === 'Sudah Tertata'
                                ? 'Menunggu Diproses'
                                : 'Menunggu Diproses';
                            $petugas = $this->status_pemetaan === 'Sudah Tertata' ? 'SuEl' : 'Lapangan';
                        } else {
                            $status = 'Ditolak';
                            $petugas = 'Pemetaan';
                            $nama = '(' . $pemetaan->user->name . ')';
                        }
                        break;

                    case 'lapang':
                        $status = strpos($this->status_pengukuran, 'tolak') === false
                            ? 'Menunggu Diproses'
                            : 'Ditolak';
                        $petugas = $status === 'Ditolak' ? 'Lapangan' : 'SuEl';
                        $nama = $status === 'Ditolak' ? '(' . $lapang->user->name . ')' : '';
                        break;

                    case 'suel':
                        $petugas = 'SuEl';
                        if ($this->status_su_el === 'Tolak dan Kembalikan Berkas') {
                            $status = 'Dikembalikan';
                            $nama = '(' . ($suel->user->name ?? 'Tidak Diketahui') . ') ke ' . ($this->petugas_lapang_id ? 'Petugas Lapangan' : 'Petugas Pemetaan');
                        } else {
                            $status = 'Telah Selesai Dikerjakan';
                            $nama = '(' . ($suel->user->name ?? 'Tidak Diketahui') . ')';
                        }
                        break;
                }
            }
        } else {
            $petugas = 'Verifikator';
            $status = $this->jenis_pemetaan !== 'TOLAK' ? 'Menunggu Diproses' : 'Ditolak';
            $nama = $status === 'Ditolak' ? '(' . $this->kerjakan_permohonan_verifikator->user->name . ')' : '';
        }

        // Data yang diformat untuk respons
        return [
            'no_permohonan' => $this->no_permohonan,
            'status' => $status,
            'petugas' => $petugas,
            'nama' => $nama,
        ];
    }
}
