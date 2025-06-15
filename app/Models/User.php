<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    
    public function kerjakan_permohonan_lapang_validasi(){
        return $this->hasMany(KerjakanPermohonanLapangValidasi::class,'user_id','id');
    }
    public function kerjakan_permohonan_lapang(){
        return $this->hasMany(KerjakanPermohonanLapang::class,'user_id','id');
    }
    public function permohonan_user_lapang(){
        return $this->hasMany(Permohonan::class,'petugas_lapang_id','id');
    }
    public function kerjakan_permohonan_pemetaan(){
        return $this->hasMany(KerjakanPermohonanPemetaan::class,'user_id','id');
    }
    public function kerjakan_permohonan_suel(){
        return $this->hasMany(KerjakanPermohonanSuel::class,'user_id','id');
    }
    public function kerjakan_permohonan_btel(){
        return $this->hasMany(KerjakanPermohonanBtel::class,'user_id','id');
    }
    public function kerjakan_permohonan_verifikator(){
        return $this->hasMany(KerjakanPermohonanVerifikator::class,'user_id','id');
    }
    public function kerjakan_permohonan_bt(){
        return $this->hasMany(KerjakanPermohonanBt::class,'user_id','id');
    }
}
