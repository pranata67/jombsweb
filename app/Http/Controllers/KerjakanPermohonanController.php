<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;
use App\Models\Kecamatan;
use App\Models\KerjakanPermohonanLapang;
use App\Models\KerjakanPermohonanPemetaan;
use App\Models\KerjakanPermohonanSuel;
use App\Models\KerjakanPermohonanBtel;
use App\Models\KerjakanPermohonanVerifikator;
use App\Models\KerjakanPermohonanBt;
use App\Models\KerjakanPermohonanLapangValidasi;
use App\Models\PetugasLapangan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class KerjakanPermohonanController extends Controller
{

  public function kerjakanForm(Request $request){
    // return 'test';
    // return $request->all();
    $file = 'kerjakan.'.$request->petugas.'.main';#kalo aksi kerjakan
    $data['isDetail'] = 0;
    if($request->isDetail && $request->tugas === 'validasi'){
        #kalo aksi validasi dan detail
        $data['isDetail'] = 1;
        $file = $request->tugas;
    }elseif(!$request->isDetail && $request->tugas === 'validasi'){
        #kalo aksi validasi
        $data['isDetail'] = 0;
        $file = $request->tugas;
    }else{
        #kalo kerjakan detail
        if($request->isDetail){
            $data['isDetail'] = 1;
        }
    }

    $data['data'] = Permohonan::with([
        'permohonan_images',
        'kerjakan_permohonan_lapang.user',
        'kerjakan_permohonan_verifikator.user',
        'kerjakan_permohonan_pemetaan.user',
        'kerjakan_permohonan_lapang.user',
        'kerjakan_permohonan_suel.user',
        'kerjakan_permohonan_bt.user',
        'kerjakan_permohonan_btel.user'])->where('id_permohonan',$request->id)->first();

        $permohonan_terakhir = $data['data']->updated_at;
        $verifikator = $data['data']->kerjakan_permohonan_verifikator??null;
        $pemetaan = $data['data']->kerjakan_permohonan_pemetaan??null;
        $lapang = $data['data']->kerjakan_permohonan_lapang??null;
        $lapang_validasi = $data['data']->kerjakan_permohonan_lapang_validasi??null;
        $suel = $data['data']->kerjakan_permohonan_suel??null;
        $bt = $data['data']->kerjakan_permohonan_bt??null;
        $btel = $data['data']->kerjakan_permohonan_btel??null;
        $petugas = null;

        $lastHandler = null;
        $lastUpdate = null;

        $steps = [
            'kerjakan_permohonan_verifikator',
            'kerjakan_permohonan_pemetaan',
            'kerjakan_permohonan_lapang',
            'kerjakan_permohonan_suel',
            'kerjakan_permohonan_bt',
            'kerjakan_permohonan_btel'
        ];
        // return Auth::getUser()->id;
        // return $data['data'];
        if(
            isset($data['data']->kerjakan_permohonan_lapang) ||
            isset($data['data']->kerjakan_permohonan_pemetaan) ||
            isset($data['data']->kerjakan_permohonan_suel) ||
            isset($data['data']->kerjakan_permohonan_bt) ||
            isset($data['data']->kerjakan_permohonan_btel) ||
            isset($data['data']->kerjakan_permohonan_lapang_validasi)
        ) {
            foreach ($steps as $step) {
                if ($data['data']->$step) {
                    if (!empty($data['data']->$step) && !empty($data['data']->$step->user)) {
                        $stepUpdatedAt = $data['data']->$step->updated_at;

                        if (is_null($lastUpdate) || $stepUpdatedAt > $lastUpdate) {
                            $lastUpdate = $stepUpdatedAt;
                            $lastHandler = $data['data']->$step->user;
                        }
                    }
                    // return $data['data']->kerjakan_permohonan_suel;
                    if($lastHandler) {
                        if($lastHandler->level_user == 2) {
                            $levelPetugas = 'Lapangan';
                        } elseif($lastHandler->level_user == 3) {
                            $levelPetugas = 'Pemetaan';
                        } elseif($lastHandler->level_user == 4) {
                            $levelPetugas = 'SuEl';
                        } elseif($lastHandler->level_user == 5) {
                            $levelPetugas = 'BTEL';
                        } elseif($lastHandler->level_user == 11) {
                            $levelPetugas = 'Verifikator';
                        } elseif($lastHandler->level_user == 12) {
                            $levelPetugas = 'BT';
                        }
                    }
                }
            }
        }

        // return $lastHandler;

    if($request->tugas == 'validasi'){#kalo aksi validasi
        $data['data'] = Permohonan::with(['kerjakan_permohonan_lapang_validasi.user'])->where('id_permohonan',$request->id)->first();
        $data['kecamatan'] = Kecamatan::where('kabupaten_id', '3516')->get();
    }else{
        if(Auth::getUser()->level_user == '2'){
            $data['data'] = Permohonan::with([
                'permohonan_images',
                'kerjakan_permohonan_lapang.user'])->where('id_permohonan',$request->id)->first();

            if(!$data['isDetail']){
                $issetPetugasKerjakan = $data['data'];

                if($issetPetugasKerjakan->isKerjakan_petugas_lapangan && $issetPetugasKerjakan->petugas_lapang_id !== null && $issetPetugasKerjakan->petugas_lapang_id != Auth::getUser()->id){
                    return ['status' => 'warning', 'message' => 'Data Sedang dikerjakan oleh ' . $lastHandler->name . ' ' . '('. $levelPetugas . ')', 'content' => '']; # . $lastHandler->name . ' ' . '('. $levelPetugas . ')'
                }

                DB::beginTransaction();
                try {
                    $issetPetugasKerjakan->petugas_lapang_id = Auth::getUser()->id;
                    $issetPetugasKerjakan->isKerjakan_petugas_pemetaan = true;
                    $issetPetugasKerjakan->save();

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    Log::info(json_encode([
                        'code' => 500,
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ],JSON_PRETTY_PRINT));
                    return ['status' => 'error', 'message' => 'Tidak dapat mengerjakan', 'content' => ''];
                }
            }
        }elseif(Auth::getUser()->level_user == '3'){
            $data['data'] = Permohonan::with(['permohonan_images','kerjakan_permohonan_pemetaan.user'])->where('id_permohonan',$request->id)->first();
            $data['petugas_lapangan'] = User::where('level_user',2)->get();
            $data['petugas_pemetaan'] = Permohonan::with('petugasPemetaan')->where('id_permohonan',$request->id)->first();
            // return $data['petugas_pemetaan'];

            if(!$data['isDetail']){
                $issetPetugasKerjakan = $data['data'];

                if($issetPetugasKerjakan->isKerjakan_petugas_pemetaan && $issetPetugasKerjakan->petugas_pemetaan_id !== null && $issetPetugasKerjakan->petugas_pemetaan_id != Auth::getUser()->id){
                    return ['status' => 'warning', 'message' => 'Data Sedang dikerjakan oleh ' . $lastHandler->name . ' ' . '('. $levelPetugas . ')', 'content' => '']; # . $lastHandler->name . ' ' . '('. $levelPetugas . ')'  ===  . $data['petugas_pemetaan']->petugas_pemetaan->name . '(Pemetaan)'
                }

                DB::beginTransaction();
                try {
                    $issetPetugasKerjakan->petugas_pemetaan_id = Auth::getUser()->id;
                    $issetPetugasKerjakan->isKerjakan_petugas_pemetaan = true;
                    $issetPetugasKerjakan->save();

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    Log::info(json_encode([
                        'code' => 500,
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ],JSON_PRETTY_PRINT));
                    return ['status' => 'error', 'message' => 'Tidak dapat mengerjakan', 'content' => ''];
                }
            }
        }elseif(Auth::getUser()->level_user == '4'){
            $data['data'] = Permohonan::with(['permohonan_images','kerjakan_permohonan_suel.user'])->where('id_permohonan',$request->id)->first();

            // $issetPetugasKerjakan = $data['data'];

            // if($issetPetugasKerjakan->isKerjakan_petugas_suel && $issetPetugasKerjakan->petugas_suel_id !== null && $issetPetugasKerjakan->petugas_suel_id != Auth::getUser()->id){
            //     return ['status' => 'warning', 'message' => 'Data Sedang dikerjakan petugas lain', 'content' => ''];
            // }

            // DB::beginTransaction();
            // try {
            //     $issetPetugasKerjakan->petugas_suel_id = Auth::getUser()->id;
            //     $issetPetugasKerjakan->isKerjakan_petugas_suel = true;
            //     $issetPetugasKerjakan->save();

            //     DB::commit();
            // } catch (\Exception $e) {
            //     DB::rollback();
            //     Log::info(json_encode([
            //         'code' => 500,
            //         'message' => $e->getMessage(),
            //         'file' => $e->getFile(),
            //         'line' => $e->getLine(),
            //     ],JSON_PRETTY_PRINT));
            //     return ['status' => 'error', 'message' => 'Tidak dapat mengerjakan', 'content' => ''];
            // }
        }elseif(Auth::getUser()->level_user == '5'){
            $data['data'] = Permohonan::with(['permohonan_images','kerjakan_permohonan_btel.user'])->where('id_permohonan',$request->id)->first();

            if(!$data['isDetail']){
                $issetPetugasKerjakan = $data['data'];

                if($issetPetugasKerjakan->isKerjakan_petugas_btel && $issetPetugasKerjakan->petugas_bt_id !== null && $issetPetugasKerjakan->petugas_bt_id != Auth::getUser()->id){
                    return ['status' => 'warning', 'message' => 'Data Sedang dikerjakan oleh ' . $lastHandler->name . ' ' . '('. $levelPetugas . ')'  , 'content' => ''];
                }

                DB::beginTransaction();
                try {
                    $issetPetugasKerjakan->petugas_bt_id = Auth::getUser()->id;
                    $issetPetugasKerjakan->isKerjakan_petugas_btel = true;
                    $issetPetugasKerjakan->save();

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    Log::info(json_encode([
                        'code' => 500,
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ],JSON_PRETTY_PRINT));
                    return ['status' => 'error', 'message' => 'Tidak dapat mengerjakan', 'content' => ''];
                }
            }
        }elseif(Auth::getUser()->level_user == '11'){
            $data['data'] = Permohonan::with(['permohonan_images','kerjakan_permohonan_verifikator.user','petugasVerifikator'])->where('id_permohonan',$request->id)->first();
            $data['petugas_lapangan'] = User::where('level_user',2)->get();

            if(!$data['isDetail']){
                $issetPetugasKerjakan = $data['data'];
                // return $issetPetugasKerjakan;

                if($issetPetugasKerjakan->petugas_verifikator_id && $issetPetugasKerjakan->petugas_verifikator_id != Auth::getUser()->id){
                    return ['status' => 'warning', 'message' => 'Data Sedang dikerjakan oleh ' . $data['data']->petugasVerifikator->name . ' ( Verifikator )', 'content' => '']; #  . $lastHandler->name . ' ' . '('. $levelPetugas . ')'
                }

                DB::beginTransaction();
                try {
                    $issetPetugasKerjakan->petugas_verifikator_id = Auth::getUser()->id;
                    $issetPetugasKerjakan->isKerjakan_petugas_pemetaan = true;
                    $issetPetugasKerjakan->save();

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    Log::info(json_encode([
                        'code' => 500,
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ],JSON_PRETTY_PRINT));
                    return ['status' => 'error', 'message' => 'Tidak dapat mengerjakan', 'content' => ''];
                }
            }
        }elseif(Auth::getUser()->level_user == '12'){
            $data['data'] = Permohonan::with(['permohonan_images','kerjakan_permohonan_bt.user'])->where('id_permohonan',$request->id)->first();

            if(!$data['isDetail']){
                $issetPetugasKerjakan = $data['data'];

                if($issetPetugasKerjakan->isKerjakan_petugas_bt && $issetPetugasKerjakan->petugas_bt !== null && $issetPetugasKerjakan->petugas_bt != Auth::getUser()->id){
                    return ['status' => 'warning', 'message' => 'Data Sedang dikerjakan oleh ' . $lastHandler->name . ' ' . '('. $levelPetugas . ')'  , 'content' => ''];
                }

                DB::beginTransaction();
                try {
                    $issetPetugasKerjakan->petugas_bt = Auth::getUser()->id;
                    $issetPetugasKerjakan->isKerjakan_petugas_bt = true;
                    $issetPetugasKerjakan->save();

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    Log::info(json_encode([
                        'code' => 500,
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ],JSON_PRETTY_PRINT));
                    return ['status' => 'error', 'message' => 'Tidak dapat mengerjakan', 'content' => ''];
                }
            }
        }elseif(Auth::getUser()->level_user == '1'){
            return Redirect::route('form-registrasi',['id'=>$request->id,'detail'=>$data['isDetail']]);
        }else{
            return ['status' => 'error', 'message' => 'Tidak Ada Akses'];
        }
    }

    // $content = view('permohonan.'.$file, $data)->render();
    // return $data;
    // dd($file);
    $content = view('permohonan.'.$file, $data)->render();
	return ['status' => 'success', 'content' => $content];
  }

  public function tutupKerjakan(Request $request){
    DB::beginTransaction();
    try {
        $data = Permohonan::find($request->id);
        if(Auth::getUser()->level_user == '2'){
            $data->isKerjakan_petugas_lapangan = false;
        }elseif(Auth::getUser()->level_user == '3'){
            $data->isKerjakan_petugas_pemetaan = false;
        }elseif(Auth::getUser()->level_user == '4'){
            $data->isKerjakan_petugas_suel = false;
        }elseif(Auth::getUser()->level_user == '11'){
            $data->isKerjakan_petugas_verifikator = false;
        }elseif(Auth::getUser()->level_user == '5'){
            $data->isKerjakan_petugas_btel = false;
        }elseif(Auth::getUser()->level_user == '12'){
            $data->isKerjakan_petugas_bt = false;
        }else{
            return ['status' => 'error', 'message' => 'Tidak Ada Akses'];
        }
        $data->save();
        DB::commit();
        return ['status' => 'success', 'message' => 'ok'];
    } catch (\Exception $e) {
        DB::rollback();
        Log::info(json_encode([
            'code' => 500,
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ],JSON_PRETTY_PRINT));
        return ['status' => 'error', 'message' => 'Internal Server Error!'];
    }
  }

  public function kerjakan(Request $request){
    // return 'tes';
    try {

        // save
        DB::beginTransaction();
        if(Auth::getUser()->level_user == '2'){
            // dd($request->all());
            if(isset($request->tugas) && $request->tugas === 'validasi'){#kerjanakn validasi

                $public_path = 'uploads/validasi';
                $req_foto_lokasi = $request->file('foto_lokasi');
                $req_file_dwg = $request->file('file_dwg');
                $req_sket_gambar = $request->file('sket_gambar');
                $req_txt_csv = $request->file('txt_csv');

                $foto_lokasi = null;
                $file_dwg = null;
                $sket_gambar = null;
                $txt_csv = null;
                if(!empty($req_foto_lokasi)){
                    $foto_lokasi = time().'_'.$req_foto_lokasi->getClientOriginalName();
                    $req_foto_lokasi->move(public_path($public_path), $foto_lokasi);
                }else{
                    if(isset($request->foto_lokasi_old)){
                        $foto_lokasi = $request->foto_lokasi_old;
                    }else{
                        $foto_lokasi = null;
                    }
                }
                if(!empty($req_file_dwg)){
                    $file_dwg = time().'_'.$req_file_dwg->getClientOriginalName();
                    $req_file_dwg->move(public_path($public_path), $file_dwg);
                }else{
                    if(isset($request->file_dwg_old)){
                        $file_dwg = $request->file_dwg_old;
                    }else{
                        $file_dwg = null;
                    }
                }
                if(!empty($req_sket_gambar)){
                    $sket_gambar = time().'_'.$req_sket_gambar->getClientOriginalName();
                    $req_sket_gambar->move(public_path($public_path), $sket_gambar);
                }else{
                    if(isset($request->sket_gambar_old)){
                        $sket_gambar = $request->sket_gambar_old;
                    }else{
                        $sket_gambar = null;
                    }
                }
                if(!empty($req_txt_csv)){
                    $txt_csv = time().'_'.$req_txt_csv->getClientOriginalName();
                    $req_txt_csv->move(public_path($public_path), $txt_csv);
                }else{
                    if(isset($request->txt_csv_old)){
                        $txt_csv = $request->txt_csv_old;
                    }else{
                        $txt_csv = null;
                    }
                }

                if(!empty($request->deletedFile)){
                    $deletedFile = explode(',',$request->deletedFile);
                    $path = public_path('uploads/validasi/');
                    foreach($deletedFile as $value){
                        if($value == 'foto_lokasi'){
                            $foto_lokasi = null;
                            $file = $path.$foto_lokasi;
                        }elseif($value == 'file_dwg'){
                            $file_dwg = null;
                            $file = $path.$file_dwg;
                        }elseif($value == 'sket_gambar'){
                            $sket_gambar = null;
                            $file = $path.$sket_gambar;
                        }elseif($value == 'txt_csv'){
                            $txt_csv = null;
                            $file = $path.$txt_csv;
                        }
                        $cekFile = file_exists($file);
                        if($cekFile){
                            @unlink($file);
                        }
                    }
                }

                $request->merge([
                    'petugas_lapang' => Auth::getUser()->id,
                    'foto_lokasi_name' => $foto_lokasi,
                    'file_dwg_name' => $file_dwg,
                    'sket_gambar_name' => $sket_gambar,
                    'txt_csv_name' => $txt_csv,
                ]);

                $dataKerjakan = KerjakanPermohonanLapangValidasi::store($request);

                $permohonan = Permohonan::find($request->id_permohonan);

                if ($permohonan) {
                    // Update kolom validated menjadi 1
                    $permohonan->validated = '1';
                    $permohonan->save();
                }

            }else{
                // return $request->all();
                $data = Permohonan::updateProsesPengukuranLapang($request);

                $request->merge([
                    'petugas_lapang' => Auth::getUser()->id,
                ]);
                $dataKerjakan = KerjakanPermohonanLapang::store($request);
            }
        }elseif(Auth::getUser()->level_user == '3'){
            // return $request->all();
            $data = Permohonan::updateProsesPengukuranPemetaan($request);

            $request->merge([
                'petugas_pemetaan' => Auth::getUser()->id,
            ]);
            $dataKerjakan = KerjakanPermohonanPemetaan::store($request);
        }elseif(Auth::getUser()->level_user == '4'){
            $data = Permohonan::updateProsesPengukuranSuel($request);

            $request->merge([
                'petugas_su_el' => Auth::getUser()->id,
            ]);
            $dataKerjakan = KerjakanPermohonanSuel::store($request);
        }elseif(Auth::getUser()->level_user == '5'){
            $data = Permohonan::updateProsesPengukuranBtel($request);

            $request->merge([
                'petugas_bt_el' => Auth::getUser()->id,
            ]);
            $dataKerjakan = KerjakanPermohonanBtel::store($request);
        }elseif(Auth::getUser()->level_user == '11'){
            $data = Permohonan::updateProsesPengukuranVerifikator($request);

            if($request->jenis_pemetaan === 'Tolak'){
                $wa = Permohonan::sendMessage($request);
            }


            $id_permohonan = $request->id_permohonan;

            $desa = Permohonan::with('desa')->where('id_permohonan',$id_permohonan)->first();

            $nama_desa = $desa->desa->nama;

            $public_path = 'uploads/registrasi';
            $files = $request->file('file_pendukung');

            if (!empty($files)) {
                $data_file_pendukung = DB::table('permohonan')
                ->where('id_permohonan', $id_permohonan)
                ->select('no_permohonan', 'file_pendukung')
                ->first();

                // $data_file_pendukung = Permohonan::select('file_pendukung')->where('id_permohonan', $id_permohonan)->first();

                $file_lama = json_decode($data_file_pendukung->file_pendukung, true);

                if ($file_lama==null){
                    $file_lama = [$data_file_pendukung->file_pendukung];
                    // $file_lama = json_decode($file_lama, true);
                }

                // var_dump($file_lama);
                // die;

                // return gettype($file_lama);

                foreach ($files as $file) {
                    $nama_file_pendukung = str_replace(' ', '_', $file->getClientOriginalName());
                    $ext_file_pendukung = $file->getClientOriginalExtension();

                    $filename = $data_file_pendukung->no_permohonan.'_'.$nama_desa.'_'.'FILE_PENDUKUNG'.'_'.$nama_file_pendukung.'_'.time().'.'.$ext_file_pendukung;

                    $file->move(public_path($public_path), $filename);

                    $file_lama[]=$filename;
                }

                // return $file_lama;

                $file = Permohonan::find($id_permohonan);
                $file->file_pendukung = $file_lama;
                $file->save();

            }

            // return gettype($data_file_pendukung);
            // die;

            $request->merge([
                'petugas_verifikator' => Auth::getUser()->id,
            ]);
            $dataKerjakan = KerjakanPermohonanVerifikator::store($request);
        }elseif(Auth::getUser()->level_user == '12'){
            $data = Permohonan::updateProsesPengukuranBt($request);

            $request->merge([
                'petugas_bt' => Auth::getUser()->id,
            ]);
            $dataKerjakan = KerjakanPermohonanBt::store($request);
        }else{
            DB::rollback();
            return response()->json(['code'=>400,'message'=>'Tidak ada akses'],400);
        }

        DB::commit();
        return response()->json(['code'=>200,'message'=>'Berhasil menyimpan','data'=>$dataKerjakan],200);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['code'=>500,'message'=>$e->getMessage(),'file'=>$e->getFile(),'line'=>$e->getLine()],500);
    }
  }

  public function tolakKerjakan(Request $request){
    try {
        DB::beginTransaction();
        $data = Permohonan::where('id_permohonan',$request->id)->first();
        if(Auth::getUser()->level_user == '2'){ #lapangan
            $data->petugas_pengukuran = null;
            $data->petugas_lapang_id = null;
            $data->status_pengukuran = null;
            $data->save();

            $request->merge([
                'id_permohonan' => $request->id,
                'petugas_lapang' => Auth::getUser()->id,
                'proses_pengukuran_lapang' => null,
                'catatan_lapangan' => 'Dikembalikan ke Petugas Pemetaan',
            ]);
            $dataKerjakan = KerjakanPermohonanLapang::store($request);
        }elseif(Auth::getUser()->level_user == '3'){ #pemetaan
            $data->jenis_pemetaan = 'Tolak';
            $data->petugas_pemetaan = null;
            $data->petugas_pemetaan_id = null;
            $data->status_pemetaan = null;
            $data->save();

            $request->merge([
                'id_permohonan' => $request->id,
                'petugas_pemetaan' => Auth::getUser()->id,
                'status_pemetaan' => null,
                'catatan_pemetaan' => 'Dikembalikan ke Petugas Verifikator',
            ]);
            $dataKerjakan = KerjakanPermohonanPemetaan::store($request);
        }else{
            DB::rollback();
            return response()->json(['code'=>400,'message'=>'Tidak ada akses'],400);
        }

        DB::commit();
        return response()->json(['code'=>200,'message'=>'Data berhasil ditolak','data'=>$data],200);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['code'=>500,'message'=>$e->getMessage(),'file'=>$e->getFile(),'line'=>$e->getLine()],500);
    }
  }

  public function deleteFileValidasi(Request $request){
    // return $request;
    DB::beginTransaction();
    if(!$data = KerjakanPermohonanLapangValidasi::find($request->id)){
        DB::rollback();
        return response()->json(['status'=>'error','code'=>500,'message'=>'Gagal Hapus file dokumen validasi'],404);
    }
    $path = public_path('uploads/validasi/');
    if($request->file == 'foto_lokasi'){
        $file = $path.$data->foto_lokasi;
    }elseif($request->file == 'file_dwg'){
        $file = $path.$data->file_dwg;
    }elseif($request->file == 'sket_gambar'){
        $file = $path.$data->sket_gambar;
    }elseif($request->file == 'txt_csv'){
        $file = $path.$data->txt_csv;
    }
    $cekFile = file_exists($file);
    if($cekFile){
        @unlink($file);
    }

    try{
        if($request->file == 'foto_lokasi'){
            $data->foto_lokasi = null;
        }elseif($request->file == 'file_dwg'){
            $data->file_dwg = null;
        }elseif($request->file == 'sket_gambar'){
            $data->sket_gambar = null;
        }elseif($request->file == 'txt_csv'){
            $data->txt_csv = null;
        }
        $data->save();
        DB::commit();
        return response()->json(['status'=>'success','code'=>200,'message'=>'Berhasil Hapus file dokumen validasi'],200);
    }catch(\Exception $e){
        DB::rollback();
        return response()->json(['status'=>'error','code'=>500,'message'=>$e->getMessage()],500);
    }
  }

  public function getDataLap() {
    $data = Permohonan::where('id_permohonan',12)->first();
    return $data;
  }
}
