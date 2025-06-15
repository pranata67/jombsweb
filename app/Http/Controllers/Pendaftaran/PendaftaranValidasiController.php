<?php

namespace App\Http\Controllers\Pendaftaran;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Helpers\Registrasi;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Webhook\WebhookController;
use App\Models\Desa;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\KodeRegistrasi;
use App\Models\Permohonan;
use App\Models\Provinsi;
use App\Models\PermohonanImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Webhook\ChatBot;

class PendaftaranValidasiController extends Controller
{
    public function index(Request $request, $kode_acak) {
        // cari kode acak nya itu valid atau ga?
        // kalo valid => return view(bisa lanjut)
        // kalo ga valid => kasih respon not found
      if(
        !($data['code'] = KodeRegistrasi::where([
          ['kode_acak',$kode_acak],
          ['is_active','true']
        ])->first())
      ) {
        return 'Pendaftaran telah diselesaikan';
      }

      $data['data'] = Permohonan::with('kecamatan','desa')->get();
      $data['kecamatan'] = Kecamatan::where('kabupaten_id', '3516')->get();
      $data['desa'] = Desa::where('kecamatan_id', $request->id)->get();

      $time = date("H:i:s", time());
      $day_of_week = date("N");

      $start_time = "00:00:00";
      //dipake buat debug , jangan lupa dibalikin lagi ke 15:00 kalo udah selesai
      $end_time = "23:59:54";
      //ganti lagi ke day of week 5 kalo udah selesai
      if(($time < $start_time || $time > $end_time) || ($day_of_week < 0 || $day_of_week > 7)) {
        return view('pendaftaran.service-unavailable');
      } else {
        return view('pendaftaran.main', $data);
      }
    }

