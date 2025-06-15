<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PetugasVerifikatorController extends Controller
{
  private $menuActive = "petugas-verifikator";
  private $submnActive = "";
  public function index(Request $request) {
    $this->data['menuActive'] = $this->menuActive;
    $this->data['submnActive'] = $this->submnActive;
    if ($request->ajax()) {
        $data = User::where('level_user', '11')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
              $btn = '';
              if(Auth::getUser()->level_user == 1){
                $btn = '<a href="javascript:void(0)" onclick="editForm('.$row->id.')" style="margin-right: 5px;" class="btn btn-sm btn-primary "><i class="fa fa-pencil" style="font-size: 19px; margin-right: -1px; padding-top: 1px;"></i></a>';
                $btn .= '<a href="javascript:void(0)" onclick="resetPw('.$row->id.')" style="margin-right: 5px;" class="btn btn-sm btn-secondary "><i class="fa fa-refresh" style="font-size: 19px; margin-right: -1px; padding-top: 1px;"></i></a>';
                $btn .= '<a href="javascript:void(0)" onclick="deleteRow('.$row->id.')" style="margin-right: 5px;" class="btn btn-sm btn-danger "><i class="fa fa-trash" style="font-size: 19px; margin-right: -1px; padding-top: 1px;"></i></a>';
                $btn .='</div></div>';
              }
              return $btn;
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    return view('petugas-verifikator.main')->with('data', $this->data);
  }

  public function form(Request $request) {
    try {
      $data['data'] = (!empty($request->id)) ? User::find($request->id) : "";
      $content = view('petugas-verifikator.form', $data)->render();
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
            'nama_lengkap' => 'required',
            // 'no_telepon' => 'required',
            'username' => 'required',
            // 'password' => 'required',
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

    DB::beginTransaction();
    try {
        $newdata = (!empty($request->id)) ? User::find($request->id) : new User;
        $newdata->name = $request->nama_lengkap;
        $newdata->no_telepon = $request->no_telepon;
        $newdata->email = $request->username;
        $newdata->nip = $request->nip;
        $newdata->pangkat_golongan = $request->pangkat_golongan;
        $newdata->jabatan = $request->jabatan;
        $newdata->level_user = '11';
        $newdata->password = Hash::make($request->password);
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
      $kode = User::find($request->id);

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
