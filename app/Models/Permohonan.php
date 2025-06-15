<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class Permohonan extends Model
{
    use HasFactory;

    protected $table = 'permohonan';
    protected $primaryKey = 'id_permohonan';
    // protected $fillable = [
    //     'desa_id'
    // ];
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    // Accessor untuk file_sertifikat
    public function getFileSertifikatAttribute($value)
    {
        $path_storage = public_path('storage/storage/file-sertifikat/');
        $path_public = public_path('uploads/registrasi/');
        if(file_exists($path_public.$value)){
            return $value ? '/uploads/registrasi/' . $value : null;
        }else{
            return $value ? '/storage/storage/file-sertifikat/' . $value : null;
        }
    }

    public function getFilePendukungAttribute($value)
    {
        $path_public = public_path('uploads/registrasi/');

        // Jika $value adalah string seperti "['test', 'testt']"
        $value = str_replace("'", '"', $value); // Ubah tanda kutip tunggal ke kutip ganda agar menjadi JSON valid
        $array = json_decode($value, true); // Decode ke array PHP

        // Jika ingin memproses array tersebut
        if (is_array($array)) {
            return array_map(function ($item) use ($path_public) {
                if(file_exists($path_public.$item)){
                    return $item ? '/uploads/registrasi/' . $item : null;
                }else{
                    return $item ? '/storage/storage/file-sertifikat/' . $item : null;
                }
            }, $array);
        } else {
            if(file_exists($path_public.$value)){
                return $value ? '/uploads/registrasi/' . $value : null;
            }else{
                return $value ? '/storage/storage/file-sertifikat/' . $value : null;
            }
            // return $value;
        }

        return []; // Jika $value tidak valid
    }

    // Accessor untuk file_foto
    public function getFileFotoAttribute($value)
    {
        return $value ? '/uploads/registrasi/' . $value : null;
    }

    public function kecamatan() {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function desa() {
        return $this->belongsTo(Desa::class, 'desa_id');
    }
    public function kabupaten() {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    public function provinsi() {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function permohonan_images(){
        return $this->hasMany(PermohonanImages::class,'permohonan_id','id_permohonan');
    }
    public function kerjakan_permohonan_lapang(){
        return $this->hasOne(KerjakanPermohonanLapang::class,'permohonan_id','id_permohonan')->orderBy('created_at','desc');
    }
    public function kerjakan_permohonan_lapang_validasi(){
        return $this->hasOne(KerjakanPermohonanLapangValidasi::class,'permohonan_id','id_permohonan')->orderBy('created_at','desc');
    }
    public function kerjakan_permohonan_pemetaan(){
        return $this->hasOne(KerjakanPermohonanPemetaan::class,'permohonan_id','id_permohonan')->orderBy('created_at','desc');
    }
    public function kerjakan_permohonan_suel(){
        return $this->hasOne(KerjakanPermohonanSuel::class,'permohonan_id','id_permohonan')->orderBy('created_at','desc');
    }
    public function kerjakan_permohonan_btel(){
        return $this->hasOne(KerjakanPermohonanBtel::class,'permohonan_id','id_permohonan')->orderBy('created_at','desc');
    }
    public function kerjakan_permohonan_verifikator(){
        return $this->hasOne(KerjakanPermohonanVerifikator::class,'permohonan_id','id_permohonan')->orderBy('created_at','desc');
    }
    public function kerjakan_permohonan_bt(){
        return $this->hasOne(KerjakanPermohonanBt::class,'permohonan_id','id_permohonan')->orderBy('created_at','desc');
    }

    public function petugasLapang()
    {
        return $this->belongsTo(User::class, 'petugas_lapang_id');
    }

    public function petugasPemetaan()
    {
        return $this->belongsTo(User::class, 'petugas_pemetaan_id');
    }

    public function petugasSuel()
    {
        return $this->belongsTo(User::class, 'petugas_suel_id');
    }

    public function petugasBtel()
    {
        return $this->belongsTo(User::class, 'petugas_btel_id');
    }

    public function petugasVerifikator()
    {
        return $this->belongsTo(User::class, 'petugas_verifikator_id');
    }

    public function petugasBt()
    {
        return $this->belongsTo(User::class, 'petugas_bt_id');
    }

    public static function generateNoAntrian(){
        $lastKode = Permohonan::select('no_permohonan')->orderBy('id_permohonan','desc')->orderBy('no_permohonan','desc')->first();
        if($lastKode){
            $newKode = preg_replace('/\D/', '', $lastKode->no_permohonan);
            $newKode += 1;
        }else{
            $newKode = 1;
        }
        $no_permohonan = 'A'.$newKode;
        return $no_permohonan;
    }

    public static function updateProsesPengukuranLapang($request){
        // return $request->all();
        $data = Permohonan::where('id_permohonan',$request->id_permohonan)->first();
        $data->latitude = $request->latitude;
        $data->longitude = $request->longitude;
        $data->petugas_pengukuran = Auth::getUser()->name;
        $data->petugas_lapang_id = Auth::getUser()->id;
        $data->status_pengukuran = $request->proses_pengukuran_lapang;
        if($request->status_suel == 'Tolak dan Kembalikan Berkas') {
            $data->status_su_el = null;
        }
        if($request->status_su_el == 'Tolak dan Kembalikan Berkas') {
            $data->petugas_su_el = null;
            $data->status_su_el = null;
            $data->catatan_su_el = null;
        }
        $data->save();
        return $data?:false;
    }
    public static function updateProsesPengukuranPemetaan($request){
        $data = Permohonan::where('id_permohonan',$request->id_permohonan)->first();
        $data->latitude = $request->latitude;
        $data->longitude = $request->longitude;
        $data->petugas_pemetaan = Auth::getUser()->name;
        $data->petugas_pemetaan_id = Auth::getUser()->id;
        $data->status_pemetaan = $request->status_pemetaan;
        $data->petugas_lapang_id = $request->petugas_lapang;
        if($request->status_pengukuran == 'tolak dan kembalikan berkas') {
            $data->status_pengukuran = '';
            $data->petugas_pengukuran = null;
        }
        if($request->status_su_el == 'Tolak dan Kembalikan Berkas') {
            $data->petugas_su_el = null;
            $data->status_su_el = null;
            $data->catatan_su_el = null;
        }
        $data->save();
        return $data?:false;
    }
    public static function updateProsesPengukuranSuel($request){
        $data = Permohonan::where('id_permohonan',$request->id_permohonan)->first();
        $data->latitude = $request->latitude;
        $data->longitude = $request->longitude;
        $data->petugas_su_el = Auth::getUser()->name;
        $data->petugas_suel_id = Auth::getUser()->id;
        $data->status_su_el = $request->status_su_el;
        $data->catatan_su_el = $request->catatan_su_el;

        if($request->status_su_el == "Sudah") {
            self::sendMessageSuEl($data);
        } elseif($data->status_bt_el == "Sudah" && $request->status_su_el == "Sudah") {
            self::sendMessageBtEl($data);
        }
        if ($request->status_su_el === "Sudah" && $data->status_bt_el === "Sudah") {
            $data->status_permohonan = "SELESAI";
        } else if ($request->status_su_el === "Sudah" && $data->status_bt_el !== "Sudah") {
            // $data->status_permohonan = "KURANG BT EL";
            $data->status_permohonan = "";
        } else {
            $data->status_permohonan = "PROSES";
        }

        $data->save();
        return $data?:false;
    }
    public static function updateProsesPengukuranBtel($request){
        $data = Permohonan::where('id_permohonan',$request->id_permohonan)->first();
        $data->latitude = $request->latitude;
        $data->longitude = $request->longitude;
        $data->petugas_bt_el = Auth::getUser()->name;
        $data->petugas_btel_id = Auth::getUser()->id;
        // $data->upload_bt = $request->upload_bt;
        $data->status_bt_el = $request->status_bt_el;
        $data->catatan_bt_el = $request->catatan_bt_el;

        if($request->status_bt_el == "Sudah" && $data->status_su_el === "Sudah") {
            self::sendMessageBtEl($data);
        }
        if ($data->status_su_el === "Sudah" && $request->status_bt_el === "Sudah") {
            $data->status_permohonan = "SELESAI";
        } else if ($data->status_su_el === "Sudah" && $request->status_bt_el !== "Sudah") {
            $data->status_permohonan = "KURANG BT EL";
        } else {
            $data->status_permohonan = "PROSES";
        }

        $data->save();
        return $data?:false;
    }
    public static function updateProsesPengukuranVerifikator($request){
        $data = Permohonan::where('id_permohonan',$request->id_permohonan)->first();
        $data->latitude = $request->latitude;
        $data->longitude = $request->longitude;
        $data->petugas_verifikator = Auth::getUser()->name;
        $data->petugas_verifikator_id = Auth::getUser()->id;
        $data->jenis_pemetaan = $request->jenis_pemetaan;
        $data->catatan_verifikator = $request->catatan_verifikator;

        $data->petugas_pengukuran = null;
        $data->petugas_lapang_id = null;
        if($request->jenis_pemetaan === 'Kelapangan'){
            $data->petugas_pengukuran = $request->namaPetugasLapang;
            $data->petugas_lapang_id = $request->petugas_lapang;
        }
        if($request->jenis_pemetaan === 'Tolak'){
            $data->status_permohonan = 'TOLAK';
        }
        if($request->status_permohonan === 'TOLAK') {
            $data->jenis_pemetaan = null;
            $data->petugas_verifikator = null;
            $data->petugas_verifikator_id = null;
            $data->status_permohonan = 'PROSES';
        }
        if($request->status_pemetaan == 'Tolak') {
            $data->status_pemetaan = null;
            $data->petugas_pemetaan = null;
        }

        $data->save();
        return $data?:false;
    }
    public static function updateProsesPengukuranBt($request){
        $data = Permohonan::where('id_permohonan',$request->id_permohonan)->first();
        $data->latitude = $request->latitude;
        $data->longitude = $request->longitude;
        $data->petugas_bt = Auth::getUser()->name;
        $data->petugas_bt_id = Auth::getUser()->id;
        $data->upload_bt = $request->upload_bt;
        $data->save();
        return $data?:false;
    }


    public static function store($request){
        $data = ($request->id_permohonan) ? Permohonan::where('id_permohonan',$request->id_permohonan)->first() : new Permohonan;
        $data->no_permohonan = $request->no_registrasi;
        $data->nama_pemohon = $request->nama_pemohon;
        $data->telepon_pemohon = $request->telep_pemohon;
        $data->nama_kuasa = $request->nama_kuasa;
        $data->telepon_kuasa = $request->telp_kuasa;
        $data->provinsi_id = 35;
        $data->kabupaten_id = 3516;
        $data->kecamatan_id = $request->kecamatan_id;
        $data->desa_id = $request->desa_id;
        $data->jenis_hak = $request->jenis_hak;
        $data->no_sertifikat = $request->no_sertifikat;
        $data->latitude = $request->latitude;
        $data->longitude = $request->longitude;
        $data->validated = '0';
        // $data->keperluan = $request->keperluan;
        // $data->petugas_klas_valentin = $request->petugas_klas_valentin;#??gatau bener apa gak
        $data->tgl_input = $request->tanggal;
        $data->tanggal_pengajuan = $request->tanggal;
        // $data->out = $request->out;
        $data->jenis_pemetaan = $request->jenis_pemetaan;
        if($request->jenis_pemetaan !== 'Tolak'){
            $data->petugas_lapang_id = ($request->petugas_lapang!='' || $request->petugas_lapang!=null)? $request->petugas_lapang :null;#id petugas
            $data->petugas_pengukuran = ($request->petugas_lapang != '') ? $request->namaPetugasLapang : null;#nama petugas
            $data->status_pengukuran = $request->proses_pengukuran_lapang;
            $data->petugas_pemetaan_id = ($request->petugas_pemetaan!='' || $request->petugas_pemetaan!=null)? $request->petugas_pemetaan :null;#id petugas
            $data->petugas_pemetaan = ($request->petugas_pemetaan != '') ? $request->namaPetugasPemetaan : null;#nama petugas
            $data->status_pemetaan = $request->status_pemetaan;
            $data->petugas_suel_id = ($request->petugas_su_el!='' || $request->petugas_su_el!=null)? $request->petugas_su_el :null;#id petugas
            $data->petugas_su_el = ($request->petugas_su_el != '') ? $request->namaPetugasSuel : null;#nama petugas
            $data->status_su_el = $request->status_su_el;
            $data->catatan_su_el = $request->catatan_su_el;
            $data->upload_bt = $request->upload_bt;
            $data->petugas_btel_id = ($request->petugas_bt_el!='' || $request->petugas_bt_el!=null)? $request->petugas_bt_el :null;#id petugas
            $data->petugas_bt_el = ($request->petugas_bt_el != '') ? $request->namaPetugasBtel : null;#nama petugas
            $data->status_bt_el = $request->status_bt_el;
            $data->catatan_bt_el = $request->catatan_bt_el;
            $data->tanggal_setor = $request->tanggal_setor;
            $data->kendala = $request->kendala;


            if ($request->status_su_el === "Sudah" && $request->status_bt_el === "Sudah") {
                $request->status = "SELESAI";
            } else if ($request->status_su_el === "Sudah" && $request->status_bt_el !== "Sudah") {
                $request->status = "KURANG BT EL";
            } else {
                $request->status = "PROSES";
            }

            $data->status_permohonan = $request->status;
            $data->pemberitahuan = $request->pemberitahuan;
            $data->catatan_permohonan = $request->catatan_permohonan;
        }
        $data->save();
        return $data?:false;
    }

    public static function sendMessage($request){
        $data = Permohonan::where('id_permohonan',$request->id_permohonan)->first();
        $text = "Mohon maaf!\n";
        $text .= "Pengajuan validasi Anda dengan Nomor Registrasi $data->no_permohonan dinyatakan tidak bisa diteruskan/ditolak.\n\n";
        $text .= "Silakan melakukan pengajuan ulang dengan data yang benar, atau bisa langsung melakukan pengajuan validasi di Kantor BPN Jombang.";
        $text .= "*Catatan = $data->catatan_verifikator*";

        $curl = curl_init();
        $token = "GZBy1FssJ0x0QQWIA5JnOGCskFQcrUm57FhKoBjAllgHqYyfrut25BA";
        $secret_key = "pvB1Kr9i";
        $payload = [
            "data" => [
                [
                    'phone' => '0'.$data->telepon_pemohon,
                    'message' => $text,
                ],
            ]
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token.$secret_key",
                "Content-Type: application/json"
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload) );
        curl_setopt($curl, CURLOPT_URL,  "https://texas.wablas.com/api/v2/send-message");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    public static function sendMessageSuEl($data){
        $data = Permohonan::where('id_permohonan',$data->id_permohonan)->first();
        $text = "No. Reg : $data->no_permohonan\n";
        $text .= "Jenis Hak : $data->jenis_hak\n";
        $text .= "No. Sertifikat : $data->no_sertifikat\n";
        $text .= "Desa  : " . $data->desa->nama . "\n";
        $text .= "Kecamatan   : " . $data->kecamatan->nama . "\n\n";
        $text .= "*SUDAH MELAKUKAN VALIDASI BIDANG*\n\n";
        $text .= "_Silahkan datang Ke Kantor Pertanahan Kab. Jombang dengan membawa sertipikat asli untuk melanjutkan proses_";

        $curl = curl_init();
        $token = "GZBy1FssJ0x0QQWIA5JnOGCskFQcrUm57FhKoBjAllgHqYyfrut25BA";
        $secret_key = "pvB1Kr9i";
        $payload = [
            "data" => [
                [
                    'phone' => '0'.$data->telepon_pemohon,
                    'message' => $text,
                ],
            ]
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token.$secret_key",
                "Content-Type: application/json"
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload) );
        curl_setopt($curl, CURLOPT_URL,  "https://texas.wablas.com/api/v2/send-message");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    public static function sendMessageBtEl($data){
        $data = Permohonan::where('id_permohonan',$data->id_permohonan)->first();
        $text = "Permohonan dengan No Registrasi $data->no_permohonan Sudah Selesai.Silahkan datang ke Kantor Pertanahan Kab Jombang
         untuk melanjutkan proses pendaftaran berikutnya.\n";

        $curl = curl_init();
        $token = "GZBy1FssJ0x0QQWIA5JnOGCskFQcrUm57FhKoBjAllgHqYyfrut25BA";
        $secret_key = "pvB1Kr9i";
        $payload = [
            "data" => [
                [
                    'phone' => '0'.$data->telepon_pemohon,
                    'message' => $text,
                ],
            ]
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token.$secret_key",
                "Content-Type: application/json"
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload) );
        curl_setopt($curl, CURLOPT_URL,  "https://texas.wablas.com/api/v2/send-message");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
