<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerjakanPermohonanLapangValidasi extends Model
{
    use HasFactory;

    protected $table = 'kerjakan_permohonan_lapang_validasi';
    protected $primaryKey = 'id_kerjakan_permohonan_lapang_validasi';

    protected $fillable = [
        'permohonan_id',
        'user_id',
        'no_hak',
        'kecamatan_id',
        'desa_id',
        'foto_lokasi',
        'file_dwg',
        'sket_gambar',
        'txt_csv',
        'catatan',
    ];

    public function permohonan(){
        return $this->belongsTo(Permohonan::class,'permohonan_id','id_permohonan');
    }

    public function kecamatan() {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function desa() {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public static function store($request){
        $data = new KerjakanPermohonanLapangValidasi;
        $data->permohonan_id = $request->id_permohonan;
        $data->user_id = $request->petugas_lapang;
        $data->no_hak = $request->no_hak;
        $data->kecamatan_id = $request->kecamatan_id;
        $data->desa_id = $request->desa_id;
        $data->foto_lokasi = $request->foto_lokasi_name;
        $data->file_dwg = $request->file_dwg_name;
        $data->sket_gambar = $request->sket_gambar_name;
        $data->txt_csv = $request->txt_csv_name;
        $data->save();
        return $data?:false;
    }
}
