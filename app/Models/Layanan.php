<?php

// app/Models/Layanan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $fillable = ['nama', 'deskripsi', 'is_active'];

    public function fields()
    {
        return $this->hasMany(FormInput::class, 'layanan_id');
    }
}
