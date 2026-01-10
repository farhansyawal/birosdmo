<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BerandaSetting extends Model
{
protected $fillable = [
    'hero_title',
    'hero_subtitle',
    'hero_bigtext',

    // HAPUS hero_background, karena sudah tidak ada
    // 'hero_background',

    'slider_items',
    'about_title',
    'about_description',

    // Tambahan tiga gambar item
    'item_image_1',
    'item_image_2',
    'item_image_3',

    'survey_image'
];

protected $casts = [
    'slider_items' => 'array',
];
}
