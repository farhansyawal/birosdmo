<?php

// app/Models/PengajuanLayanan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanLayanan extends Model
{
    protected $fillable = ['user_id', 'layanan_id', 'data_umum', 'data_layanan', 'status', 'progress'];

    protected $casts = [
        'data_umum' => 'array',
        'data_layanan' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}
