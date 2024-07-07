<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    use HasFactory;

    protected $fillable = ['bulan', 'tahun', 'counter'];
    public $timestamps = false;

    public function getNoTrxPerMonth($month, $year): int
    {
        return Counter::where('bulan', $month)->where('tahun', $year)->pluck('counter')->first() ?? 0;
    }
}
