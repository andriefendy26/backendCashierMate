<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

use App\Models\usaha;
use App\Models\roles;

class users extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'users';
    protected $fillable = ['nama', 'email', 'password', 'usaha_id', 'role_id', 'gambar'];
    protected $hidden = [
        'password',
        // 'remember_token',
    ];

    public $timestamps = false;

    public function usaha()
    {
        return $this->belongsTo(usaha::class, 'usaha_id');
    }
    public function roles()
    {
        return $this->belongsTo(roles::class, 'role_id');
    }
}
