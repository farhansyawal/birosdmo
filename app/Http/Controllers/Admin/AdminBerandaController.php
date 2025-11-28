<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BerandaSetting;
use Illuminate\Support\Facades\Storage;

class AdminBerandaController extends Controller
{
    public function edit()
    {
        $data = BerandaSetting::first();

        if (!$data) {
            $data = BerandaSetting::create([
                'hero_title' => '',
                'hero_subtitle' => '',
                'hero_bigtext' => '',
                'slider_items' => json_encode([]),
                'about_title' => '',
                'about_description' => '',
                'survey_image' => null,
                'item_image_1' => null,
                'item_image_2' => null,
                'item_image_3' => null,
            ]);
        }

        // decode slider items agar selalu array
        $data->slider_items = json_decode($data->slider_items, true) ?: [];

        return view('admin.beranda.edit', compact('data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:255',
            'hero_bigtext' => 'nullable|string|max:255',
            'about_title' => 'nullable|string|max:255',
            'about_description' => 'nullable|string',

            'slider_files.*' => 'nullable|image|max:2048',
            'slider_links.*' => 'nullable|string|max:255',
            'slider_images.*' => 'nullable|string',

            'item_image_1' => 'nullable|image|max:2048',
            'item_image_2' => 'nullable|image|max:2048',
            'item_image_3' => 'nullable|image|max:2048',
            'survey_image' => 'nullable|image|max:2048',
        ]);

        $model = BerandaSetting::first();

        // update teks
        $model->hero_title = $request->hero_title;
        $model->hero_subtitle = $request->hero_subtitle;
        $model->hero_bigtext = $request->hero_bigtext;
        $model->about_title = $request->about_title;
        $model->about_description = $request->about_description;

        // ======================
        // Slider
        // ======================
        $sliderLinks = $request->slider_links ?? [];
        $sliderExisting = $request->slider_images ?? [];
        $sliderFiles = $request->file('slider_files') ?? [];

        $newSlider = [];
        $count = max(count($sliderLinks), count($sliderExisting), count($sliderFiles));

        for ($i = 0; $i < $count; $i++) {
            $link = $sliderLinks[$i] ?? '#';
            $existing = $sliderExisting[$i] ?? null;
            $file = $sliderFiles[$i] ?? null;

            if ($file) {
                if ($existing) Storage::disk('public')->delete($existing);
                $img = $file->store('beranda', 'public');
            } else {
                $img = $existing;
            }

            if (!$img) continue;

            $newSlider[] = [
                'image' => $img,
                'link' => $link ?: '#'
            ];
        }

        $model->slider_items = json_encode($newSlider);

        // ======================
        // Tiga gambar item
        // ======================
        for ($n = 1; $n <= 3; $n++) {
            $fileName = "item_image_$n";
            if ($request->hasFile($fileName)) {
                if ($model->$fileName) Storage::disk('public')->delete($model->$fileName);
                $model->$fileName = $request->file($fileName)->store('beranda', 'public');
            }
        }

        // ======================
        // Survey image
        // ======================
        if ($request->hasFile('survey_image')) {
            if ($model->survey_image) Storage::disk('public')->delete($model->survey_image);
            $model->survey_image = $request->file('survey_image')->store('beranda', 'public');
        }

        $model->save();

        return redirect()->route('admin.beranda.edit')->with('success', 'Beranda berhasil diperbarui!');
    }
}
