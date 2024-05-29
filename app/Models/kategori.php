<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\usaha;
class kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $fillable = ['nama', 'usaha_id'];
    public $timestamps = false;
    public function usaha()
    {
        return $this->hasOne(usaha::class, 'usaha_id');
    }
}
