<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterProdi extends Model
{
    use HasFactory;

    protected $table = 'master_prodi';
    protected $guarded = ['id'];

    public function universitas()
    {
        return $this->belongsTo(MasterUniversitas::class, 'universitas_id', 'id');
    }
}
