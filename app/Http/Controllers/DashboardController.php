<?php

namespace App\Http\Controllers;

use App\Imports\ImportPermohonan;
use App\Models\Desa;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Keperluan;
use App\Models\Provinsi;
use App\Models\Permohonan;
use App\Models\User;
use Illuminate\Http\Request;
use Excel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private $menuActive = "dashboard";
    private $submnActive = "";

    public function __construct() {
        // $this->middleware('auth');
    }

    public function index(Request $request) {
        $this->data['menuActive'] = $this->menuActive;
        $this->data['submnActive'] = $this->submnActive;
        $this->data['keperluan'] = Keperluan::get();
        // return $this->data;
        if ($request->ajax()) {
            $data = [];
            $berkas_masuk = Permohonan::count();
            $selesai_suel = Permohonan::where('status_su_el','Sudah')->count();
            $selesai_suel_belum_btel = Permohonan::where('status_su_el','Sudah')->where('status_bt_el','!=','Sudah')->count();
            $selesai_btel = Permohonan::where('status_bt_el','Sudah')->count();
            $selesai_btel_belum_suel = Permohonan::where('status_bt_el','Sudah')->where('status_su_el','!=','Sudah')->count();
            $selesai = Permohonan::where('status_su_el','Sudah')->where('status_bt_el','Sudah')->count();
            // $data[0]['rekap'] = 'berkas_masuk';
            $data[0]['rekap'] = 'Berkas Masuk';
            $data[0]['jumlah'] = $berkas_masuk;
            // $data[1]['rekap'] = 'selesai_suel';
            $data[1]['rekap'] = 'Selesai SUEL';
            $data[1]['jumlah'] = $selesai_suel;
            // // $data[2]['rekap'] = 'selesai_suel_belum_btel';
            // $data[2]['rekap'] = 'Selesai Suel Belum BTEL';
            // $data[2]['jumlah'] = $selesai_suel_belum_btel;
            // // $data[3]['rekap'] = 'selesai_btel';
            // $data[3]['rekap'] = 'Selesai BTEL';
            // $data[3]['jumlah'] = $selesai_btel;
            // // $data[4]['rekap'] = 'selesai_btel_belum_suel';
            // $data[4]['rekap'] = 'Selesai BTEL Belum Suel';
            // $data[4]['jumlah'] = $selesai_btel_belum_suel;
            // $data[5]['rekap'] = 'Selesai';
            // $data[5]['jumlah'] = $selesai;
            return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('jumlah',function($row){
                return number_format($row['jumlah'],0,',','.');
            })
            ->make(true);
        }
        return view('dashboard.main')->with('data', $this->data);
    }

    public function dataLapangan(Request $request){
        $petugas_lapang = User::select('id','name')->where('level_user',2)->with(['permohonan_user_lapang:petugas_lapang_id,petugas_pengukuran,status_pengukuran'])->get();
        $data = [];
        $total_pekerjaan_all = 0;
        $sudah_diukur_all = 0;
        $sudah_kelapang_tidak_dapat_diukur_all = 0;
        $sisa_all = 0;

        foreach($petugas_lapang as $key => $lapang){
            $total_pekerjaan = count($lapang->permohonan_user_lapang);

            $sudah_diukur = 0;
            foreach($lapang->permohonan_user_lapang as $permohonan_user_lapang){
                if($permohonan_user_lapang->status_pengukuran == 'sudah diukur')
                $sudah_diukur++;
            }
            $sudah_kelapang_tidak_dapat_diukur = 0;
            foreach($lapang->permohonan_user_lapang as $permohonan_user_lapang){
                if($permohonan_user_lapang->status_pengukuran == 'sudah kelapang tidak dapat diukur')
                $sudah_kelapang_tidak_dapat_diukur++;
            }

            $sisa = $total_pekerjaan - $sudah_diukur - $sudah_kelapang_tidak_dapat_diukur;

            $data[$key]['nama_petugas'] = $lapang->name;
            $data[$key]['total_pekerjaan'] = $total_pekerjaan;
            $data[$key]['sudah_diukur'] = $sudah_diukur;
            $data[$key]['sudah_kelapang_tidak_dapat_diukur'] = $sudah_kelapang_tidak_dapat_diukur;
            $data[$key]['sisa'] = $sisa;

            // Update total counters
            $total_pekerjaan_all += $total_pekerjaan;
            $sudah_diukur_all += $sudah_diukur;
            $sudah_kelapang_tidak_dapat_diukur_all += $sudah_kelapang_tidak_dapat_diukur;
            $sisa_all += $sisa;
        }

        // Add total row
        $data[] = [
            'nama_petugas' => 'Total',
            'total_pekerjaan' => $total_pekerjaan_all,
            'sudah_diukur' => $sudah_diukur_all,
            'sudah_kelapang_tidak_dapat_diukur' => $sudah_kelapang_tidak_dapat_diukur_all,
            'sisa' => $sisa_all,
            'no_index' => true,
        ];

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('total_pekerjaan',function($row){
                return number_format($row['total_pekerjaan'],0,',','.');
            })
            ->editColumn('sudah_diukur',function($row){
                return number_format($row['sudah_diukur'],0,',','.');
            })
            ->editColumn('sudah_kelapang_tidak_dapat_diukur',function($row){
                return number_format($row['sudah_kelapang_tidak_dapat_diukur'],0,',','.');
            })
            ->editColumn('sisa',function($row){
                return number_format($row['sisa'],0,',','.');
            })
            ->make(true);
    }

    public function getProvinsi() {
        $data = Provinsi::all();
  		return response()->json($data);
    }

    public function getKabupaten(Request $request) {
        $data = Kabupaten::where('provinsi_id', '35')->get();
  		return response()->json($data);
    }

    public function getKecamatan(Request $request) {
        $data = Kecamatan::where('kabupaten_id', '3516')->get();
  		return response()->json($data);
    }

    public function getDesa(Request $request) {
        $data = Desa::where('kecamatan_id', $request->id)->get();
  		return response()->json($data);
    }

    public function importPermohonanExcel(Request $request)
    {
        // $this->validate($request, [
        //   'file' => 'required|mimes:csv,xls,xlsx'
        // ]);
        // $file = $request->file('file');
    //   return  Excel::import(new AnggotaImport(), storage_path('app/public/haji-2054.xlsx'));
        Excel::import(new ImportPermohonan(), storage_path('app/public/storage/data_permohonan.xls'));
        return ['status'=>'success','message' => 'Berhasil Import Excel','title' => 'Success'];
    }

    public function cekStatusPermohonan(Request $request){

        try{

            $data = Permohonan::with(['kecamatan', 'desa'])
            ->where('no_permohonan','=', $request->no_permohonan)
            ->first();


            return response()->json(['status'=>'success','code'=>200,'message'=>'oke','data'=>$data->status_permohonan],200);
        }catch(\Throwable $th){
            return response()->json(['status'=>'error','code'=>500,'message'=>'tidak ditemukan'],500);
        }
    }
    public function cekPetugasPermohonan(Request $request){
        try{

            $data = Permohonan::with([
                'kerjakan_permohonan_verifikator.user',
                'kerjakan_permohonan_pemetaan.user',
                'kerjakan_permohonan_lapang.user',
                'kerjakan_permohonan_lapang_validasi.user',
                'kerjakan_permohonan_suel.user',
                'kerjakan_permohonan_bt.user',
                'kerjakan_permohonan_btel.user'
            ])
            // ->whereNotNull('jenis_pemetaan')
            // ->where('jenis_pemetaan', '!=', 'Tolak')
            ->where('no_permohonan', $request->no_permohonan)
            ->get()
            ->map(function($row) {
                $permohonan_terakhir = $row->updated_at;
                $verifikator = $row->kerjakan_permohonan_verifikator ?? null;
                $pemetaan = $row->kerjakan_permohonan_pemetaan ?? null;
                $lapang = $row->kerjakan_permohonan_lapang ?? null;
                $lapang_validasi = $row->kerjakan_permohonan_lapang_validasi ?? null;
                $suel = $row->kerjakan_permohonan_suel ?? null;
                $bt = $row->kerjakan_permohonan_bt ?? null;
                $btel = $row->kerjakan_permohonan_btel ?? null;
                $petugas = null;

                $last_time = [
                    'verifikator' => ($verifikator != null) ? $verifikator->created_at : '',
                    'pemetaan' => ($pemetaan != null) ? $pemetaan->created_at : '',
                    'lapang' => ($lapang != null) ? $lapang->created_at : '',
                    // 'lapang_val' => ($lapang_validasi != null) ? $lapang_validasi->created_at : '',
                    'suel' => ($suel != null) ? $suel->created_at : '',
                    // 'bt' => ($bt != null) ? $bt->created_at : '',
                    // 'btel' => ($btel != null) ? $btel->created_at : '',
                ];



                $max_value = max($last_time);
                $key_max = array_search($max_value, $last_time);

                // return $row;
                // return strpos("$row->status_pengukuran","tolak")!='';

                if ($verifikator!=null){
                    if ($permohonan_terakhir) {
                        if ($key_max == 'verifikator') {
                            if ($row->jenis_pemetaan == 'TOLAK' or $row->jenis_pemetaan == 'Tolak'){
                                $status = 'Ditolak';
                                $petugas = 'Verifikator';
                                if($row->kerjakan_permohonan_verifikator->user){
                                    $nama = '('.$row->kerjakan_permohonan_verifikator->user->name.')';
                                }else{
                                    $nama = '';
                                };
                            }else{
                                $status = 'Menunggu Diproses';
                                $petugas = 'Pemetaan';
                                $nama = '';
                            }
                        } elseif ($key_max == 'pemetaan') {
                            if($row->status_pemetaan != 'Tolak'){
                                if($row->status_pemetaan == 'Sudah Tertata'){
                                    $status = 'Menunggu Diproses';
                                    $petugas = 'SuEl';
                                    $nama = '';
                                }elseif($row->status_pemetaan == 'Ke Lapangan'){
                                    $status = 'Menunggu Diproses';
                                    $petugas = 'Lapangan';
                                    $nama = '';
                                }
                            }else{
                                $status = 'Ditolak';
                                $petugas = 'Pemetaan';
                                $nama = '('.$row->kerjakan_permohonan_pemetaan->user->name.')';
                            }
                        } elseif ($key_max == 'lapang') {
                            if(strpos("$row->status_pengukuran","tolak")!=''){
                                $status = 'Ditolak';
                                $petugas = 'Lapangan';
                                $nama = '('.$row->kerjakan_permohonan_lapang->user->name.')';
                            }elseif($row->status_pengukuran == 'sudah diukur'){
                                $status = 'Menunggu Diproses';
                                $petugas = 'SuEl';
                                $nama = '';
                            }else{
                                $status = 'Sedang Diproses';
                                $petugas = 'Lapangan';
                                $nama = '';
                            }
                        } elseif ($key_max == 'suel') {
                            $petugas = 'SuEl';
                            // return $row->status_su_el;
                            if($row->status_su_el == 'Tolak dan Kembalikan Berkas'){
                                $status = 'Dikembalikan';
                                if($row->petugas_lapang_id != null){
                                    $nama = '('.$row->kerjakan_permohonan_suel->user->name.') ke Petugas Lapangan';
                                }else{
                                    $nama = '('.$row->kerjakan_permohonan_suel->user->name.') ke Petugas  Pemetaan';
                                }
                            }elseif($row->status_su_el == 'Proses'){
                                $status = 'Sedang Dikerjakan';
                                $nama = '('.$row->kerjakan_permohonan_suel->user->name.')';
                            }elseif($row->status_su_el == 'Sudah'){
                                $status = 'Telah Selesai Dikerjakan';
                                $nama = '('.$row->kerjakan_permohonan_suel->user->name.')';
                            }
                        }
                    }
                }else{
                    $petugas = 'Verifikator';
                    $status = ($row->jenis_pemetaan != 'TOLAK') ? 'Menunggu Diproses' : 'Ditolak';
                    $nama = ($status == 'Ditolak') ? '('.$row->kerjakan_permohonan_verifikator->user->name.')' : '';
                }

                $row->sedang_diproses = $petugas;

                $data=[
                    'no_permohonan' => $row->no_permohonan,
                    'status'=>$status,
                    'petugas' => $petugas,
                    'nama' => $nama,
                ];

                // dd($petugas);
                // if($petugas == 'SuEl' && $row->status_su_el == 'Tolak dan Kembalikan Berkas' && $row->petugas_lapang_id != null){
                //     dd($row);
                // }
                // dd($row);

                return response()->json(['status'=>'success','code'=>200,'message'=>'oke','data'=>$data],200);
            });
        }catch(\Throwable $th){
            return response()->json(['status'=>'error','code'=>500,'message'=>'tidak ditemukan'],500);
        }


        return $data;
        // try{
        //     $data = Permohonan::where('no_permohonan', $request->no_permohonan)
        //     ->with('petugasLapang', 'petugasPemetaan', 'petugasSuel', 'petugasBtel', 'petugasVerifikator')
        //     ->first();

        //     return response()->json(['status'=>'success','code'=>200,'message'=>'oke','data'=>$data],200);
        // }catch(\Throwable $th){
        //     return response()->json(['status'=>'error','code'=>500,'message'=>'tidak ditemukan'],500);
        // }
    }
}
