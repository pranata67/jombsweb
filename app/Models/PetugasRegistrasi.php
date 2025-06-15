<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasRegistrasi extends Model
{
    use HasFactory;
    protected $table = 'petugas_registrasi';
    protected $primaryKey = 'id_petugas_registrasi';
    protected $dates = ['deleted_at'];
}
