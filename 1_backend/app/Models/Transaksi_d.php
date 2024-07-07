<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi_d extends Model
{
    use HasFactory;

    protected $fillable = ['id_transaksi_h', 'id_barang', 'qty', 'harga', 'total_harga'];
    public $timestamps = false;
}
