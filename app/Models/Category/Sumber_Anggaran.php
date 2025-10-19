<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sumber_Anggaran extends Model
{
    use HasFactory;
    public $table = 'sumber_anggaran';
    protected $guarded = ['id'];

}
