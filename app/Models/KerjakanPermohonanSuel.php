<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerjakanPermohonanSuel extends Model
{
    use HasFactory;

    protected $table = 'kerjakan_permohonan_suel';
    protected $primaryKey = 'id_kerjakan_permohonan_suel';

    public function permohonan(){
        return $this->belongsTo(Permohonan::class,'permohonan_id','id_permohonan');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public static function store($request){
        $data = new KerjakanPermohonanSuel;
        $data->permohonan_id = $request->id_permohonan;
        $data->user_id = $request->petugas_su_el;
        $data->status_suel = $request->status_su_el;
        $data->catatan_su_el = $request->catatan_su_el;
        $data->save();
        return $data?:false;
    }
}
