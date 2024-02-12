<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'client';
    protected $guarded = [];
    protected $fillable = [
        'nama_client',
        'alamat_client',
        'tgl_mulai_kontrak',
        'tgl_akhir_kontrak',        
    ];
}
