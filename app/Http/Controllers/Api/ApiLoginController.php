<?php

namespace App\Http\Controllers\api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiLoginController extends Controller
{
    // public function doLogin(Request $request) {
    //     // return response()->json(['status' => 'kontol']);
    //     $rules = [
    //         'username' => 'required',
    //         'password' => 'required'
    //     ];
    //     $message = [
    //         'required' => 'Koloms :attribute tidak boleh kosong'
    //     ];

    //     $valid = Validator::make($request->all(), $rules, $message);
    //     if ($valid->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'code' => 400,
    //             'message' => $valid->errors()->all()
    //         ]);
    //     }

    //     $user = User::where('email', $request->username)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json([
    //             'status' => 'error',
    //             'code' => 401,
    //             'message' => 'Username atau password anda salah, silahkan cek kembali'
    //         ]);
    //     }

    //     Auth::login($user);

    //     return response()->json([
    //         'status' => 'success',
    //         'code' => 200,
    //         'message' => 'Login Berhasil',
    //         'data' => Auth::getUser()->level_user,
    //     ]);
    // }
    public function doLogin(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
          return response()->json(['code'=> 422, 'status' => "validation_error", 'messege' => 'Input tidak valid', 'data' => $validator->errors()]);
        }

        try{
            if(Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
                $auth = User::where('email', $request->username)->first();
                // $data['token'] = $auth->createToken('auth_token')->plainTextToken;
                // $data['name'] = $auth->name;
                // $data['nip'] = $auth->email;
                // $data['level_user'] = $auth->level_user;
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'messege' => 'login berhasil',
                    'data' => $auth,
                ]);
            } else {
              return response()->json([
              'success' => false,
              'code' => 401,
              'message' => 'Username atau Password salah'
            ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => $e->getMessage()
            ]);
        }
      }

      // update password
        public function updatePassword(Request $request){
            $validator = Validator::make($request->all(), [
                'id_user' => 'required|exists:users,id',
                'name' => 'required',
                'no_telepon' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password'
            ], [
                'id_user.required' => 'ID User wajib diisi.',
                'id_user.exists' => 'ID User tidak ditemukan.',
                'name.required' => 'Nama wajib diisi.',
                'no_telepon.required' => 'Nomor telepon wajib diisi.',
                'new_password.required' => 'Password baru wajib diisi.',
                'confirm_password.required' => 'Konfirmasi password wajib diisi.',
                'confirm_password.same' => 'Konfirmasi password harus sama dengan password baru.',
            ]);

            if ($validator->fails()) {
                $errors = [];
                foreach ($validator->errors()->getMessages() as $field => $messages) {
                    $errors[$field] = $messages[0];
                }
                return ResponseFormatter::error($errors, 'Validation Error', 422);
            }

            try{
                $user = User::find($request->id_user);
                if(!$user){
                    return ResponseFormatter::error(null, 'User tidak ditemukan', 404);
                }
                $user->name = $request->name;
                $user->no_telepon = $request->no_telepon;
                $user->password = Hash::make($request->new_password);
                $user->save();

                return ResponseFormatter::success($user->only(['id', 'name', 'no_telepon']), 'Password berhasil diubah');
            }catch(\Exception $e){
                return ResponseFormatter::error(null, $e->getMessage(), 500);
            }
        }
}
