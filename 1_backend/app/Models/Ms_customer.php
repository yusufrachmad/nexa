<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ms_customer extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'alamat', 'phone'];
    public $timestamps = false;
}
