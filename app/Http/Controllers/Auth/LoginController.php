<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassowrdMail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use DB;

class LoginController extends Controller
{
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
      $this->middleware('guest')->except('logout');
    }

  public function index(Request $request) {
    $this->data['next_url'] = empty($request->next_url) ? '' : $request->next_url;
    return view('auth.login.main');
  }

  public function doLogin(Request $request) {
    // return $request->all();
    $rules = [
        'username' => 'required',
        'password' => 'required'
    ];
    $message = [
        'required' => 'Kolom :attribute tidak boleh kosong'
    ];

    $valid = Validator::make($request->all(), $rules, $message);
    if ($valid->fails()) {
        return response()->json([
            'status' => 'error',
            'code' => 400,
            'message' => $valid->errors()->all()
        ]);
    }

    $user = User::where('email', $request->username)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'status' => 'error',
            'code' => 401,
            'message' => 'Username atau password anda salah, silahkan cek kembali'
        ]);
    }

    Auth::login($user);

    return response()->json([
        'status' => 'success',
        'code' => 200,
        'message' => 'Login Berhasil',
        'data' => Auth::getUser()->level_user,
    ]);
  }

  public function forgotPassword(Request $request) {

    return view('auth.forgot-password.main');
  }

  # Mengirim link reset password melalui email
  public function  sendEmail(Request $request) {
    $message = [
        'email.required' => 'Email tidak boleh kosong',
        'email.email' => 'Email tidak valid',
        'email.exists' => 'Email tidak terdaftar, masukkan email yang sesuai'
    ];
    $request->validate(['email' => 'required|email|exists:users,email'], $message);
    $token = \Str::random(60);
    PasswordReset::updateOrCreate(
        [
            'email' => $request->email
        ],
        [
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]
    );

    Mail::to($request->email)->send(new ResetPassowrdMail($token));

    return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Kami telah mengirimkan link reset password melalui email, silahkan cek email anda']);
  }



  public function resetPassword(Request $request, $token) {
    $token = PasswordReset::where('token', $token)->first();
    if(!$token)  {
        return redirect()->route('login')->with('failed', 'Token is mismatch');
    }
    return view('auth.forgot-password.reset')->with('token', $token->token);
  }

  # Pemrosessan reset ulang password
  public function resetUlangPassword(Request $request) {
    // return $request->all();
    // dd($request->all());
    try {
        $token = PasswordReset::where('token', $request->token)->first();
        if(!$token)  {
            return response()->json(['status' => 'whoops', 'code' => 400, 'message' => 'Token kadaluarsa']);
        }

        $user = User::where('email', $token->email)->first();
        if(!$token)  {
            return response()->json(['status' => 'warning', 'code' => 201, 'message' => 'Email tidak terdaftar!']);
        }

        $user->update([
            'password' =>Hash::make($request->password)
        ]);
        $token->delete();
        return response()->json(['status' => 'success', 'code'  => 200, 'message' => 'Password Berhasil Diganti']);
    } catch(\Exception $e) {
        return response()->json(['status' => 'error', 'code'  => 500, 'message' => $e->getMessage()]);
    }
  }

  public function logout(Request $request ) {
    Auth::logout();
    if ($request->ajax()) {
      return response()->json([
        'success' => true
      ]);
    }
  }
}

