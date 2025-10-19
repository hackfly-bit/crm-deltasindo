<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segmentasi extends Model
{
    use HasFactory;
    public $table = 'segmentasi';
    protected $guarded = ['id'];
}
