<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerjakanPermohonanBt extends Model
{
    use HasFactory;

    protected $table = 'kerjakan_permohonan_bt';
    protected $primaryKey = 'id_kerjakan_permohonan_bt';

    public function permohonan(){
        return $this->belongsTo(Permohonan::class,'permohonan_id','id_permohonan');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public static function store($request){
        $data = new KerjakanPermohonanBt;
        $data->permohonan_id = $request->id_permohonan;
        $data->user_id = $request->petugas_bt;
        $data->upload_bt = $request->upload_bt;
        $data->catatan_bt = $request->catatan_bt;
        $data->save();
        return $data?:false;
    }
}
