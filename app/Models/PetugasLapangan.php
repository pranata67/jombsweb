<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasLapangan extends Model
{
    use HasFactory;

    protected $table = 'petugas_lapangan';
    protected $primaryKey = 'id_petugas_lapangan';
    protected $dates = ['deleted_at'];
}
