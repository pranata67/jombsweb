<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeRegistrasi extends Model
{
    use HasFactory;
    protected $table = 'kode_registrasi';
    protected $primaryKey = 'id';
    protected $fillable = ['is_active'];
}
