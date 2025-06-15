<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerjakanPermohonanBtel extends Model
{
    use HasFactory;

    protected $table = 'kerjakan_permohonan_btel';
    protected $primaryKey = 'id_kerjakan_permohonan_btel';

    public function permohonan(){
        return $this->belongsTo(Permohonan::class,'permohonan_id','id_permohonan');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public static function store($request){
        $data = new KerjakanPermohonanBtel;
        $data->permohonan_id = $request->id_permohonan;
        $data->user_id = $request->petugas_bt_el;
        $data->status_btel = $request->status_bt_el;
        $data->catatan_bt_el = $request->catatan_bt_el;
        // $data->upload_bt = $request->upload_bt;
        $data->save();
        return $data?:false;
    }
}
