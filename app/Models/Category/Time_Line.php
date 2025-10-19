<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time_Line extends Model
{
    use HasFactory;
    public $table = 'time_line';
    protected $guarded = ['id'];
}
