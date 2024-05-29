<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\cart;
use App\Models\users;
use App\Models\usaha;

class transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = ['tanggal', 'metode', 'bayar', 'total', 'kembalian', 'users_id', 'usaha_id', 'cart_id'];
    public $timestamps = false;

    public function cart()
    {
        return $this->belongsTo(cart::class, 'cart_id');
    }
    public function user()
    {
        return $this->belongsTo(users::class, 'users_id');
    }
    public function usaha()
    {
        return $this->belongsTo(usaha::class, 'usaha_id');
    }
}
