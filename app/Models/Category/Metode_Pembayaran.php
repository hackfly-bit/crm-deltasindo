<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metode_Pembayaran extends Model
{
    use HasFactory;
    public $table = 'metode_pembayaran';
    protected $guarded = ['id'];
}
