<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';
    protected $guarded = [];
    protected $fillable = [
        'client_id',
        'nama_item',
        'harga_item',
        'tanggal_order'        
    ];
}
