<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerjakanPermohonanLapang extends Model
{
    use HasFactory;

    protected $table = 'kerjakan_permohonan_lapang';
    protected $primaryKey = 'id_kerjakan_permohonan_lapang';

    protected $fillable = [
        'permohonan_id',
        'user_id',
        'proses_pengukuran_lapang',
        'catatan_lapangan',
    ];

    public function permohonan(){
        return $this->belongsTo(Permohonan::class,'permohonan_id','id_permohonan');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public static function store($request){
        // return $request->all();
        $data = new KerjakanPermohonanLapang;
        // $data = (!empty($request->id_permohonan)) ? Permohonan::where('id_permohonan', $request->id_permohonan)->first() : new KerjakanPermohonanLapang;
        $data->permohonan_id = $request->id_permohonan;
        $data->user_id = $request->petugas_lapang;
        $data->proses_pengukuran_lapang = $request->proses_pengukuran_lapang;
        $data->catatan_lapangan = $request->catatan_lapangan;
        $data->save();
        return $data?:false;
    }
}
