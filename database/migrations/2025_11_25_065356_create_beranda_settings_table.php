<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('beranda_settings', function (Blueprint $table) {
            $table->id();

            // HERO SECTION (TANPA BACKGROUND)
            $table->string('hero_title', 150)->nullable();
            $table->string('hero_subtitle', 255)->nullable();
            $table->string('hero_bigtext', 255)->nullable();

            // SLIDER SECTION (gambar + optional text)
            // [
            //   { "image": "slide1.jpg", "title": "A", "subtitle": "B" },
            //   { "image": "slide2.jpg", "title": "X", "subtitle": "Y" }
            // ]
            $table->json('slider_items')->nullable();

            // 3 ITEM GAMBAR BIASA (bukan slider!)
            $table->string('item_image_1')->nullable();
            $table->string('item_image_2')->nullable();
            $table->string('item_image_3')->nullable();

            // Tentang Biro
            $table->string('about_title', 150)->nullable();
            $table->text('about_description')->nullable();

            // Indeks Kepuasan
            $table->string('survey_image')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beranda_settings');
    }
};
