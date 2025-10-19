<?php

namespace App\Models;

use App\Models\Category\Principal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\Revisionable;

class Kegiatan_visit extends Revisionable
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
     * Relasi ke tabel visit_products (many-to-many dengan products melalui pivot)
     */
    public function visitProducts()
    {
        return $this->hasMany(VisitProduct::class, 'kegiatan_visit_id');
    }

    /**
     * Relasi many-to-many dengan products melalui visit_products
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'visit_products', 'kegiatan_visit_id')
                    ->withPivot('brand_id', 'quantity', 'notes')
                    ->withTimestamps();
    }

    /**
     * Helper method untuk mendapatkan semua brands yang terkait
     */
    public function getBrandsAttribute()
    {
        return $this->visitProducts()->distinct('brand_id')->pluck('brand_id')->filter();
    }

    /**
     * Helper method untuk mendapatkan semua products dengan brand
     */
    public function getProductsWithBrandAttribute()
    {
        return $this->visitProducts()->with('product')->get();
    }

    /**
     * Accessor untuk mendapatkan nama brand dari relasi visitProducts
     */
    public function getBrandNamesAttribute()
    {
        $brandIds = $this->visitProducts()->pluck('brand_id')->filter()->unique();

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
     * Accessor untuk mendapatkan nama produk dari relasi visitProducts
     */
    public function getProductNamesAttribute()
    {
        return $this->visitProducts()
                    ->with('product')
                    ->get()
                    ->pluck('product.nama_produk')
                    ->filter()
                    ->unique()
                    ->implode(', ');
    }









}
