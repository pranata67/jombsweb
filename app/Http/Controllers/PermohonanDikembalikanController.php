<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class PermohonanDikembalikanController extends Controller
{
    private $menuActive = "registrasi-dikembalikan";
    private $submnActive = "";

    public function index(Request $request) {
        $this->data['menuActive'] = $this->menuActive;
        $this->data['submnActive'] = $this->submnActive;
        if ($request->ajax()) {
            $level_user = Auth::getUser()->level_user;
            $user_id = Auth::id();
            $data = Permohonan::with('kecamatan', 'desa')
            ->when($level_user == 11, function($q) use ($user_id) { # petugas verifikator
                $q->whereIn('status_pemetaan', ['Tolak dan Kembalikan Berkas','Tolak'])
                ->orWhere('status_su_el', 'Tolak dan Kembalikan Berkas');
            })
            ->when($level_user == 3, function($q) use ($user_id) { # petugas pemetaan
                $q->where('status_pengukuran', 'Tolak dan Kembalikan Berkas')
                    ->orWhere('status_su_el', 'Tolak dan Kembalikan Berkas')
                  ->where('petugas_pemetaan_id', $user_id);
            })
            ->when($level_user == 2, function($q) use ($user_id) { # petugas lapangan
                $q->where('status_su_el', 'Tolak dan Kembalikan Berkas')
                  ->where('petugas_lapang_id', $user_id);
            })
            ->get();
            $table = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                if (Auth::getUser()->level_user == '1' || Auth::getUser()->level_user == '10') { #admin
                    $btn = '<div class="dropdown">';
                    $btn .= '<button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton'.$row->id_permohonan.'" data-bs-toggle="dropdown" aria-expanded="false">';
                    $btn .= '<i class="fa fa-fw fa-bars"></i>';
                    $btn .= '</button>';
                    $btn .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$row->id_permohonan.'" style="font-size: 12px;">';
                    $btn .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="editForm('.$row->id_permohonan.',1)">Detail</a></li>';
                    $btn .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="editForm('.$row->id_permohonan.')">Edit</a></li>';
                    $btn .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteRow('.$row->id_permohonan.')">Hapus</a></li>';
                    $btn .= '</ul>';
                    $btn .= '</div>';
                }
                elseif(Auth::getUser()->level_user == '2'){#lapangan
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`lapang`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`lapang`,null,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                }elseif(Auth::getUser()->level_user == '3'){#pemetaan
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`pemetaan`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`pemetaan`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                }elseif(Auth::getUser()->level_user == '4'){#suel
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`suel`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`suel`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                }elseif(Auth::getUser()->level_user == '5'){#btel
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`btel`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`btel`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                }elseif(Auth::getUser()->level_user == '11'){#verifikator
                    if($row->jenis_pemetaan === 'Pemetaan Langsung'){
                        $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`verifikator`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                        $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`verifikator`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
                    }else{
                        $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`verifikator`)" style="margin-right: 5px;" class="btn btn-sm btn-warning ">Kerjakan</a>';
                    }
                }elseif(Auth::getUser()->level_user == '12'){#bt
                    $btn = '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`bt`)" style="margin-right: 5px;" class="btn btn-sm btn-warning "><i class="fa fa-pencil"></i></a>';
                    $btn .= '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->id_permohonan.',`bt`,true,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
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
            $table = $table->rawColumns($rawColumns)
            ->make(true);
            return $table;
        }
        return view('permohonan-dikembalikan.main')->with('data', $this->data);
    }
}
