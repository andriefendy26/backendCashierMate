<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class usaha extends Model
{
    use HasFactory;
    protected $table = 'usaha';
    // const created_at = null;
    // const updated_at = null;
    protected $fillable = ['nama', 'kategori', 'alamat'];
    public $timestamps = false;
}
