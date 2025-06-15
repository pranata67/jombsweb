<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Keperluan;
use App\Models\KerjakanPermohonanBt;
use App\Models\KerjakanPermohonanBtel;
use App\Models\KerjakanPermohonanLapang;
use App\Models\KerjakanPermohonanPemetaan;
use App\Models\KerjakanPermohonanSuel;
use App\Models\Permohonan;
use App\Models\PermohonanImages;
use App\Models\User;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PermohonanDikerjakanController extends Controller
{
    private $data;
    private $menuActive = "registrasi-dikerjakan";
    private $submnActive = "";

    public function index(Request $request) {
        // $data = Permohonan::with([
        //     'kecamatan',
        //     'desa',
        //     'kerjakan_permohonan_verifikator.user',
        //     'kerjakan_permohonan_pemetaan.user',
        //     'kerjakan_permohonan_lapang.user',
        //     'kerjakan_permohonan_lapang_validasi.user',
        //     'kerjakan_permohonan_suel.user',
        //     'kerjakan_permohonan_bt.user',
        //     'kerjakan_permohonan_btel.user'
        // ])->first();
        // return $data;
        $this->data['menuActive'] = $this->menuActive;
        $this->data['submnActive'] = $this->submnActive;
        $this->data['kecamatan'] = Kecamatan::where('kabupaten_id', '3516')->get();
        if($request->ajax()) {
            $dateRange = [];
            if(isset($request->dates) && $request->dates != ''){
                $dates = explode(' - ',$request->dates);
                foreach($dates as &$date){
                    $dateRange[] = date('Y-m-d',strtotime($date));
                }
            }
            // $data = Permohonan::with([
            //     'kecamatan',
            //     'desa',
            // ])->whereNotNull('jenis_pemetaan')->where('jenis_pemetaan', '!=', 'Tolak')
            // ->orderBy('tgl_input','desc')
            // ->orderBy('id_permohonan','desc')
            // ->orderBy('no_permohonan','desc')->get();
            $level_user = Auth::user()->level_user;
            // $data = Permohonan::with([
            //     'kecamatan',
            //     'desa',
            //     'kerjakan_permohonan_verifikator.user',
            //     'kerjakan_permohonan_pemetaan.user',
            //     'kerjakan_permohonan_lapang.user',
            //     'kerjakan_permohonan_lapang_validasi.user',
            //     'kerjakan_permohonan_suel.user',
            //     'kerjakan_permohonan_bt.user',
            //     'kerjakan_permohonan_btel.user'
            // ])
            // ->whereNotNull('jenis_pemetaan')->where('jenis_pemetaan', '!=', 'Tolak')
            // ->when($level_user == 3,function($q){ #pemetaan
            //     $q->where('jenis_pemetaan','!=','Tolak')
            //     ->whereNotNull('petugas_pemetaan')
            //     ->whereNotNull('petugas_pemetaan_id')
            //     ->where('petugas_pemetaan_id',Auth::getUser()->id);
            // })
            // ->when($level_user == 2, function ($q) { #lapangan
            //     $q->where('status_pemetaan','Ke Lapangan')
            //     ->where('petugas_lapang_id',Auth::getUser()->id);
            // })
            // ->when($level_user == 4,function($q){#suel
            //     $q->where('status_su_el','!=','Tolak dan Kembalikan Berkas')
            //         ->orWhere('status_su_el','=',NULL)
            //         ->where('petugas_suel_id',Auth::getUser()->id);
            // })
            // ->when($level_user == 5,function($q){#btel
            //     $q->where('jenis_pemetaan','Pemetaan Langsung')
            //     ;
            // })
            // ->when($level_user == 11,function($q){# Verifikator
            //     $q->where('jenis_pemetaan','Pemetaan Langsung')
            //     ;
            // })
            // ->when($level_user == 12,function($q){#bt
            //     $q->where('jenis_pemetaan','Pemetaan Langsung')
            //     ;
            // })
            // ->when(isset($request->dates) && $request->dates != '' ,function($q) use ($dateRange){
            //     $q->whereBetween('tgl_input',$dateRange);
            // })
            // ->when(isset($request->status) && $request->status != '' ,function($q) use ($request){
            //     $q->where('status_permohonan',$request->status);
            // })
            // ->when(isset($request->jenis_hak) && $request->jenis_hak != '' ,function($q) use ($request){
            //     $q->where('jenis_hak',$request->jenis_hak);
            // })
            // ->when(isset($request->kecamatan_id) && $request->kecamatan_id != '' ,function($q) use ($request){
            //     $q->where('kecamatan_id',$request->kecamatan_id);
            // })
            // ->when(isset($request->desa_id) && $request->desa_id != '' ,function($q) use ($request){
            //     $q->where('desa_id',$request->desa_id);
            // })
            // ->when(isset($request->proses_pengukuran_lapang) && $request->proses_pengukuran_lapang != '' ,function($q) use ($request){
            //     $q->where('status_pengukuran',$request->proses_pengukuran_lapang);
            // })
            // ->when(isset($request->status_su_el) && $request->status_su_el != '' ,function($q) use ($request){
            //     $q->where('status_su_el',$request->status_su_el);
            // })
            // ->when(isset($request->status_bt_el) && $request->status_bt_el != '' ,function($q) use ($request){
            //     $q->where('status_bt_el',$request->status_bt_el);
            // })
            // ->when(isset($request->upload_bt) && $request->upload_bt != '' ,function($q) use ($request){
            //     $q->where('upload_bt',$request->upload_bt);
            // })
            // ->orderBy('tgl_input','desc')
            // ->orderBy('id_permohonan','desc')
            // ->orderBy('no_permohonan','desc')->get();

            $data = Permohonan::selectRaw('
                permohonan.id_permohonan,
                permohonan.no_permohonan,
                permohonan.nama_pemohon,
                permohonan.nama_kuasa,
                permohonan.tgl_input,
                permohonan.jenis_pemetaan,
                permohonan.status_permohonan,
                permohonan.status_pemetaan,
                permohonan.no_sertifikat,
                permohonan.status_su_el,
                permohonan.status_bt_el,
                permohonan.upload_bt,
                permohonan.jenis_hak,
                permohonan.kecamatan_id,
                permohonan.desa_id,
                mst_kecamatans.nama AS nama_kecamatan,
                mst_desas.nama AS nama_desa
            ')
            ->leftJoin('mst_kecamatans', 'permohonan.kecamatan_id', '=', 'mst_kecamatans.id')
            ->leftJoin('mst_desas', 'permohonan.desa_id', '=', 'mst_desas.id')
            ->with([
                'kerjakan_permohonan_verifikator.user:id,name',
                'kerjakan_permohonan_pemetaan.user:id,name',
                'kerjakan_permohonan_lapang.user:id,name',
                'kerjakan_permohonan_lapang_validasi.user:id,name',
                'kerjakan_permohonan_suel.user:id,name',
                'kerjakan_permohonan_bt.user:id,name',
                'kerjakan_permohonan_btel.user:id,name'
            ])
            ->whereNotNull('jenis_pemetaan')->where('jenis_pemetaan', '!=', 'Tolak')
            // ->when($level_user == 3, function($q) { // pemetaan
            //     $q->where('jenis_pemetaan', '!=', 'Tolak')
            //       ->whereNotNull('petugas_pemetaan')
            //       ->whereNotNull('petugas_pemetaan_id')
            //       ->where('petugas_pemetaan_id', Auth::id());
            // })
            // ->when($level_user == 2, function ($q) { // lapangan
            //     $q->where('status_pemetaan', 'Ke Lapangan')
            //       ->where('petugas_lapang_id', Auth::id());
            // })
            // ->when($level_user == 4, function($q) { // suel
            //     $q->where('status_su_el', '!=', 'Tolak dan Kembalikan Berkas')
            //       ->orWhereNull('status_su_el')
            //       ->where('petugas_suel_id', Auth::id());
            // })
            // ->when($level_user == 5, function($q) { // btel
            //     $q->where('jenis_pemetaan', 'Pemetaan Langsung');
            // })
            // ->when($level_user == 11, function($q) { // verifikator
            //     $q->where('jenis_pemetaan', 'Pemetaan Langsung');
            // })
            // ->when($level_user == 12, function($q) { // bt
            //     $q->where('jenis_pemetaan', 'Pemetaan Langsung');
            // })
            ->when(isset($request->dates) && $request->dates != '', function($q) use ($dateRange) {
                $q->whereBetween('tgl_input', $dateRange);
            })
            ->when(isset($request->status) && $request->status != '', function($q) use ($request) {
                $q->where('status_permohonan', $request->status);
            })
            ->when(isset($request->jenis_hak) && $request->jenis_hak != '', function($q) use ($request) {
                $q->where('jenis_hak', $request->jenis_hak);
            })
            ->when($level_user == 4,function($q){#suel
                $q->where('status_su_el','!=','Tolak dan Kembalikan Berkas')
                    ->orWhere('status_su_el','=',NULL)
                    ->where('petugas_suel_id',Auth::getUser()->id);
            })
            ->when(isset($request->kecamatan_id) && $request->kecamatan_id != '', function($q) use ($request) {
                $q->where('kecamatan_id', $request->kecamatan_id);
            })
            ->when(isset($request->desa_id) && $request->desa_id != '', function($q) use ($request) {
                $q->where('desa_id', $request->desa_id);
            })
            ->when(isset($request->proses_pengukuran_lapang) && $request->proses_pengukuran_lapang != '', function($q) use ($request) {
                $q->where('status_pengukuran', $request->proses_pengukuran_lapang);
            })
            ->when(isset($request->status_su_el) && $request->status_su_el != '', function($q) use ($request) {
                $q->where('status_su_el', $request->status_su_el);
            })
            ->when(isset($request->status_bt_el) && $request->status_bt_el != '', function($q) use ($request) {
                $q->where('status_bt_el', $request->status_bt_el);
            })
            ->when(isset($request->upload_bt) && $request->upload_bt != '', function($q) use ($request) {
                $q->where('upload_bt', $request->upload_bt);
            })
            ->orderBy('tgl_input', 'desc')
            ->orderBy('id_permohonan', 'desc')
            ->orderBy('no_permohonan', 'desc')
            ->get();


            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('formatDate', function($row) {
                return date('d-m-Y', strtotime($row->tgl_input));
            })
            ->addColumn('catatan', function($row){
                return '<a href="javascript:void(0);" onclick="showCatatan('.$row->id_permohonan.')">Lihat</a>';
            })
            ->addColumn('sedang_diproses', function($row){
                $permohonan_terakhir = $row->updated_at;
                $verifikator = $row->kerjakan_permohonan_verifikator??null;
                $pemetaan = $row->kerjakan_permohonan_pemetaan??null;
                $lapang = $row->kerjakan_permohonan_lapang??null;
                $lapang_validasi = $row->kerjakan_permohonan_lapang_validasi??null;
                $suel = $row->kerjakan_permohonan_suel??null;
                $bt = $row->kerjakan_permohonan_bt??null;
                $btel = $row->kerjakan_permohonan_btel??null;
                $petugas = null;
                if($permohonan_terakhir){
                    if ($verifikator && $permohonan_terakhir == $verifikator->created_at) {
                        $petugas = 'Verifikator';
                    } elseif ($lapang && $permohonan_terakhir == $lapang->created_at) {
                        $petugas = 'Lapangan';
                    } elseif ($lapang_validasi && $permohonan_terakhir == $lapang_validasi->created_at) {
                        $petugas = 'Lapangan';
                    } elseif ($suel && $permohonan_terakhir == $suel->created_at) {
                        $petugas = 'SuEl';
                    }
                    // elseif ($bt && $permohonan_terakhir == $bt->created_at) {
                    //     $petugas = 'BT';
                    // } elseif ($btel && $permohonan_terakhir == $btel->created_at) {
                    //     $petugas = 'BTEL';
                    // }
                }
                return $petugas;
            })
            ->addColumn('action', function ($row) {
                if (Auth::getUser()->level_user == '1' || Auth::getUser()->level_user == '10') { #admin
                    $btn = '<div class="dropdown">';
                    $btn .= '<button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton'.$row->id_permohonan.'" data-bs-toggle="dropdown" aria-expanded="false">';
                    $btn .= '<i class="fa fa-fw fa-bars"></i>';
                    $btn .= '</button>';
                    $btn .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$row->id_permohonan.'" style="font-size: 12px;">';
                    $btn .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="pindahkanBerkas('.$row->id_permohonan.')">Pindahkan</a></li>';
                    // $btn .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="editForm('.$row->id_permohonan.',1)">Detail</a></li>';
                    // $btn .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="editForm('.$row->id_permohonan.')">Edit</a></li>';
                    // $btn .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteRow('.$row->id_permohonan.')">Hapus</a></li>';
                    $btn .= '</ul>';
                    $btn .= '</div>';
                }
                elseif(Auth::getUser()->level_user == '2'){#lapangan
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`lapang`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`lapang`,null,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="detail('.$row->id_permohonan.',1)" style="margin-right: 5px;" class="btn btn-sm btn-primary "><i class="fa fa-eye"></i></a>';
                }elseif(Auth::getUser()->level_user == '3'){#pemetaan
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`pemetaan`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`pemetaan`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="detail('.$row->id_permohonan.',1)" style="margin-right: 5px;" class="btn btn-sm btn-primary "><i class="fa fa-eye"></i></a>';
                }elseif(Auth::getUser()->level_user == '4'){#suel
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`suel`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`suel`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="detail('.$row->id_permohonan.',1)" style="margin-right: 5px;" class="btn btn-sm btn-primary "><i class="fa fa-eye"></i></a>';
                }elseif(Auth::getUser()->level_user == '5'){#btel
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`btel`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`btel`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="detail('.$row->id_permohonan.',1)" style="margin-right: 5px;" class="btn btn-sm btn-primary "><i class="fa fa-eye"></i></a>';
                }elseif(Auth::getUser()->level_user == '11'){#verifikator
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`verifikator`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`verifikator`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="detail('.$row->id_permohonan.',1)" style="margin-right: 5px;" class="btn btn-sm btn-primary "><i class="fa fa-eye"></i></a>';
                }elseif(Auth::getUser()->level_user == '12'){#bt
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`bt`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`bt`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="detail('.$row->id_permohonan.',1)" style="margin-right: 5px;" class="btn btn-sm btn-primary "><i class="fa fa-eye"></i></a>';
                }else{
                    $btn = '';
                }
                return $btn;
            })
            ->rawColumns(['formatDate','catatan','action'])
            ->make(true);
        }
        // return $this->data;
        return view('permohonan-dikerjakan.main')->with('data', $this->data);
    }

    public function form(Request $request) {
        if($request->id){
            $data['data'] = Permohonan::with('kecamatan','desa')->where('id_permohonan',$request->id)->first();
            $data['data_gambar'] = PermohonanImages::where('permohonan_id',$request->id)->get();
            $data['no_permohonan'] = [];
        }else{
            $data['data'] = [];
            $data['data_gambar'] = [];
            $data['no_permohonan'] = Permohonan::generateNoAntrian();
        }
        if (!empty($request->id)) {
            $data['kecamatan']          = Kecamatan::where('kabupaten_id', '3516')->get();
            $data['desa']               = Desa::where('kecamatan_id', (int)$data['data']->kecamatan_id)->get();
        } else {
            $data['kecamatan'] = Kecamatan::where('kabupaten_id', '3516')->get();
            $data['desa'] = Desa::where('kecamatan_id', $request->id)->get();
        }
        // return $data;
        $data['keperluan'] = Keperluan::all();
        $data['petugas_lapangan'] = User::where('level_user',2)->get();
        $data['petugas_pemetaan'] = User::where('level_user',3)->get();
        $data['petugas_su_el'] = User::where('level_user',4)->get();
        $data['petugas_bt_el'] = User::where('level_user',5)->get();
        if(isset($request->detail) && $request->detail){
            $data['detail'] = true;
        }else{
            $data['detail'] = false;
        }

        $content = view('permohonan-dikerjakan.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function getUpdateTerakhir() {
        $permohonan = Permohonan::where('id_permohonan', 1967)->get();
        $pemetaan = KerjakanPermohonanPemetaan::where('permohonan_id', 1967)->get();
        $lapang = KerjakanPermohonanLapang::where('permohonan_id', 1967)->get();
        $suel = KerjakanPermohonanSuel::where('permohonan_id', 1967)->get();
        $bt = KerjakanPermohonanBt::where('permohonan_id', 1967)->get();
        $btel = KerjakanPermohonanBtel::where('permohonan_id', 1967)->get();
        // return date('Y-m-d H:i:s', strtotime($data['permohonan']->updated_at));
    }

    public function detail(Request $request) {
        // return $request->all();
        try {
            if($request->id){
                $data['data'] = Permohonan::with('kecamatan','desa')->where('id_permohonan',$request->id)->first();
                $data['data_gambar'] = PermohonanImages::where('permohonan_id',$request->id)->get();
                $data['no_permohonan'] = [];
            }else{
                $data['data'] = [];
                $data['data_gambar'] = [];
                $data['no_permohonan'] = Permohonan::generateNoAntrian();
            }
            if (!empty($request->id)) {
                $data['kecamatan']          = Kecamatan::where('kabupaten_id', '3516')->get();
                $data['desa']               = Desa::where('kecamatan_id', (int)$data['data']->kecamatan_id)->get();
            } else {
                $data['kecamatan'] = Kecamatan::where('kabupaten_id', '3516')->get();
                $data['desa'] = Desa::where('kecamatan_id', $request->id)->get();
            }
            // return $data;
            $data['keperluan'] = Keperluan::all();
            $data['petugas_lapangan'] = User::where('level_user',2)->get();
            $data['petugas_pemetaan'] = User::where('level_user',3)->get();
            $data['petugas_su_el'] = User::where('level_user',4)->get();
            $data['petugas_bt_el'] = User::where('level_user',5)->get();
            if(isset($request->detail) && $request->detail){
                $data['detail'] = true;
            }else{
                $data['detail'] = false;
            }
            // return $data;

            $content = view('permohonan-dikerjakan.detail', $data)->render();
            return ['status' => 'success', 'content' => $content];
        } catch(\Exception $e) {
            return response()->json(['message' => 'error', 'code' => 201, 'message' => $e->getMessage()]);
        }
    }
}
