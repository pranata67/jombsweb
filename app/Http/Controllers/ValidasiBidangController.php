<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\KerjakanPermohonanLapangValidasi;
use App\Models\Permohonan;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiBidangController extends Controller
{
    private $menuActive = "validasi-bidang";
    private $submnActive = "";

    public function index(Request $request){
        $this->data['menuActive'] = $this->menuActive;
        $this->data['submnActive'] = $this->submnActive;
        $this->data['kecamatan'] = Kecamatan::where('kabupaten_id', '3516')->get();
        if($request->ajax()){
            $data = KerjakanPermohonanLapangValidasi::join(
                DB::raw('(SELECT permohonan_id, MAX(created_at) as created_at FROM kerjakan_permohonan_lapang_validasi GROUP BY permohonan_id) as t2'),
                function ($join) {
                    $join->on('kerjakan_permohonan_lapang_validasi.permohonan_id', '=', 't2.permohonan_id')
                         ->on('kerjakan_permohonan_lapang_validasi.created_at', '=', 't2.created_at');
                }
            )
            ->with('permohonan:id_permohonan,no_permohonan','kecamatan:id,nama','desa:id,nama','user:id,name')
            ->when(isset($request->kecamatan_id) && $request->kecamatan_id != '' ,function($q) use ($request){
                $q->where('kecamatan_id',$request->kecamatan_id);
            })
            ->when(isset($request->desa_id) && $request->desa_id != '' ,function($q) use ($request){
                $q->where('desa_id',$request->desa_id);
            })
            ->get();
            $path = public_path('uploads/validasi/');
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('no_permohonan_',function($row){
                return '<a href="'.route("main-registrasi").'?no='.$row->permohonan->no_permohonan.'">'.$row->permohonan->no_permohonan.'</a>';
            })
            ->addColumn('foto_lokasi_',function($row) use ($path){
                $file = $path.$row->foto_lokasi;
                if($row->foto_lokasi && file_exists($file)){
                    return '<a href="'.asset('uploads/validasi/'.$row->foto_lokasi).'" download>Download</a>';
                }else{
                    return 'tidak ada file';
                }
            })
            ->addColumn('file_dwg_',function($row) use ($path){
                $file = $path.$row->file_dwg;
                if($row->file_dwg && file_exists($file)){
                    return '<a href="'.asset('uploads/validasi/'.$row->file_dwg).'" download>Download</a>';
                }else{
                    return 'tidak ada file';
                }
            })
            ->addColumn('sket_gambar_',function($row) use ($path){
                $file = $path.$row->sket_gambar;
                if($row->sket_gambar && file_exists($file)){
                    return '<a href="'.asset('uploads/validasi/'.$row->sket_gambar).'" download>Download</a>';
                }else{
                    return 'tidak ada file';
                }
            })
            ->addColumn('txt_csv_',function($row) use ($path){
                $file = $path.$row->txt_csv;
                if($row->txt_csv && file_exists($file)){
                    return '<a href="'.asset('uploads/validasi/'.$row->txt_csv).'" download>Download</a>';
                }else{
                    return 'tidak ada file';
                }
            })
            ->editColumn('created_at', function($row){
                return \Carbon\Carbon::parse($row->created_at)->isoFormat('DD MMMM YYYY');
            })
            ->rawColumns([
                'no_permohonan_',
                'foto_lokasi_',
                'file_dwg_',
                'sket_gambar_',
                'txt_csv_'
            ])
            ->make(true);
        }
        return view('validasi-bidang.main')->with('data', $this->data);
    }

    public function mainValidasiBidang(Request $request) {
        $this->data['menuActive'] = $this->menuActive;
        $this->data['submnActive'] = $this->submnActive;
        if($request->ajax()) {
            $filter = $request->filter_berkas;
            // $query = Permohonan::with('kecamatan', 'desa')
            //     ->where('petugas_lapang_id', Auth::getUser()->id)
            //     ->orderBy('id_permohonan', 'DESC');
            $query = KerjakanPermohonanLapangValidasi::with('permohonan.desa','permohonan.kecamatan');
            if ($filter === 'lengkap') {
                $query->where(function ($q) {
                    $q->whereNotNull('foto_lokasi')
                        ->whereNotNull('file_dwg')
                        ->whereNotNull('sket_gambar')
                        ->whereNotNull('txt_csv');
                });
            } elseif ($filter === 'tidak_lengkap') {
                $query->where(function ($q) {
                    $q->whereNull('foto_lokasi')
                        ->orWhereNull('file_dwg')
                        ->orWhereNull('sket_gambar')
                        ->orWhereNull('txt_csv');
                });
            }
            $data = $query->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" onclick="kerjakanModal('.$row->permohonan_id.',`lapang`,null,`validasi`)" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-file"></i></a>';
            })
            ->make(true);
        }
        return view('validasi-bidang.main-kerjakan-validasi')->with('data', $this->data);
    }
}
