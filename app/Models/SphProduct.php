<?php

namespace App\Models;

use App\Models\Category\Principal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SphProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'sph_id',
        'product_id',
        'brand_id',
        'quantity',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'brand_id' => 'integer',
    ];

    /**
     * Relasi ke model Sph
     */
    public function sph()
    {
        return $this->belongsTo(Sph::class);
    }

    /**
     * Relasi ke model Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi ke model Principal (Brand)
     */
    public function brand()
    {
        return $this->belongsTo(Principal::class, 'brand_id');
    }

    /**
     * Scope untuk filter berdasarkan brand ID
     */
    public function scopeByBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }
}
