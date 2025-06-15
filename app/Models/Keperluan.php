<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keperluan extends Model
{
    use HasFactory;

    protected $table = 'mst_keperluan';
    protected $primaryKey = 'id_keperluan';
    // protected $guarded = ['id_permohonan'];
    protected $dates = ['deleted_at'];
}
