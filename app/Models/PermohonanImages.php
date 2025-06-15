<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermohonanImages extends Model
{
    use HasFactory;

    protected $table = 'permohonan_images';
    protected $primaryKey = 'id_permohonan_images';
    
    protected $appends = ['file_gambar_exists'];

    public function getFileGambarExistsAttribute(){
        $file = $this->gambar;
        $path_storage = public_path('storage/storage/file-sertifikat/');
        $path_public = public_path('uploads/registrasi/');
        
        if(file_exists($path_public.$file)){
            return $this->file_gambar_exists = 1;
        }elseif(file_exists($path_storage.$file)) {
            return $this->file_gambar_exists = 2;
        }else{
            return $this->file_gambar_exists = 0;
        }
    }
    
    public function permohonan(){
        return $this->belongsTo(Permohonan::class,'permohonan_id','id_permohonan');
    }

    public static function deleteGambar($id){
        $data = PermohonanImages::findOrFail($id);
        //hapus gambar
        $path = public_path('uploads/registrasi/');
        $file = $path.$data->gambar;
        $cekFile = file_exists($file);
        // return $file;
        if($cekFile){
            @unlink($file);
            $data->delete();
            return 1;
        }
        return 0;
    }
}