    public function store(Request $request) {
      $validator = Validator::make(
        $request->all(),
        [
          'nama_pemohon' => 'required',
          'file_sertifikat' => 'required',
          'no_sertifikat' => 'required',
        ],
        [
            'required' => ' Kolom :attribute Wajib diisi'
        ]
      );

      if ($validator->fails()) {
        $pesan = $validator->errors();
        $pakai_pesan = join(',',$pesan->all());
        $return = ['status' => 'warning', 'code' => 201, 'message' => $pakai_pesan];
        return response()->json($return);
      }


      try {
        $desa = Desa::find($request->desa);
        $nama_desa = $desa->nama;

        DB::beginTransaction();
        $newdata = (!empty($request->id)) ? Permohonan::find($request->id) : new Permohonan;

        $latestPermohonan = Permohonan::orderByDesc('id_permohonan')->latest()->first();
        $latestNumber = intval(substr($latestPermohonan->no_permohonan, 1));
        $no_permohonan = $latestNumber + 1;

        $no_permohonan_result =  'A'.$no_permohonan;

        $newdata->no_permohonan = $no_permohonan_result;
        $newdata->tgl_input = date('Y-m-d');

        $newdata->nama_pemohon = $request->nama_pemohon;
        $newdata->telepon_pemohon = $request->telepon_pemohon;
        $newdata->nama_kuasa = $request->nama_kuasa;
        $newdata->telepon_kuasa = $request->telepon_kuasa;
        $newdata->jenis_hak = $request->jenis_hak;
        $newdata->no_sertifikat = $request->no_sertifikat;
        $newdata->provinsi_id = '35';
        $newdata->kabupaten_id = '3516';
        $newdata->kecamatan_id = $request->kecamatan;
        $newdata->desa_id = $request->desa;
        $newdata->latitude = $request->latitude;
        $newdata->longitude = $request->longitude;
        $newdata->status_permohonan = 'PROSES';
        $newdata->catatan_permohonan = $request->catatan_permohonan;

        if (!empty($request->file_sertifikat)) {
          if (!empty($newdata->file_sertifikat)) {
            if (is_file($newdata->file_sertifikat)) {
              // Storage::delete($newdata->file_sertifikat);
              unlink(storage_path('public/uploads/registrasi/'.$newdata->file_sertifikat));
              // File::delete($newdata->file_sertifikat);
            }
          }
          $file = $request->file('file_sertifikat');
          if($request->hasFile('file_sertifikat')){
            $nama_file_sertif = str_replace(' ', '_', $file->getClientOriginalName());
            $ext_file_sertif = $file->getClientOriginalExtension();
            $filename = $no_permohonan_result.'_'.$nama_desa.'_'.'FILE_SERTIFIKAT'.'_'.$nama_file_sertif.'_'.time().'.'.$ext_file_sertif;
            // $filename = $file->getClientOriginalName();
            // $ext_foto = $file->getClientOriginalExtension();
            // $filename = $newdata->no_agenda."-".date('YmdHis').".".$ext_foto;
            $file->storeAs('public/uploads/registrasi/',$filename);
            file_put_contents(public_path('uploads/registrasi/' . $filename), file_get_contents($file->getRealPath()));
            $newdata->file_sertifikat = $filename;
          }
        }

        if ($request->hasFile('file_pendukung')) {
            $filePendukungArray = [];

            $file = $request->file('file_pendukung');

            $nama_file_pendukung = str_replace(' ', '_', $file->getClientOriginalName());
            $ext_file_pendukung = $file->getClientOriginalExtension();
            $filename = $no_permohonan_result.'_'.$nama_desa.'_'.'FILE_PENDUKUNG'.'_'.$nama_file_pendukung.'_'.time().'.'.$ext_file_pendukung;

            // $filename = $file->getClientOriginalName();
            // $ext_foto = $file->getClientOriginalExtension();
            $file->storeAs('public/uploads/registrasi/', $filename);
            file_put_contents(public_path('uploads/registrasi/' . $filename), file_get_contents($file->getRealPath()));
            $filePendukungArray[] = $filename;

            $newdata->file_pendukung = json_encode($filePendukungArray);
        }

        // return $this->sendMessage($newdata);
        // return $newdata;
        $newdata->save();

        $public_path = 'uploads/registrasi';
        $file = $request->file('images');
        if(!empty($file)){
            foreach($file as $item){
                $nama_file_gambar = str_replace(' ', '_', $item->getClientOriginalName());
                $ext_file_gambar = $item->getClientOriginalExtension();
                $image = $no_permohonan_result.'_'.$nama_desa.'_'.'FILE_GAMBAR'.'_'.$nama_file_gambar.'_'.time().'.'.$ext_file_gambar;
                // $image = time().'_'.$item->getClientOriginalName();
                $item->move(public_path($public_path), $image);

                $images = new PermohonanImages;
                $images->permohonan_id = $newdata->id_permohonan;
                $images->gambar = $image;
                $images->save();
            }
        }

        DB::commit();

        if($chatBot = ChatBot::filter($request)) {
            $status   = $chatBot->status_chat;
            $isNULL   = $status=="";
            $isINFO   = $status=="INFO";
            $isMULAI  = $status=="MULAI";
            $isFINAL  = $status=="FINAL";
        }
        $this->sendMessage($newdata);
        $kode = KodeRegistrasi::where('kode_acak', $request->kode_unik)->first();
        $kode->update(['is_active' => 'false']);
        $request->merge([
            'statusChat' => 'FINAL',
            'phone' => $kode->no_hp,
            'is_active' => 'false',
            'statusChatSebelumnya' => 'PERMOHONAN VALIDASI'
        ]);
        $webhook = new WebhookController;
        if (!$webhook->updateStatusChatBot($request)) {
            DB::rollback();
            // return $this->textInfo();
        }
        DB::commit();

        return response()->json([
          'status' => 'success',
          'code' => 200,
          'message' => 'Berhasil Menyimpan data',
          'data' => $newdata,
        ]);

      } catch (\Exception $e){
        DB::rollback();
        return response()->json([
          'status' => 'error',
          'code' => 500,
          'message' => $e->getMessage(),
          'line' => $e->getLine(),
        ]);
      }
    }

    public function cekNoSertifikat(Request $request) {
      $data = Permohonan::where('no_sertifikat', $request->no_sertifikat)->first();
      if(!empty($data)) {
        return response()->json(['message' => 'Data ditemukan', 'data' => $data]);
      } else {
        return response()->json(['message' => 'Data tidak ditemukan']);
      }
    }

