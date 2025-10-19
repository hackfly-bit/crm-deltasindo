<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Venturecraft\Revisionable\Revisionable;

class Kegiatan_other extends Revisionable
{
    use HasFactory;

    protected $guarded = ['id'];

    public function customer(){
       return $this->belongsTo(Customer::class);
    }
}
