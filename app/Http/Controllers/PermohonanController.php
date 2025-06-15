<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Keperluan;
use App\Models\Permohonan;
use App\Models\KerjakanPermohonanLapang;
use App\Models\PetugasBTEL;
use App\Models\PetugasSUEL;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PetugasLapangan;
use App\Models\PetugasPemetaan;
use App\Models\PermohonanImages;
use App\Exports\RegisterExport;
use App\Models\KerjakanPermohonanBt;
use App\Models\KerjakanPermohonanBtel;
use App\Models\KerjakanPermohonanPemetaan;
use App\Models\KerjakanPermohonanVerifikator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Excel,Storage,Log;
use Illuminate\Support\Facades\Validator;

class PermohonanController extends Controller
{
    private $data;
    private $menuActive = "registrasi";
    private $submnActive = "";

    public function index(Request $request) {
        // return Auth::getUser()->id;

        $data = Permohonan::where('status_pengukuran', 'sudah diukur')
        ->orWhere('status_pengukuran', 'SUDAH DIUKUR DAN SUDAH TERTATA')
        ->whereNotNull('petugas_pengukuran')
        ->whereNotNull('petugas_lapang_id')
        // ->whereNull('status_su_el')
        ->get();
        // return count($data);
        $this->data['menuActive'] = $this->menuActive;
        $this->data['submnActive'] = $this->submnActive;
        $this->data['kecamatan'] = Kecamatan::where('kabupaten_id', '3516')->get();
        $no = null;
        if(isset($request->no)){
            $no = $request->no;
        }
        $this->data['no'] = $no;
        if ($request->ajax()) {
            $dateRange = [];
            if(isset($request->dates) && $request->dates != ''){
                $dates = explode(' - ',$request->dates);
                foreach($dates as &$date){
                    $dateRange[] = date('Y-m-d',strtotime($date));
                }
            }
            $level_user = Auth::getUser()->level_user;
            $query = Permohonan::with('kecamatan','desa')
            ->when(!empty($no), function ($q) use ($no) {
                $q->where('no_permohonan',$no);
            })
            ->when($level_user == 1,function($q){ #admin
                $q->where('jenis_pemetaan',null);
            })
            ->when($level_user == 11,function($q){#verifikator
                $q->whereNull('jenis_pemetaan')
                ->whereNull('petugas_verifikator')
                // ->whereNull('petugas_verifikator_id')
                ->orWhere(function($q){
                    $q->orWhereNull('status_pemetaan')
                    ->whereNotIn('jenis_pemetaan', ['Tolak', 'Pemetaan Langsung']);
                    // ->where('jenis_pemetaan','!=','Tolak')
                    // ->where('jenis_pemetaan','!=','Pemetaan Langsung');
                });
            })
            ->when($level_user == 3,function($q){ #pemetaan
                // $q->where('status_pemetaan','Kelapangan');
                $q->where('jenis_pemetaan','Pemetaan Langsung')
                ->whereNotNull('petugas_verifikator')
                ->whereNotNull('petugas_verifikator_id')
                ->whereNull('status_pemetaan');
                // ->orWhere(function($q){
                //     $q->orWhere('status_pemetaan','=','')
                //     ->orWhereNull('status_pemetaan');
                // })
                ;
            })
            ->when($level_user == 2, function ($q) { #lapangan
                // $q->where('petugas_pemetaan_id',Auth::getUser()->level_user);
                $q->where('status_pemetaan','Ke Lapangan')
                ->whereNotNull('petugas_pemetaan')
                ->whereNotNull('petugas_pemetaan_id')
                // ->whereNull('status_pengukuran')
                // ->orWhere('validated','0')
                ->where(function ($query) {
                    $query->whereNull('status_pengukuran')
                        ->orWhere(function ($subQuery) {
                            $subQuery->whereNotNull('status_pengukuran')
                                    //  ->where('validated', '0')
                                     ->where('status_pengukuran', '!=', 'tolak dan kembalikan berkas')
                                    //  ->where('status_pengukuran', '!=', 'sudah diukur');
                                    ->whereNotIn('status_pengukuran', ['sudah diukur','SUDAH DIUKUR DAN SUDAH TERTATA']);
                                    //  ->orWhere('status_pengukuran', '!=', 'SUDAH DIUKUR DAN SUDAH TERTATA');
                        });
                })
                ->where('petugas_lapang_id', Auth::getUser()->id);
            })
            ->when($level_user == 4,function($q){#suel
                $q->where(function($q2){
                    $q2->where('status_pengukuran', 'sudah diukur')
                       ->orWhere('status_pemetaan','Sudah Tertata');
                });
            })

            // bt dan btel start
            ->when($level_user == 5,function($q){#btel
                // $q->where('jenis_pemetaan','Pemetaan Langsung')
                // ->whereNotIn('status_bt_el', ['Tolak dan Kembalikan Berkas','Sudah'])
                // ->orWhere(function($query) {
                //     $query->whereNotNull('petugas_verifikator')
                //         ->whereNotNull('petugas_verifikator_id');
                // })
                // ;
                //     ->whereNull('status_bt_el');
                $q->where('jenis_pemetaan','Pemetaan Langsung')
                    // ->whereNotIn('status_bt_el', ['Tolak dan Kembalikan Berkas','Sudah'])
                    ->whereNull('status_bt_el')
                    ->whereNotNull('petugas_verifikator')
                    ->whereNotNull('petugas_verifikator_id')
                    ;
                // ->whereNotNull('petugas_verifikator')
                // ->whereNotNull('petugas_verifikator_id')
                // ;
            })
            ->when($level_user == 12,function($q){#bt
                $q->where('jenis_pemetaan','Pemetaan Langsung')
                ->where(function($query) {
                    $query->where('upload_bt', null)
                            ->whereNotNull('petugas_verifikator')
                            ->whereNotNull('petugas_verifikator_id');
                })
                // ->whereNotIn('upload_bt', ['Tolak dan Kembalikan Berkas','Sudah'])
                // ->whereNotNull('petugas_verifikator')
                // ->whereNotNull('petugas_verifikator_id')
                ;
            })
            // bt dan btel end
            // untuk filter start
            ->when(isset($request->dates) && $request->dates != '' ,function($q) use ($dateRange){
                $q->whereBetween('tgl_input',$dateRange);
            })
            ->when(isset($request->status) && $request->status != '' ,function($q) use ($request){
                $q->where('status_permohonan',$request->status);
            })
            ->when(isset($request->jenis_hak) && $request->jenis_hak != '' ,function($q) use ($request){
                $q->where('jenis_hak',$request->jenis_hak);
            })
            ->when(isset($request->kecamatan_id) && $request->kecamatan_id != '' ,function($q) use ($request){
                $q->where('kecamatan_id',$request->kecamatan_id);
            })
            ->when(isset($request->desa_id) && $request->desa_id != '' ,function($q) use ($request){
                $q->where('desa_id',$request->desa_id);
            })
            ->when(isset($request->proses_pengukuran_lapang) && $request->proses_pengukuran_lapang != '' ,function($q) use ($request){
                $q->where('status_pengukuran',$request->proses_pengukuran_lapang);
            })
            // ->when(isset($request->status_su_el) && $request->status_su_el != '' ,function($q) use ($request){
            //     $q->where('status_su_el',$request->status_su_el);
            // })
            ->when(isset($request->status_su_el) && $request->status_su_el != '', function($q) use ($request) {
                if ($request->status_su_el == 'null') {
                    $q->whereNull('status_su_el');
                } else {
                    $q->where('status_su_el', $request->status_su_el);
                }
                // dd($q->toSql());
            })
            ->when(isset($request->status_bt_el) && $request->status_bt_el != '' ,function($q) use ($request){
                $q->where('status_bt_el',$request->status_bt_el);
            })
            ->when(isset($request->upload_bt) && $request->upload_bt != '' ,function($q) use ($request){
                $q->where('upload_bt',$request->upload_bt);
            })
            ->orderBy('tgl_input','desc')
            ->orderBy('id_permohonan','desc')
            ->orderBy('no_permohonan','desc');
            // ->get();

            // $statusSuEl = $request->status_su_el;
            // if($statusSuEl == 'null') {
            //     // return 'ajhsjhadb';
            //     $query->where(function ($q) {
            //         $q->whereNull('status_su_el');
            //     });
            // } elseif($statusSuEl == 'Sudah') {
            //     // return $statusSuEl;
            //     $query->where(function ($q) {
            //         $q->where('status_su_el', 'Sudah');
            //     });
            // } elseif($statusSuEl == 'Proses') {
            //     $query->where(function ($q) {
            //         $q->where('status_su_el', 'Proses');
            //     });
            // } elseif($statusSuEl == 'Tolak dan Kembalikan Berkas') {
            //     $query->where(function ($q) {
            //         $q->where('status_su_el', 'Tolak dan Kembalikan Berkas');
            //     });
            // }

            // dd($query);
            $data = $query->get();

            // return $data;
            // dd($request->status_su_el);
            $table = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                if (Auth::getUser()->level_user == '1' || Auth::getUser()->level_user == '10') { #admin
                    $btn = '<div class="dropdown">';
                    $btn .= '<button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton'.$row->id_permohonan.'" data-bs-toggle="dropdown" aria-expanded="false">';
                    $btn .= '<i class="fa fa-fw fa-bars"></i>';
                    $btn .= '</button>';
                    $btn .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$row->id_permohonan.'" style="font-size: 12px;">';
                    // $btn .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="pindahkanBerkas('.$row->id_permohonan.')">Pindahkan</a></li>';
                    $btn .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="editForm('.$row->id_permohonan.',1)">Detail</a></li>';
                    $btn .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="editForm('.$row->id_permohonan.')">Edit</a></li>';
                    $btn .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteRow('.$row->id_permohonan.')">Hapus</a></li>';
                    $btn .= '</ul>';
                    $btn .= '</div>';
                }
                elseif(Auth::getUser()->level_user == '2'){#lapangan
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`lapang`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    // $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`lapang`,null,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="editForm('.$row->id_permohonan.',1)" style="margin-right: 5px;" class="btn btn-sm btn-primary "><i class="fa fa-eye"></i></a>';
                }elseif(Auth::getUser()->level_user == '3'){#pemetaan
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`pemetaan`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    // $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`pemetaan`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="editForm('.$row->id_permohonan.',1)" style="margin-right: 5px;" class="btn btn-sm btn-primary "><i class="fa fa-eye"></i></a>';
                }elseif(Auth::getUser()->level_user == '4'){#suel
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`suel`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`suel`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="editForm('.$row->id_permohonan.',1)" style="margin-right: 5px;" class="btn btn-sm btn-primary "><i class="fa fa-eye"></i></a>';
                }elseif(Auth::getUser()->level_user == '5'){#btel
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`btel`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`btel`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="editForm('.$row->id_permohonan.',1)" style="margin-right: 5px;" class="btn btn-sm btn-primary "><i class="fa fa-eye"></i></a>';
                }elseif(Auth::getUser()->level_user == '11'){#verifikator
                    // if($row->jenis_pemetaan === 'Pemetaan Langsung'){
                    //     $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`verifikator`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    //     $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`verifikator`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                    // }else{
                    // }
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`verifikator`)" style="margin-right: 5px;" class="btn btn-sm btn-warning ">Kerjakan</a>';
                    $btn .= '<a href="javascript:void(0)" onclick="editForm('.$row->id_permohonan.',1)" style="margin-right: 5px;" class="btn btn-sm btn-primary "><i class="fa fa-eye"></i></a>';
                }elseif(Auth::getUser()->level_user == '12'){#bt
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`bt`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`bt`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="editForm('.$row->id_permohonan.',1)" style="margin-right: 5px;" class="btn btn-sm btn-primary "><i class="fa fa-eye"></i></a>';
                }else{
                    $btn = '';
                }

                return $btn;
            })
            ->addColumn('formatDate', function($row) {
                return date('d-m-Y', strtotime($row->tgl_input));
            })
            ->addColumn('catatan', function($row) {
                $btn = '';
                $btn .= '<a href="javascript:void(0)" onclick="showCatatan('.$row->id_permohonan.')" style="margin-right: 5px;" class="">Lihat</a>';
                $btn .='</div></div>';
                return $btn;
            });
            $rawColumns = ['action','catatan'];
            if(Auth::getUser()->level_user == '2'){ #lapangan
                $rawColumns[] = 'proses_lapang';
                $table = $table->addColumn('proses_lapang',function($row) {
                    $warna='';
                    if($row->status_pengukuran == 'sudah diukur'){
                        $warna = 'btn-info';
                    }elseif($row->status_pengukuran == 'sudah kelapang tidak dapat diukur'){
                        $warna = 'btn-danger';
                    }elseif($row->status_pengukuran == 'penjadwalan ukur'){
                        $warna = 'btn-secondary';
                    }else{
                        $warna = '';
                    }
                    $container = '<button type="button" class="btn '.$warna.' btn-sm">'.ucwords($row->status_pengukuran).'</button>';
                    return $container;
                });
                $rawColumns[] = 'surat';
                $table = $table->addColumn('surat',function($row) {
                    if($row->petugas_pengukuran){
                        return '<a href="'.route('surat-lapangan',['id_permohonan'=>$row->id_permohonan]).'" target="__blank">lihat surat</a>';
                    }else{
                        return '<a href="'.route('surat-lapangan',['id_permohonan'=>$row->id_permohonan]).'" target="__blank">lihat surat</a>';
                    }
                });
            }elseif(Auth::getUser()->level_user == '3'){ #pemetaan
                $rawColumns[] = 'status_pemetaan_';
                $table = $table->addColumn('status_pemetaan_',function($row) {
                    $warna='';
                    if($row->status_pemetaan == 'Sudah Tertata'){
                        $warna = 'btn-info';
                    }elseif($row->status_pemetaan == 'Konfirmasi'){
                        $warna = 'btn-danger';
                    }elseif($row->status_pemetaan == 'Validasi Biasa'){
                        $warna = 'btn-secondary';
                    }else{
                        $warna = '';
                    }
                    $container = '<button type="button" class="btn '.$warna.' btn-sm">'.$row->status_pemetaan.'</button>';
                    return $container;
                });
            }elseif(Auth::getUser()->level_user == '4'){ #suel
                $rawColumns[] = 'status_su_el_';
                $table = $table->addColumn('status_su_el_',function($row) {
                    $warna='';
                    if($row->status_su_el == 'Sudah'){
                        $warna = 'btn-info';
                    }elseif($row->status_su_el == 'Proses'){
                        $warna = 'btn-danger';
                    }else{
                        $warna = '';
                    }
                    $container = '<button type="button" class="btn '.$warna.' btn-sm">'.$row->status_su_el.'</button>';
                    return $container;
                });
            }elseif(Auth::getUser()->level_user == '5'){ #btel
                $rawColumns[] = 'status_bt_el_';
                $table = $table->addColumn('status_bt_el_',function($row) {
                    $warna='';
                    if($row->status_bt_el == 'Sudah'){
                        $warna = 'btn-info';
                    }elseif($row->status_bt_el == 'Proses'){
                        $warna = 'btn-danger';
                    }else{
                        $warna = '';
                    }
                    $container = '<button type="button" class="btn '.$warna.' btn-sm">'.$row->status_bt_el.'</button>';
                    return $container;
                });
            }elseif(Auth::getUser()->level_user == '11'){ #verifikator
                $rawColumns[] = 'jenis_pemetaan';
                $table = $table->addColumn('jenis_pemetaan',function($row) {
                    $warna='';
                    if($row->jenis_pemetaan == 'Pemetaan Langsung'){
                        $warna = 'btn-info';
                    }elseif($row->jenis_pemetaan == 'Tolak'){
                        $warna = 'btn-danger';
                    }else{
                        $warna = '';
                    }
                    $container = '<button type="button" class="btn '.$warna.' btn-sm">'.$row->jenis_pemetaan.'</button>';
                    return $container;
                });
            }elseif(Auth::getUser()->level_user == '12'){ #bt
                $rawColumns[] = 'upload_bt_';
                $table = $table->addColumn('upload_bt_',function($row) {
                    $warna='';
                    if($row->upload_bt == 'Sudah'){
                        $warna = 'btn-info';
                    }elseif($row->upload_bt == 'Proses'){
                        $warna = 'btn-danger';
                    }else{
                        $warna = '';
                    }
                    $container = '<button type="button" class="btn '.$warna.' btn-sm">'.$row->upload_bt.'</button>';
                    return $container;
                });
            }
            $table = $table->rawColumns($rawColumns)
            ->make(true);
            return $table;
        }
        // return Auth::getUser()->level_user;
        return view('permohonan.main')->with('data', $this->data);
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

        // return $data;
        $content = view('permohonan.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function save(Request $request) {
        // return $request->all();
        $validator = Validator::make(
            $request->all(),
                [
                    'nama_pemohon' => 'required',
                ],
                [
                    'nama_pemohon.required' => 'Kolom :attribute harus diisi!'
                ]
            );

            if ($validator->fails()) {
                $pesan = $validator->errors();
                $pakai_pesan = join(',',$pesan->all());
                $return = ['status' => 'warning', 'code' => 201, 'message' => $pakai_pesan];
                return response()->json($return);
            }

            try {
                // save
                DB::beginTransaction();
                $data = Permohonan::store($request);

                try{
                    $desa = Desa::find($request->desa_id);
                    $nama_desa = $desa->nama;
                    // return $nama_desa;
                }catch(\Exception $e){
                    return response()->json(['code'=>500,'message'=>$e->getMessage()],500);
                }

                $public_path = 'uploads/registrasi';
                $file = $request->file('images');
                if(!empty($file)){
                    foreach($file as $item){
                        $nama_gambar = str_replace(' ', '_', $item->getClientOriginalName());
                        $ext_gambar = $item->getClientOriginalExtension();
                        $image = $request->no_registrasi.'_'.$nama_desa.'_'.'FILE_GAMBAR'.'_'.$nama_gambar.'_'.time().'.'.$ext_gambar;

                        $item->move(public_path($public_path), $image);

                        $images = new PermohonanImages;
                        $images->permohonan_id = $data->id_permohonan;
                        $images->gambar = $image;
                        $images->save();
                    }
                }

                $public_path = 'uploads/registrasi';
                $files = $request->file('file_pendukung');

                if (!empty($files)) {
                    $filenames = [];
                    foreach ($files as $file) {
                        $nama_file_pendukung = str_replace(' ', '_', $file->getClientOriginalName());
                        $ext_file_pendukung = $file->getClientOriginalExtension();
                        // return $ext_file_pendukung;
                        $filename = $request->no_registrasi.'_'.$nama_desa.'_'.'FILE_PENDUKUNG'.'_'.$nama_file_pendukung.'_'.time().'.'.$ext_file_pendukung;
                        // $filename = time() . '_' . $file->getClientOriginalName();

                        $file->move(public_path($public_path), $filename);
                        // $image = $data->file_pendukung;
                        // $image->permohonan_id = $request->id_permohonan;
                        // $image->gambar = $filename;
                        // $image->save();
                        $filenames[] = $filename;
                    }

                    // return $filenames;

                    $file = Permohonan::find($data->id_permohonan);
                    $file->file_pendukung = $filenames;
                    $file->save();

                }

                $file_sertifikat = $request->file('file_sertifikat');
                if(!empty($file_sertifikat)){
                    $nama_file_sertif = str_replace(' ', '_', $file_sertifikat->getClientOriginalName());
                    $ext_file_sertif = $file_sertifikat->getClientOriginalExtension();
                    $file = $request->no_registrasi.'_'.$nama_desa.'_'.'FILE_SERTIFIKAT'.'_'.$nama_file_sertif.'_'.time().'.'.$ext_file_sertif;
                    // $file = time().'_'.$file_sertifikat->getClientOriginalName();
                    $file_sertifikat->move(public_path($public_path), $file);

                    $files = Permohonan::find($data->id_permohonan);
                    $files->file_sertifikat = $file;
                    $files->save();
                }

                // if ($request->hasFile('file_pendukung')) {
                //     $filePendukungArray = [];

                //     foreach ($request->file('file_pendukung') as $file) {
                //         $nama_file_pendukung = str_replace(' ', '_', $file->getClientOriginalName());
                //         $ext_file_pendukung = $file->getClientOriginalExtension();
                //         $filename = $request->no_registrasi.'_'.$nama_desa.'_'.'FILE_PENDUKUNG'.'_'.$nama_file_pendukung.'_'.time().'.'.$ext_file_pendukung;
                //         // $filename = $file->getClientOriginalName();
                //         // $ext_foto = $file->getClientOriginalExtension();
                //         $file->storeAs('public/storage/file-pendukung/', $filename);
                //         file_put_contents(public_path('storage/storage/file-pendukung/' . $filename), file_get_contents($file->getRealPath()));
                //         $filePendukungArray[] = $filename;
                //     }
                //     $data->file_pendukung = json_encode($filePendukungArray);
                // }


                if(empty($request->petugas_lapang) && !empty($request->proses_pengukuran_lapang)){
                    $data->petugas_lapang = 1;
                    $request->merge([
                        'id_permohonan' => $data->id_permohonan,
                        'petugas_lapang' => $data->petugas_lapang,
                    ]);
                    $dataKerjakan = KerjakanPermohonanLapang::store($request);
                }

                DB::commit();
                return response()->json(['code'=>200,'message'=>'Berhasil menyimpan','data'=>$data],200);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['code'=>500,'message'=>$e->getMessage(),'file'=>$e->getFile(),'line'=>$e->getLine()],500);
            }
    }

    public function deleteGambar(Request $request){
            DB::beginTransaction();
            if(!$data = PermohonanImages::deleteGambar($request->id)){
                DB::rollback();
                return response()->json(['status'=>'error','code'=>500,'message'=>'Gagal Hapus gambar'],500);
            }
            DB::commit();
            return response()->json(['status'=>'success','code'=>200,'message'=>'Berhasil Hapus gambar'],200);
    }

    public function deleteSertif(Request $request){
            DB::beginTransaction();
            if(!$data = Permohonan::find($request->id)){
                DB::rollback();
                return response()->json(['status'=>'error','code'=>500,'message'=>'Gagal Hapus sertifikat'],500);
            }
            $path = public_path('uploads/registrasi/');
            $file = $path.$data->file_sertifikat;
            $cekFile = file_exists($file);

            if($cekFile){
                @unlink($file);
                $data->file_sertifikat = null;
                $data->save();
                DB::commit();
                return response()->json(['status'=>'success','code'=>200,'message'=>'Berhasil Hapus sertifikat'],200);
            }else{
                DB::rollback();
                return response()->json(['status'=>'fail','code'=>400,'message'=>'Gagal Hapus sertifikat'],400);
            }
    }

    public function delete(Request $request){
        try{
            DB::beginTransaction();
            $gambar = PermohonanImages::where('permohonan_id',$request->id)->get();
            if($gambar){
                foreach($gambar as $item){
                    PermohonanImages::deleteGambar($item->id_permohonan_images);
                }
            }
            $data = Permohonan::findOrFail($request->id)->delete();
            DB::commit();
            return response()->json(['status'=>'success','code'=>200,'message'=>'Berhasil Hapus data'],200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status'=>'error','code'=>500,'message'=>$e->getMessage()],500);
        }
    }

    public function detail(Request $request) {

    }

    public function suratlapangan($id_permohonan){
        $data['data'] = Permohonan::with('kecamatan','desa')->find($id_permohonan);
        return view('cetakan.surat-lapangan',$data);
    }

    public function showPindahBerkas(Request $request){
        try {
            $data['data'] = Permohonan::where('id_permohonan', $request->id)->first();
            // return $data;
            $content = view('permohonan.pindah_berkas', $data)->render();
            return ['status' => 'success', 'content' => $content];
        } catch(\Exception $e) {
            return ['status' => 'error', 'content' => $e->getMessage()];
        }
    }

    public function storePindahkanBerkas(Request $request) {
        try {
            $data = Permohonan::find($request->id_permohonan);
            if($request->petugas == '3') { # PEMETAAN
                $data->jenis_pemetaan = 'Pemetaan Langsung';
                // $data->petugas_pemetaan_id = $request->nama_petugas;
            } elseif($request->petugas == '2') { # LAPANGAN
                $data->jenis_pemetaan = 'Pemetaan Langsung';
                $data->status_pemetaan = 'Ke Lapangan';
                $data->petugas_pengukuran = $request->nama_petugas_text;
                $data->petugas_lapang_id = $request->nama_petugas;
                $data->petugas_pemetaan_id = 70;
            } elseif($request->petugas == '4') { # SUEL
                $data->status_pengukuran = 'sudah diukur';
                $data->petugas_su_el = $request->nama_petugas_text;
                $data->status_pemetaan = 'Ke Lapangan';
                $data->petugas_pengukuran = 'Moh Khomsun Kholili';
                $data->petugas_lapang_id = 78;
                $data->petugas_pemetaan_id = 70;
                // $data->petugas_suel_id = $request->nama_petugas;
            }
            $data->save();
            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Berhasil Menyimpan Data']);
        } catch (\Exception $e) {
            return ['status' => 'error', 'code' => 500, 'message' => $e->getMessage()];
        }
    }

    public function getPetugasByLevelUser(Request $request) {
        $data['petugas'] = User::where('level_user', $request->petugasValue)->get();
        return response()->json($data);
    }

    public function syncronUserPetugasPermohonan(){
        try{
        DB::beginTransaction();
        $permohonan = Permohonan::whereNull(
            ['petugas_lapang_id','petugas_pemetaan_id','petugas_suel_id','petugas_btel_id']
            )->get();
            foreach($permohonan as $item){
                if(!empty($item->petugas_pengukuran)){
                    $petugas_pengukuran = strtolower($item->petugas_pengukuran);
                    $user = User::whereRaw("lower(name) LIKE '%$petugas_pengukuran%'")->first();
                    if($user){
                        $update = Permohonan::where('id_permohonan',$item->id_permohonan)->first();
                        $update->petugas_lapang_id = $user->id;
                        $update->save();
                    }
                }
                if(!empty($item->petugas_pemetaan)){
                    $petugas_pemetaan = strtolower($item->petugas_pemetaan);
                    $user = User::whereRaw("lower(name) LIKE '%$petugas_pemetaan%'")->first();
                    if($user){
                        $update = Permohonan::where('id_permohonan',$item->id_permohonan)->first();
                        $update->petugas_pemetaan_id = $user->id;
                        $update->save();
                    }
                }
                if(!empty($item->petugas_su_el)){
                    $petugas_su_el = strtolower($item->petugas_su_el);
                    $user = User::whereRaw("lower(name) LIKE '%$petugas_su_el%'")->first();
                    if($user){
                        $update = Permohonan::where('id_permohonan',$item->id_permohonan)->first();
                        $update->petugas_suel_id = $user->id;
                        $update->save();
                    }
                }
                if(!empty($item->petugas_bt_el)){
                    $petugas_bt_el = strtolower($item->petugas_bt_el);
                    $user = User::whereRaw("lower(name) LIKE '%$petugas_bt_el%'")->first();
                    if($user){
                        $update = Permohonan::where('id_permohonan',$item->id_permohonan)->first();
                        $update->petugas_btel_id = $user->id;
                        $update->save();
                    }
                }
            }
            DB::commit();
            return response()->json(['code'=>200,'status'=>'success','message'=>'berhasil menyinkronkan'],200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['code'=>500,'status'=>'error','message'=>$e->getMessage(),'file'=>$e->getFile(),'line'=>$e->getLine()],500);
        }
    }
    public function syncronUserPetugasPermohonanByID(){
        try{
            DB::beginTransaction();
            $permohonan = Permohonan::whereNull(
                ['petugas_lapang_id','petugas_pemetaan_id','petugas_suel_id','petugas_btel_id']
                )->get();
                foreach($permohonan as $item){
                    if(!empty($item->petugas_pengukuran)){
                        $petugas_pengukuran = $item->petugas_pengukuran;
                        $user = User::find($petugas_pengukuran);
                        if($user){
                            $update = Permohonan::where('id_permohonan',$item->id_permohonan)->first();
                            $update->petugas_pengukuran = $user->name;
                            $update->petugas_lapang_id = $user->id;
                            $update->save();
                        }
                    }
                    if(!empty($item->petugas_pemetaan)){
                        $petugas_pemetaan = $item->petugas_pemetaan;
                        $user = User::find($petugas_pemetaan);
                        if($user){
                            $update = Permohonan::where('id_permohonan',$item->id_permohonan)->first();
                            $update->petugas_pemetaan = $user->name;
                            $update->petugas_pemetaan_id = $user->id;
                            $update->save();
                        }
                    }
                    if(!empty($item->petugas_su_el)){
                        $petugas_su_el = $item->petugas_su_el;
                        $user = User::find($petugas_su_el);
                        if($user){
                            $update = Permohonan::where('id_permohonan',$item->id_permohonan)->first();
                            $update->petugas_su_el = $user->name;
                            $update->petugas_suel_id = $user->id;
                            $update->save();
                        }
                    }
                    if(!empty($item->petugas_bt_el)){
                        $petugas_bt_el = $item->petugas_bt_el;
                        $user = User::find($petugas_bt_el);
                        if($user){
                            $update = Permohonan::where('id_permohonan',$item->id_permohonan)->first();
                            $update->petugas_bt_el = $user->name;
                            $update->petugas_btel_id = $user->id;
                            $update->save();
                        }
                    }
                }
                DB::commit();
                return response()->json(['code'=>200,'status'=>'success','message'=>'berhasil menyinkronkan'],200);
            }catch(\Exception $e){
                DB::rollback();
                return response()->json(['code'=>500,'status'=>'error','message'=>$e->getMessage(),'file'=>$e->getFile(),'line'=>$e->getLine()],500);
            }
    }

    // public function detailCatatan(Request $request) {
    //     $data['verifikator'] = KerjakanPermohonanVerifikator::with('user')->where('permohonan_id', $request->id)->orderBy('id_kerjakan_permohonan_verifikator', 'DESC')->first();
    //     $data['lapang'] = KerjakanPermohonanLapang::with('user')->where('permohonan_id', $request->id)->orderBy('id_kerjakan_permohonan_lapang', 'DESC')->first();
    //     $data['pemetaan'] = KerjakanPermohonanPemetaan::with('user')->where('permohonan_id', $request->id)->orderBy('id_kerjakan_permohonan_pemetaan', 'DESC')->first();
    //     $data['bt'] = KerjakanPermohonanBt::with('user')->where('permohonan_id', $request->id)->orderBy('id_kerjakan_permohonan_bt', 'DESC')->first();
    //     $data['btel'] = KerjakanPermohonanBtel::with('user')->where('permohonan_id', $request->id)->orderBy('id_kerjakan_permohonan_btel', 'DESC')->first();
    //     // return $data;
    //     $content = view('permohonan.detail-catatan', $data)->render();
    //     return ['status' => 'success', 'content' => $content];
    // }

    public function detailCatatan(Request $request){
        try {
            $permohonan = Permohonan::select('id_permohonan', 'catatan_verifikator')->with([
                'kerjakan_permohonan_lapang.user',
                'kerjakan_permohonan_lapang_validasi.user',
                'kerjakan_permohonan_pemetaan.user',
                'kerjakan_permohonan_suel.user',
                'kerjakan_permohonan_btel.user',
                'kerjakan_permohonan_verifikator.user',
                'kerjakan_permohonan_bt.user',
            ])->find($request->id);
            $kerjakan_permohonan_lapang = $permohonan->kerjakan_permohonan_lapang;
            $kerjakan_permohonan_lapang_validasi = $permohonan->kerjakan_permohonan_lapang_validasi;
            $kerjakan_permohonan_pemetaan = $permohonan->kerjakan_permohonan_pemetaan;
            $kerjakan_permohonan_suel = $permohonan->kerjakan_permohonan_suel;
            $kerjakan_permohonan_btel = $permohonan->kerjakan_permohonan_btel;
            $kerjakan_permohonan_verifikator = $permohonan->kerjakan_permohonan_verifikator;
            $kerjakan_permohonan_bt = $permohonan->kerjakan_permohonan_bt;
            $catatan = [];
            if($kerjakan_permohonan_lapang){
                array_push($catatan,$kerjakan_permohonan_lapang);
            }

            if($kerjakan_permohonan_lapang_validasi){
                array_push($catatan,$kerjakan_permohonan_lapang_validasi);
            }

            if($kerjakan_permohonan_pemetaan){
                array_push($catatan,$kerjakan_permohonan_pemetaan);
            }

            if($kerjakan_permohonan_suel){
                array_push($catatan,$kerjakan_permohonan_suel);
            }

            if($kerjakan_permohonan_btel){
                array_push($catatan,$kerjakan_permohonan_btel);
            }

            if($kerjakan_permohonan_verifikator){
                $kerjakan_permohonan_verifikator->catatan_verifikator = $permohonan->catatan_verifikator;
                array_push($catatan,$kerjakan_permohonan_verifikator);
            }

            if($kerjakan_permohonan_bt){
                array_push($catatan,$kerjakan_permohonan_bt);
            }
            $data['data'] = $catatan;
            // return $data;
            $content = view('permohonan.catatan',$data)->render();
            return response()->json([
                'status' => 'success',
                'content' => $content,
            ],200);
        } catch (\Throwable $th) {
            Log::info(json_encode([
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ],JSON_PRETTY_PRINT));
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error!',
            ],500);
        }
    }

    public function export(Request $request){
        $dates = null;
        if(isset($request->dates) && $request->dates != ''){
            $dates = explode(' - ',$request->dates);
            foreach($dates as &$date){
                $date = date('Y-m-d',strtotime($date));
            }
        }
        $data = Permohonan::select('permohonan.*','mst_kecamatans.nama as kecamatan_nama','mst_desas.nama as desa_nama')
        ->join('mst_kecamatans','permohonan.kecamatan_id','mst_kecamatans.id')
        ->join('mst_desas','permohonan.desa_id','mst_desas.id')
        ->when(isset($request->dates) && $request->dates != '' ,function($q) use ($dates){
            $q->whereBetween('tgl_input',$dates);
        })
        ->when(isset($request->status) && $request->status != '' ,function($q) use ($request){
            $q->where('status_permohonan',$request->status);
        })
        ->when(isset($request->jenis_hak) && $request->jenis_hak != '' ,function($q) use ($request){
            $q->where('jenis_hak',$request->jenis_hak);
        })
        ->orderBy('id_permohonan', 'DESC')
        ->get();
        // return $data;
        $namaFile = "Data_Registrasi_$dates[0]".'_'."$dates[1].xlsx";
        return Excel::download(new RegisterExport($data),$namaFile);
    }
}
