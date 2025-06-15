<?php

namespace App\Http\Controllers\Webhook;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\KodeRegistrasi;
use App\Models\Permohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

# Model Start
use App\Models\Webhook\ChatBot;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use WebGarden\UrlShortener\UrlShortener;

class WebhookController extends Controller
{
	private static $file = 'WebhookController.php';

	public function chatBot(Request $request) {
		header("Content-Type: text/plain");
		// return $request->all();
        // return 'wa baru';
		// Log::info(json_encode($request->all(),JSON_PRETTY_PRINT));
        // $permohonan = (int)$request->message;
        // $data = Permohonan::where('no_sertifikat', 'M 1121')->orWhere('no_permohonan', '2929')->with(['kecamatan', 'desa'])->first();
        // $text = "Berikut ini adalah informasi Status Permohonan anda:\n\n";
        // $text .= "No. Reg        : $data->no_permohonan\n";
        // $text .= "Nama Pemohon   : $data->nama_pemohon\n";
        // $text .= "Kuasa          : $data->nama_kuasa\n";
        // $text .= "Jenis Hak      : $data->jenis_hak\n";
        // $text .= "No. Sertifikat  : $data->no_sertifikat\n";
        // $text .= "Desa           : " . $data->desa->nama . "\n";
        // $text .= "Kecamatan      : " . $data->kecamatan->nama . "\n";
        // $text .= "Status         : $data->status_permohonan\n\n";
        // $text .= "Hubungi kantor BPN jika data ini tidak sesuai";
        // return $text;

        // return $text;
		// $data = Permohonan::where('no_sertifikat', $request->message)->first();
		// return 'tes';
		// $permohonan = (int)$request->message;
		// $data = Permohonan::where('no_sertifikat', $request->message)->orWhere('no_permohonan', $permohonan)->first();
		// if($data) {
		//     return $data;
		// } else {
		//     return 'data tidak ditemukan';
		// }
		// return $request->all();
		// return $this->textInfo();

		DB::beginTransaction();
		try {
			$isFromMe = $request->isFromMe; # true{Account owner}, false{Not the account owner}
			$isGroup = $request->isGroup;
			$phone = $request->phone;

			$message = implode('', explode("\n", $request->message));
			$message = strtoupper(isset($message) ? str_replace(['_', '*'], '', $message) : 'empty');
			$request->request->add(['message' => $message]);

            if(!$isFromMe) {
                $chatBot = ChatBot::filter($request); // Ambil data chatbot berdasarkan phone atau identifier lainnya

                if (!$chatBot) {
                // Jika pengguna belum pernah mengirim pesan sebelumnya, buat data baru
                ChatBot::store($request);
                $request->request->add([
                    'statusChat' => 'INFO',
                ]);
                if (!$this->updateStatusChatBot($request)) {
                    DB::rollback();
                    return 'Mohon maaf, silahkan kirim ulang kata _*INFO*_';
                }
                DB::commit();
                return $this->textInfo();
                } elseif ($chatBot->status_akun == false) {
                // Jika pengguna pernah mengirim pesan, tapi belum mengetik INFO
                    $chatBot->status_akun = true;
                    $request->request->add([
                        'statusChat' => 'INFO',
                    ]);
                if (!$this->updateStatusChatBot($request)) {
                    DB::rollback();
                    return 'Mohon maaf, silahkan kirim ulang kata _*INFO*_';
                }
                DB::commit();
                    return $this->textInfo();
                }

                if ($isFromMe || $isGroup) {
                    return false; // Tidak berlaku di chat group
                }

                if($message=='EMPTY') {
                    return '*Pesan tidak valid!*';
                }

                if($chatBot = ChatBot::filter($request)) {
                    $status   = $chatBot->status_chat;
                    $statusChatSebelumnya   = $chatBot->status_chat_sebelumnya;
                    $isNULL   = $status=="";
                    $isINFO   = $status=="INFO";
                    $isMULAI  = $status=="MULAI";
                    $isFINAL  = $status=="FINAL";
                }

                $is1 = $message==="1";
                $is2 = $message==="2";
                if ((($is1||$is2) && ($isNULL)) || $isINFO) {
                    if($is1) {
                        $request->merge([
                        'statusChat' => 'CEK STATUS',
                        ]);
                        if ($status != "FINAL") {
                            if (!$this->updateStatusChatBot($request)) {
                                DB::rollback();
                                return $this->textInfo();
                            }
                            DB::commit();
                        }
                        return $this->cekStatus();
                    }
                    if($is2) { // || $status=='INFO'
                        $request->request->add([
                            'statusChat' => 'PERMOHONAN VALIDASI',
                        ]);
                        if ($status != "FINAL") {
                            if (!$this->updateStatusChatBot($request)) {
                                DB::rollback();
                                return $this->textInfo();
                            }
                            DB::commit();
                        }
                        $generate = $this->generateSimpleRandomString($request)->getData();
                        if($generate->code!=200){
                            return 'Silahkan kirim ulang pilihan anda';
                        }
                        $today = Carbon::today();
                        $data = KodeRegistrasi::select(DB::raw('DATE(created_at) as tanggal'), 'no_hp', DB::raw('COUNT(*) as total'))
                            ->where('no_hp', $request->phone)
                            ->whereDate('created_at', $today)
                            ->groupBy('tanggal', 'no_hp')
                            ->first();
                        if($data && $data->total > 3) {
                            return 'Mohon maaf, permohonan hanya bisa dilakukan 3 kali, dalam satu hari.';
                        } else {
                            return $this->textPermohonan($request);
                        }
                    }
                    $textIncorrect = $this->textSalahInput();
                    $textInfo = $this->textInfo();
                    return $textIncorrect."\n".$textInfo;
                }

                if($message==='0') {
                    $request->request->add([
                        'statusChat' => 'INFO',
                    ]);
                    if ($status != "FINAL") {
                        if (!$this->updateStatusChatBot($request)) {
                        DB::rollback();
                        return $this->textInfo();
                        }
                        DB::commit();
                    }
                    return $this->textInfo();
                }

                if($status=='CEK STATUS') {
                    $permohonan = $request->message;
                    $data = Permohonan::where('no_sertifikat', $request->message)->orWhere('no_permohonan', $permohonan)->with(['kecamatan', 'desa'])->first();
                    if ($data) {
                        $request->request->add([
                        'statusChat' => 'FINAL',
                        ]);
                        if ($status != "FINAL") {
                            if (!$this->updateStatusChatBot($request)) {
                                DB::rollback();
                                return $this->cekStatus();
                            }
                            DB::commit();
                        }

                        $text = "Berikut detail informasi Status Permohonan anda :\n\n";
                        $text .= "*No. Reg        : $data->no_permohonan*\n";
                        $text .= "*Nama Pemohon   : $data->nama_pemohon*\n";
                        $text .= "*Kuasa          : $data->nama_kuasa*\n";
                        $text .= "*Jenis Hak      :". ($data->jenis_hak ? $data->jenis_hak : '-') ."*\n";
                        $text .= "*No. Sertifikat  : $data->no_sertifikat*\n";
                        $text .= "*Desa           : " . $data->desa->nama . "*\n";
                        $text .= "*Kecamatan      : " . $data->kecamatan->nama . "*\n";
                        $text .= "*Status         : $data->status_permohonan*\n\n";
                        $text .= "Hubungi admin kantor BPN jika data ini tidak sesuai.";
                        echo $text."\n\n";

                        return $this->textPenutup();
                    } else {
                        echo "Maaf, data tidak ditemukan. Silahkan masukkan No. Registrasi Anda dengan benar!\n\n";
                        return "Ketik angka '0' untuk kembali ke menu utama";
                    }
                }

                if($status=='PERMOHONAN VALIDASI') {
                    $request->request->add([
                        'statusChat' => 'INFO',
                    ]);
                    if (!$this->updateStatusChatBot($request)) {
                        DB::rollback();
                        return 'Mohon maaf, silahkan kirim ulang kata _*INFO*_';
                    }
                    DB::commit();
                    return $this->textInfo();
                }


                if($status=='FINAL' || $statusChatSebelumnya=='PERMOHONAN VALIDASI') {
                    $request->request->add([
                        'statusChat' => 'INFO',
                    ]);
                    if ($status == "FINAL") {
                        if (!$this->updateStatusChatBot($request)) {
                        DB::rollback();
                        return $this->textInfo();
                        }
                        DB::commit();
                    }
                    return $this->textInfo();
                }
            }

            DB::commit();
		} catch(\Throwable $e) {
			DB::rollback();
			$arrLog = [
				'title'   => 'ERROR CHATBOT',
				'url'     => $request->url(),
				'file'    => $e->getFile(),
				'line'    => $e->getLine(),
				'message' => $e->getMessage(),
			];
			return [$e->getMessage(),$e->getLine()];
			Helpers::sendErrorSystemToAdmin($arrLog); # Send notif error to wa admin
			// $arrLog['data'] = $request->all();
			Helpers::logging($arrLog); # Logging
			return 'Terjadi kesalahan sistem, silahkan hubungi admin!';
		}
	}

