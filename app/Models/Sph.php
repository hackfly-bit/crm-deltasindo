<?php

namespace App\Models;

use App\Models\Category\Principal;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\Revisionable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sph extends Revisionable
{
    use HasFactory;

    protected $guarded = ['id'];


    public function customer(){

        return $this->belongsTo(Customer::class);

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function brand($id)
    {
        return Principal::where('id',$id)->get()->pluck('name')->first();
    }

    /**
     * Relasi ke tabel sph_products (many-to-many dengan products melalui pivot)
     */
    public function sphProducts()
    {
        return $this->hasMany(SphProduct::class);
    }

    /**
     * Relasi many-to-many dengan products melalui sph_products
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'sph_products')
                    ->withPivot('brand_id', 'quantity', 'notes')
                    ->withTimestamps();
    }

    /**
     * Helper method untuk mendapatkan semua brands yang terkait
     */
    public function getBrandsAttribute()
    {
        return $this->sphProducts()->distinct('brand_id')->pluck('brand_id')->filter();
    }

    /**
     * Helper method untuk mendapatkan semua products dengan brand
     */
    public function getProductsWithBrandAttribute()
    {
        return $this->sphProducts()->with('product')->get();
    }

      /**
     * Accessor untuk mendapatkan nama brand dari relasi sphProducts
     */
    public function getBrandNamesAttribute()
    {
        $brandIds = $this->sphProducts()->pluck('brand_id')->filter()->unique();

        if ($brandIds->isEmpty()) {
            return '';
        }

        // Ambil nama brand dari tabel principal berdasarkan ID
        $principalNames = Principal::whereIn('id', $brandIds)->pluck('name', 'id');

        // Map brand IDs ke nama, fallback ke nilai asli jika bukan ID
        return $brandIds->map(function ($brandId) use ($principalNames) {
            return $principalNames->get($brandId, $brandId);
        })->unique()->implode(', ');
    }

    /**
     * Accessor untuk mendapatkan nama produk dari relasi sphProducts
     */
    public function getProductNamesAttribute()
    {
        return $this->sphProducts()
                    ->with('product')
                    ->get()
                    ->pluck('product.nama_produk')
                    ->filter()
                    ->unique()
                    ->implode(', ');
    }
}
