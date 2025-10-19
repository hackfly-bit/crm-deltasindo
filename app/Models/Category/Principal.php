<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Principal extends Model
{
    use HasFactory;
    public $table = 'principal';
    protected $guarded = ['id'];
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
