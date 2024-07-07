<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Transaksi_h extends Model
{
    use HasFactory;

    protected $fillable = ['id_customer', 'nomor_transaksi', 'tanggal_transaksi', 'total_transaksi'];
    protected $dates = ['tanggal_transaksi'];
    public $timestamps = false;

    public function transaksi_d()
    {
        return $this->hasMany(Transaksi_d::class, 'id_transaksi_hs', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Ms_customer::class, 'id_customer', 'id');
    }

    public function scopeDateFilter(Builder $query, $from, $to): void
    {
        $query->whereBetween('tanggal_transaksi', [$from, $to]);
    }
}
