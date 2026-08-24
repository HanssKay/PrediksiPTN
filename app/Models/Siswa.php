<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    // Explicitly define the table name since the migration is named 'siswa' (singular)
    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nisn',
        'asal_sekolah',
        'jenis_kelamin',
        'tanggal_lahir',
        'kelas',
        'tahun_ajaran',
    ];

    /**
     * Relation to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
