<?php
namespace App\Http\Controllers;

use App\Models\Permohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Exports\RegisterExport;
use App\Models\KerjakanPermohonanBt;
use App\Models\KerjakanPermohonanBtel;
use App\Models\KerjakanPermohonanLapang;
use App\Models\KerjakanPermohonanPemetaan;
use App\Models\KerjakanPermohonanVerifikator;
use Excel;

class PermohonanDitolakController extends Controller
{
  private $menuActive = "registrasi-ditolak";
  private $submnActive = "";

  public function index(Request $request) {
    $this->data['menuActive'] = $this->menuActive;
    $this->data['submnActive'] = $this->submnActive;
    if ($request->ajax()) {
        $data = Permohonan::with('kecamatan','desa')
        ->when(Auth::getUser()->level_user == 1,function($q) { # Admin
            $q->where('jenis_pemetaan','Tolak');
        })
        ->when(Auth::getUser()->level_user == 11,function($q) { # Verifikator
            $q->where('jenis_pemetaan','Tolak')
            ->where('status_permohonan','TOLAK')
            ->orWhere('status_bt_el','Tolak dan Kembalikan Berkas')
            ->whereNotNull('petugas_verifikator')
            ->where('petugas_verifikator_id', Auth::getUser()->id);
            // ->orWhere(function($q){
            //     $q->where('jenis_pemetaan', '!=', 'Pemetaan Langsung');
            // });
        })
        ->when(Auth::getUser()->level_user == 3,function($q) { # Pemetaan
            $q->where('status_pemetaan','Tolak')
            ->whereNotNull('petugas_pemetaan')
            ->where('petugas_pemetaan_id', Auth::getUser()->id);
        })
        ->when(Auth::getUser()->level_user == 2,function($q) { # Lapangan
            $q->where('status_pengukuran','Tolak')
            ->whereNotNull('petugas_pengukuran')
            ->where('petugas_pengukuran_id', Auth::getUser()->id);
        })
        ->when(Auth::getUser()->level_user == 4,function($q) { # Suel
            $q->where('status_suel','Tolak')
            ->whereNotNull('petugas_suel')
            ->where('petugas_suel_id', Auth::getUser()->id);
        })
        ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('formatDate', function($row) {
                return date('d-m-Y', strtotime($row->tgl_input));
            })
            ->addColumn('action', function($row){
                $petugas = '';
                if(Auth::getUser()->level_user === 11){
                    $petugas = 'verifikator';
                }elseif(Auth::getUser()->level_user === 3){
                    $petugas = 'pemetaan';
                }elseif(Auth::getUser()->level_user === 2){
                    $petugas = 'lapangan';
                }elseif(Auth::getUser()->level_user === 4){
                    $petugas = 'suel';
                }elseif(Auth::getUser()->level_user === 1){
                    $petugas = 'admin';
                }
                if(Auth::getUser()->level_user == 1) {
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`verifikator`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                } else {
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`verifikator`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`verifikator`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                }
                return $btn;
            })
            ->addColumn('catatan', function($row){
                return '<a href="javascript:void(0);" onclick="showCatatan('.$row->id_permohonan.')">Lihat</a>';
            })
            ->rawColumns(['formatDate','action','catatan'])
            ->make(true);
    }
    // return Auth::getUser()->level_user;
    return view('permohonan-ditolak.main')->with('data', $this->data);
  }

  public function export(Request $request){
    $dates = null;
    if(isset($request->dates) && $request->dates != ''){
        $dates = explode(' - ',$request->dates);
        foreach($dates as &$date){
            $date = date('Y-m-d',strtotime($date));
        }
    }
    $level_user = Auth::getUser()->level_user;
    $data = Permohonan::select('permohonan.*','mst_kecamatans.nama as kecamatan_nama','mst_desas.nama as desa_nama')
    ->join('mst_kecamatans','permohonan.kecamatan_id','mst_kecamatans.id')
    ->join('mst_desas','permohonan.desa_id','mst_desas.id')
    ->when($level_user == 11,function($q) { #verifikator
        $q->where('jenis_pemetaan','Tolak');
    })
    ->when($level_user == 3,function($q){ #pemetaan
        $q->where('status_pemetaan','Kelapangan');
    })
    ->when($level_user == 2, function ($q) { #lapangan
        $q->where('petugas_pemetaan_id',Auth::getUser()->level_user);
    })
    ->when($level_user == 4,function($q){#suel
        $q->where('status_pengukuran','Tolak');
    })

    // filter
    ->when(isset($request->dates) && $request->dates != '' ,function($q) use ($dates){
        $q->whereBetween('tgl_input',$dates);
    })
    ->orderBy('tgl_input','desc')
    ->orderBy('id_permohonan','desc')
    ->orderBy('no_permohonan','desc')
    ->get();
    if(!$data){
        return 'data kosong';
    }
    $namaFile = "Data_Registrasi_ditolak_$dates[0]".'_'."$dates[1].xlsx";
    return Excel::download(new RegisterExport($data),$namaFile);
  }
}