	public function textInfo(){
		$text = "Selamat datang di layanan chatbot BPN Kab Jombang.\n";
		$text .= "Silahkan Pilih Layanan Kami :\n";
		$text .= "*1. Cek Status Pengajuan*\n";
		$text .= "*2. Pengajuan Permohonan Validasi*\n\n";
		$text .= "Ketik 1 atau 2, lalu kirim.";
		return $text;
	}

    // function generateSimpleRandomString($length = 7, $phone) {
    function generateSimpleRandomString(Request $request) {
        $length = $request->length ? : 7;
        $phone = $request->phone;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        // return $randomString;
        try {
            $newdata = new KodeRegistrasi;
            $newdata->no_hp = $phone;
            $newdata->kode_acak = $randomString;
            $newdata->save();
            $request->merge(['kode_acak'=>$newdata->kode_acak]);
            return response()->json([ 'status' => 'success', 'code' => 200, 'message' => 'Berhasil Menyimpan data', 'data' => $newdata,]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error','code' => 500,'message' => $e->getMessage(),'line' => $e->getLine(),]);
        }
    }

    public function textSalahInput() {
        $text = "Pilihan yang anda masukkan Tidak Sesuai! Silahkan ketikkan angka yang ada dalam pilihan. *Cth : 1*\n";
		return $text;
    }

	public function cekStatus() {
      $text = "Silahkan masukkan No. Registrasi Anda!. *Cth : A2912*\n\n";
      $text .= "Ketik angka '0' untuk kembali ke menu utama.\n";
      return $text;
	}

    public function textPermohonan(Request $request) {
      $text = "Silahkan klik tautan berikut ini dan isi formulir sesuai dengan data yang benar :\n\n";
    //   $text .= "https://103.187.215.99/bpn_mojokerto/public/pendaftaran\n\n";
      $text .= "https://bpnjombang.my.id/pendaftaran/$request->kode_acak\n\n";
      $text .= "Ketik angka '0' untuk kembali ke menu utama\n";
      return $text;
    }

    public function textPenutup() {
      $text = "_Terima Kasih telah menggunakan layanan chat BPN Kab. Jombang._";
      return $text;
    }

    public function registrasiBerhasil($params) {
    // FIX 1: The first line should also use .= or be the initial assignment
    $text = "*Selamat !* Pendaftaran Anda berhasil!\n\n";
    
    // FIX 2: This was overwriting the line above. Changed to .=
    $text .= "*No. Reg       : $params->no_permohonan*\n"; 
    $text .= "*Nama Pemohon       : $params->nama_pemohon*\n";
    $text .= "*Kuasa       : $params->nama_kuasa*\n";
    $text .= "*Jenis Hak       : $params->jenis_hak*\n";
    $text .= "*No. Sertipikat     : $params->no_sertifikat*\n";
    $text .= "*Desa       : " . $params->desa->nama ."*\n";
    $text .= "*Kecamatan       :  " . $params->kecamatan->nama . "*\n";
    $text .= "*Status       : $params->status_permohonan*\n\n";
    $text .= "Simpan nomor registrasi Anda. Anda dapat melakukan pengecekan berkala untuk melihat status permohonan validasi anda.\n";

    // FIX 3: The function must return the created string
    return $text;
    }

	public function updateStatusChatBot($params){
		$this->setTimezone();
		$statusChat = $params->statusChat;
		$paramsChatBot = [
			'status_chat' => $statusChat,
			'tanggal_chat' => date('Y-m-d'),
			'jam_chat' => date('H:i:s'),
		];
		if($chatBot = ChatBot::filter($params)){
			$paramsChatBot += [
				'status_chat_sebelumnya' => $chatBot->status_chat,
			];
		}
		if($statusChat=='INPUT_TANGGAL_BEROBAT'){ # $status==PENDAFTARAN{cek di WebhookController}, ketika pilih angka 1{px baru}, 2{px lama}
			$paramsChatBot += [
				'is_pasien_baru' => $params->isPasienBaru, # px baru{true}, lama{false}
				'bot_pasien_id' => $params->idBotPasien, # Id chat_bot_pasien
			];
		}
		if($statusChat=='FINAL'){ # $status==PILIH_POLI{cek di WebhookController}, ketika pilih nomor poli
			$paramsChatBot += [ # Reset jadi null
			'is_pasien_baru' => null,
			'bot_pasien_id' => null,
		];
	}
	$updateChatBot = ChatBot::find($params->phone)->update($paramsChatBot);
	// $updateDataPasien = ChatBotPasien::where([
	// 	['chat_bot_id',$params->phone], # Parent id
	// 	['is_active',true], # Antrian belum di konfirmasi/belum kadaluarsa{true}
	// 	['edited',true], # Tahap pendaftaran/pengambilan antrian belum 100% selesai{belum masuk ke table kiosk-antrian}
	// ])->update([
	// 	'status_chat' => $statusChat,
	// ]);
	return $updateChatBot?true:false;
}

public function cekStatusPendaftaran() {
	$data = Permohonan::where('no_sertifikat', 'M 2965')->orWhere('no_permohonan', '2929')->first();
	if(!empty($data)) {
		return $data;
	} else {
		return 'data tidak ada';
	}
}

public function setTimezone(){
	return date_default_timezone_set('Asia/Jakarta');
}
}
