<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterUniversitas extends Model
{
    use HasFactory;

    protected $table = 'master_universitas';
    protected $guarded = ['id'];

    public function prodis()
    {
        return $this->hasMany(MasterProdi::class, 'universitas_id', 'id');
    }
}
