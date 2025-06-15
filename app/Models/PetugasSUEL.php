<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasSUEL extends Model
{
    use HasFactory;

    protected $table = 'petugas_su_el';
    protected $primaryKey = 'id_petugas_su_el';
    protected $dates = ['deleted_at'];
}
