<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasPemetaan extends Model
{
    use HasFactory;

    protected $table = 'petugas_pemetaan';
    protected $primaryKey = 'id_petugas_pemetaan';
    protected $dates = ['deleted_at'];
}
