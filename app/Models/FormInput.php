<?php

// app/Models/FormInput.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormInput extends Model
{
    protected $fillable = ['layanan_id', 'label', 'type', 'name', 'options','is_require', 'queue'];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}
