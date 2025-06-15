<?php

namespace App\Imports;

use App\Models\Permohonan;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportPermohonan implements ToCollection,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $permohonan = Permohonan::create([
                'no_permohonan' => $row[2],
                'tgl_input' => $row[3],
                'nama_pemohon' => $row[4],
                'telepon_pemohon' => $row[5],
                'nama_kuasa' => $row[6],
                'telepon_kuasa' => $row[7],
                'jenis_hak' => $row[8],
                'no_sertifikat' => $row[9],
                'provinsi_id' => $row[10],
                'kabupaten_id' => $row[11],
                'kecamatan_id' => $row[12],
                'desa_id' => $row[13],
                'file_sertifikat' => $row[14],
                'file_foto' => $row[15],
                'keperluan' => $row[16],
                'tanggal_pengajuan' => $row[17],
                'out' => $row[18],
                'jenis_pemetaan' => $row[19],
                'petugas_pemetaan' => $row[20],
                'status_pemetaan' => $row[21],
                'petugas_pengukuran' => $row[22],
                'status_pengukuran' => $row[23],
                'petugas_su_el' => $row[24],
                'status_su_el' => $row[25],
                'catatan_su_el' => $row[26],
                'upload_bt' => $row[27],
                'petugas_bt_el' => $row[28],
                'status_bt_el' => $row[29],
                'catatan_bt_el' => $row[30],
                'tanggal_setor' => $row[31],
                'status_permohonan' => $row[32],
                'catatan_status' => $row[33],
                'pemberitahuan' => $row[34],
                'latitude' => $row[35],
                'longitude' => $row[36],
                'luas_total' => $row[37],
                'jarak_total' => $row[38],
            ]);
        }
    }
    public function startRow(): int
    {
        return 2; // Mulai dari baris ke-2 untuk mengabaikan header
    }
}
