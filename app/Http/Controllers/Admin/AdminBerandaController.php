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
                // Initialize as empty array, NOT json_encoded string
                'slider_items' => [],
                'about_title' => '',
                'about_description' => '',
                'survey_image' => null,
                'item_image_1' => null,
                'item_image_2' => null,
                'item_image_3' => null,
            ]);
        }

        // REMOVED: manual json_decode.
        // With 'casts' in model, $data->slider_items is ALREADY an array.
        // We just ensure it's not null.
        if (is_null($data->slider_items)) {
            $data->slider_items = [];
        }

        return view('admin.beranda.edit', compact('data'));
    }

    public function update(Request $request)
    {
        // Validation adjusted for the new array input structure
        $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:255',
            'hero_bigtext' => 'nullable|string|max:255',
            'about_title' => 'nullable|string|max:255',
            'about_description' => 'nullable|string',

            // New Array Validation syntax: sliders.*.field
            'sliders.*.file' => 'nullable|image|max:2048',
            'sliders.*.link' => 'nullable|string|max:255',
            'sliders.*.image' => 'nullable|string',

            'item_image_1' => 'nullable|image|max:2048',
            'item_image_2' => 'nullable|image|max:2048',
            'item_image_3' => 'nullable|image|max:2048',
            'survey_image' => 'nullable|image|max:2048',
        ]);

        $model = BerandaSetting::first();

        // 1. Update Standard Text Fields
        $model->hero_title = $request->hero_title;
        $model->hero_subtitle = $request->hero_subtitle;
        $model->hero_bigtext = $request->hero_bigtext;
        $model->about_title = $request->about_title;
        $model->about_description = $request->about_description;

        // ======================
        // 2. SLIDER LOGIC (CORRECTED)
        // ======================

        // Retrieve the 'sliders' array from the form inputs
        // This comes from name="sliders[UNIQUE_ID][...]"
        $inputs = $request->input('sliders', []);

        $cleanSliders = [];

        foreach ($inputs as $uniqueId => $data) {
            $link = $data['link'] ?? '#';
            $existingPath = $data['image'] ?? null;
            $finalImage = $existingPath;

            // Check if a NEW file was uploaded for this specific slider ID
            // Using dot notation: sliders.ID.file
            if ($request->hasFile("sliders.{$uniqueId}.file")) {
                $file = $request->file("sliders.{$uniqueId}.file");

                // Delete old image if it exists and we are replacing it
                if ($existingPath) {
                    Storage::disk('public')->delete($existingPath);
                }

                // Store the new image
                $finalImage = $file->store('beranda', 'public');
            }

            // Only add to the final array if we have an image path (either old or new)
            if ($finalImage) {
                $cleanSliders[] = [
                    'image' => $finalImage,
                    'link' => $link
                ];
            }
        }

        // CRITICAL FIX:
        // 1. Use array_values() to reset indices (0, 1, 2...)
        // 2. REMOVED json_encode(). Since your model has 'casts' => 'array',
        //    assigning a PHP array here will automatically save as valid JSON.
        $model->slider_items = array_values($cleanSliders);


        // ======================
        // 3. STATIC IMAGES (Item 1, 2, 3)
        // ======================
        for ($n = 1; $n <= 3; $n++) {
            $fileName = "item_image_$n";
            if ($request->hasFile($fileName)) {
                if ($model->$fileName)
                    Storage::disk('public')->delete($model->$fileName);
                $model->$fileName = $request->file($fileName)->store('beranda', 'public');
            }
        }

        // ======================
        // 4. SURVEY IMAGE
        // ======================
        if ($request->hasFile('survey_image')) {
            if ($model->survey_image)
                Storage::disk('public')->delete($model->survey_image);
            $model->survey_image = $request->file('survey_image')->store('beranda', 'public');
        }

        $model->save();

        return redirect()->route('admin.beranda.edit')->with('success', 'Beranda berhasil diperbarui!');
    }
}