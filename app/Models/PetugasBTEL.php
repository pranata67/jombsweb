<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasBTEL extends Model
{
    use HasFactory;
    protected $table = 'petugas_bt_el';
    protected $primaryKey = 'id_petugas_bt_el';
    protected $dates = ['deleted_at'];
}
