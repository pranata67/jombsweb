<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PermohonanSelesaiDikerjakanController extends Controller
{
    private $menuActive = "registrasi-selesai";
    private $submnActive = "";

    public function index(Request $request) {
        $this->data['menuActive'] = $this->menuActive;
        $this->data['submnActive'] = $this->submnActive;
        if ($request->ajax()) {
            $data = Permohonan::with('kecamatan','desa')
            ->when(Auth::getUser()->level_user == 5, function($q) { # btel
                $q->where('status_bt_el', 'Sudah')
                ->where('petugas_btel_id', Auth::getUser()->id);
            })
            ->when(Auth::getUser()->level_user == 12, function($q) { # bt
                $q->where('upload_bt', 'Sudah')
                ->where('petugas_bt_id', Auth::getUser()->id);
            })
            ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('formatDate', function($row) {
                    return date('d-m-Y', strtotime($row->tgl_input));
                })
                ->addColumn('catatan', function($row){
                    return '<a href="javascript:void(0);" onclick="showCatatan('.$row->id_permohonan.')">Lihat</a>';
                })
                ->rawColumns(['formatDate','action','catatan'])
                ->make(true);
        }
        // return Auth::getUser()->level_user;
        return view('selesai-dikerjakan.main')->with('data', $this->data);
    }
}
