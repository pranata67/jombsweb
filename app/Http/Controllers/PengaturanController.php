<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

class PengaturanController extends Controller
{
  private $menuActive = "pengaturan";
  private $submnActive = "";
  public function index() {
    $this->data['menuActive'] = $this->menuActive;
    $this->data['submnActive'] = $this->submnActive;
    $this->data = User::first();

    // return $this->data;
    return view('pengaturan.main')->with('data', $this->data);
  }

  public function updatePassword(Request $request) {
    // return $request->all();
    $validator = Validator::make(
        $request->all(),
        [
            'password_sekarang' => 'required',
            'password_baru' => 'required',
            // 'email' => 'email|required',
        ],
        [
            'required' => 'Kolom :attribute tidak boleh kosong',
            // 'email' => 'Kolom :attribute format email tidak sesuai'
        ]
    );

    if ($validator->fails()) {
      $pesan = $validator->errors();
      $pakai_pesan = join(',',$pesan->all());
      $return = ['status' => 'warning', 'code' => 201, 'message' => $pakai_pesan];
      return response()->json($return);
    }
    try {
      $user = User::find(auth()->user()->id);
      // if($request->email !== $user->email) {
      //   return response()->json(['status' => 'warning', 'code' => 401, 'message' => 'Email tidak sesuai']);
      // }
      if(!Hash::check($request->password_sekarang, $user->password)){
        return response()->json(['status' => 'warning', 'code' => 402, 'message' => 'Password sebelumnya tidak sesuai']);
      }else{
        User::whereId(auth()->user()->id)->update([
          'password' => Hash::make($request->password_baru)
        ]);
        DB::commit();
        return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Password Berhasil dirubah']);
      }
    } catch(\Exception $e) {
      DB::rollback();
      return response()->json([
        'status' => 'error',
        'code' => 500,
        'message' => $e->getMessage(),
      ]);
    }
  }
  
  public function resetPw(Request $request)
  {
    try {
        DB::beginTransaction();
      $kode = User::find($request->id);

      if (!$kode) {
        DB::rollback();
        return response()->json([
          'error' => 'Data not found'
        ], 404);
      }

      $kode->password = Hash::make($kode->email);
      $kode->save();

      DB::commit();
      return response()->json([
        'success' => 'Data Berhasil Direset'
      ]);
    } catch (\Exception $e) {
        DB::rollback();
      return response()->json([
        'error' => 'Terjadi kesalahan, silahkan coba lagi'
      ], 500);
    }
  }
}
