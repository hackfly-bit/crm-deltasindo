<?php

namespace App\Models;

use App\Models\Category\Principal;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\Revisionable;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Revisionable
{
    use HasFactory;

    protected $guarded = ['id'];


    public function visit()
    {
        return $this->hasMany(Kegiatan_visit::class);
    }

    public function sph()
    {
        return $this->hasMany(Sph::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function call()
    {
        return $this->hasMany(Call::class);
    }
    public function brand($id)
    {
        return Principal::where('id',$id)->get()->pluck('name')->first();
    }

    public function presentasi()
    {
       return $this->hasMany(Presentasi::class);
    }
    
    public function preorder()
    {
        return $this->hasMany(Preorder::class);
    }
    
}
