<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerjakanPermohonanVerifikator extends Model
{
    use HasFactory;

    protected $table = 'kerjakan_permohonan_verifikator';
    protected $primaryKey = 'id_kerjakan_permohonan_verifikator';

    public function permohonan(){
        return $this->belongsTo(Permohonan::class,'permohonan_id','id_permohonan');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public static function store($request){
        $data = new KerjakanPermohonanVerifikator;
        $data->permohonan_id = $request->id_permohonan;
        $data->user_id = $request->petugas_verifikator;
        $data->jenis_pemetaan = $request->jenis_pemetaan;

        $data->petugas_pengukuran = null;
        $data->petugas_lapang_id = null;
        if($request->jenis_pemetaan === 'Kelapangan'){
            $data->petugas_pengukuran = $request->namaPetugasLapang;
            $data->petugas_lapang_id = $request->petugas_lapang;
        }
        $data->catatan = $request->catatan_verivikator;

        $data->save();
        return $data?:false;
    }
}