    // public function sendMessage($params) {
    //   // return $params;
    //     $text = "*Selamat !* Pendaftaran Anda berhasil!\n\n";
    //     $text .= "*No. Reg            : $params->no_permohonan*\n";
    //     $text .= "*Nama Pemohon       : $params->nama_pemohon*\n";
    //     if (!empty($params->nama_kuasa)) {
    //         $kuasa = $params->nama_kuasa;
    //     } else {
    //         $kuasa = "-";
    //     }
    //     // $text .= "*Kuasa              : $params->nama_kuasa*\n";
    //     $text .= "*Kuasa              : $kuasa*\n";
    //     $text .= "*Jenis Hak          : $params->jenis_hak*\n";
    //     $text .= "*No. Sertipikat     : $params->no_sertifikat*\n";
    //     $text .= "*Desa               : " . $params->desa->nama ."*\n";
    //     $text .= "*Kecamatan          :  " . $params->kecamatan->nama . "*\n";
    //     $text .= "*Status             : $params->status_permohonan*\n\n";
    //     $text .= "Simpan nomor registrasi Anda. Anda dapat melakukan pengecekan berkala untuk melihat status permohonan validasi anda.\n";

    //     $curl = curl_init();
    //     // $token = "4q7yt0VrSmDqpl6KV2yMzH2cI99fL8xPLUYAgrEDAKOQWbOAxD0uSsf3VtBjHhVV";
    //     $token = "GZBy1FssJ0x0QQWIA5JnOGCskFQcrUm57FhKoBjAllgHqYyfrut25BA";
    //     $secret_key = "cFkkwQq";
    //     $random = true;
    //     $payload = [
    //         "data" => [
    //             [
    //                 'phone' => $params->telepon_pemohon,
    //                 'message' => $text,
    //             ],
    //             [
    //                 'phone' => $params->telepon_kuasa,
    //                 'message' => $text,
    //             ]
    //         ]
    //     ];
    //     curl_setopt($curl, CURLOPT_HTTPHEADER,
    //         array(
    //             "Authorization: $token.$secret_key",
    //             "Content-Type: application/json"
    //         )
    //     );
    //     curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload) );
    //     curl_setopt($curl, CURLOPT_URL,  "https://texas.wablas.com/api/v2/send-message");
    //     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    //     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    //     $result = curl_exec($curl);
    //     curl_close($curl);
    //     return $result;
    // }

    public function sendMessage($params) {
    $text = "*Selamat !* Pendaftaran Anda berhasil!\n\n";
    $text .= "*No. Reg            : $params->no_permohonan*\n";
    $text .= "*Nama Pemohon       : $params->nama_pemohon*\n";
    $kuasa = !empty($params->nama_kuasa) ? $params->nama_kuasa : "-";
    $text .= "*Kuasa              : $kuasa*\n";
    $text .= "*Jenis Hak          : $params->jenis_hak*\n";
    $text .= "*No. Sertipikat     : $params->no_sertifikat*\n";
    $text .= "*Desa               : " . $params->desa->nama ."*\n";
    $text .= "*Kecamatan          :  " . $params->kecamatan->nama . "*\n";
    $text .= "*Status             : $params->status_permohonan*\n\n";
    $text .= "Simpan nomor registrasi Anda. Anda dapat melakukan pengecekan berkala untuk melihat status permohonan validasi anda.\n";

    $token = "GZBy1FssJ0x0QQWIA5JnOGCskFQcrUm57FhKoBjAllgHqYyfrut25BA";
    $secret_key = "pvB1Kr9i";
    
    $payload = [
        "data" => [
            [
                'phone' => $params->telepon_pemohon,
                'message' => $text,
            ],
            [
                'phone' => $params->telepon_kuasa,
                'message' => $text,
            ]
        ]
    ];
    
    try {
        $response = Http::withHeaders([
            'Authorization' => $token . '.' . $secret_key
        ])->post('https://texas.wablas.com/api/v2/send-message', $payload);

        // Log the response for debugging
        if ($response->failed()) {
            Log::error('Wablas request failed: ' . $response->body());
        } else {
            Log::info('Wablas request successful: ' . $response->body());
        }

        return $response->body(); // Return the response body as a string

    } catch (\Exception $e) {
        // Handle connection exceptions, etc.
        Log::error('Failed to connect to Wablas API: ' . $e->getMessage());
        return null;
    }
}

    public function serviceUnavailable() {
        return view('pendaftaran.service-unavailable');
    }
}
