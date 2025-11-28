<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormInput;

class FormInputController extends Controller
{
    public function getFields($layanan_id)
    {
        $fields = FormInput::where('layanan_id', $layanan_id)
            ->orderBy('id')
            ->get(['id', 'name', 'label', 'type', 'options']);

        $fields->transform(function ($field) {
            $field->options = $field->options ? json_decode($field->options, true) : [];
            return $field;
        });

        return response()->json($fields);
    }
}
