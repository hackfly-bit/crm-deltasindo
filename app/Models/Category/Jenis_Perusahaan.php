<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis_Perusahaan extends Model
{
    use HasFactory;
    public $table = 'jenis_perusahaan';
    protected $guarded = ['id'];
}
