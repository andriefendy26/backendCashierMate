<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\usaha;

class cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = ['usaha_id'];
    public $timestamps = false;

    public function usaha()
    {
        return $this->belongsTo(usaha::class, 'usaha_id');
    }
}
