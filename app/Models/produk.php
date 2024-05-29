<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\usaha;
use App\Models\kategori;

class produk extends Model
{
    use HasFactory;
    protected $table = 'produk';
    protected $fillable = ['nama', 'harga', 'gambar', 'qty', 'kategori_id', 'usaha_id'];
    public $timestamps = false;

    public function kategori()
    {
        return $this->belongsTo(kategori::class, 'kategori_id');
    }
    public function usaha()
    {
        return $this->belongsTo(usaha::class, 'usaha_id');
    }
}
