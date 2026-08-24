<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class C45Rule extends Model
{
    use HasFactory;

    protected $table = 'c45_rules';
    
    protected $fillable = [
        'rule_string',
        'hasil_prediksi',
        'batch_training'
    ];
}