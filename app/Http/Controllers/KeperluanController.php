<?php

namespace App\Http\Controllers;

use App\Models\Keperluan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class KeperluanController extends Controller
{
  private $menuActive = "keperluan";
  private $submnActive = "";
  public function index(Request $request) {
    $this->data['menuActive'] = $this->menuActive;
    $this->data['submnActive'] = $this->submnActive;
    if ($request->ajax()) {
        $data = Keperluan::get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="javascript:void(0)" onclick="editForm('.$row->id_keperluan.')" style="margin-right: 5px;" class="btn btn-sm btn-primary "><i class="fa fa-pencil" style="font-size: 23px; margin-right: -1px; padding-top: 1px;"></i></a>';
                $btn .= '<a href="javascript:void(0)" onclick="deleteRow('.$row->id_keperluan.')" style="margin-right: 5px;" class="btn btn-sm btn-danger "><i class="fa fa-trash" style="font-size: 23px; margin-right: -1px; padding-top: 1px;"></i></a>';
                $btn .='</div></div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        // return $this->data;
    return view('keperluan.main')->with('data', $this->data);
  }

  public function form(Request $request) {
    try {
      $data['data'] = (!empty($request->id)) ? Keperluan::find($request->id) : "";
      $content = view('keperluan.form', $data)->render();
      return ['status' => 'success', 'content' => $content];
    } catch(\Exception $e) {
      return ['status' => 'success', 'content' => $e->getMessage()];
    }
  }

  public function store(Request $request) {
    // return $request->all();
    $validator = Validator::make(
        $request->all(),
        [
            'nama_keperluan' => 'required',
        ],
        [
            'required' => 'Kolom :attribute Wajib diisi'
        ]
    );

    if ($validator->fails()) {
        $pesan = $validator->errors();
        $pakai_pesan = join(',',$pesan->all());
        $return = ['status' => 'warning', 'code' => 201, 'message' => $pakai_pesan];
        return response()->json($return);
    }

    try {
        DB::beginTransaction();
        $newdata = (!empty($request->id)) ? Keperluan::find($request->id) : new Keperluan;
        $newdata->nama_keperluan = $request->nama_keperluan;
        $newdata->save();
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
        ]);
    }
  }

  public function destroy(Request $request)
  {
    try {
      $kode = Keperluan::find($request->id);

      if (!$kode) {
        return response()->json([
          'error' => 'Data not found'
        ], 404);
      }

      $kode->delete();

      return response()->json([
        'success' => 'Data Berhasil Dihapus'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'error' => 'Terjadi kesalahan, silahkan coba lagi'
      ], 500);
    }
  }
}
