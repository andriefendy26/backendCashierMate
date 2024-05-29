<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Models\produk;
use App\Models\usaha;
use App\Models\cart;

class itemhastransaksi extends Model
{
    use HasFactory;

    protected $table = 'itemhastransaksi';

    protected $fillable = ['total', 'qty', 'usaha_id', 'produk_id', 'cart_id'];
    public $timestamps = false;


    public function produk()
    {
        return $this->belongsTo(produk::class, 'produk_id');
    }
    public function usaha()
    {
        return $this->hasOne(usaha::class, 'usaha_id');
    }
    public function cart()
    {
        return $this->belongsTo(cart::class, 'cart_id');
    }
}
