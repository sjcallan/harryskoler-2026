<?php

namespace App\Http\Requests\Press;

use Illuminate\Foundation\Http\FormRequest;

class StorePressEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:press_events,slug'],
            'publication' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['nullable', 'date'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:10240'],
            'captions' => ['nullable', 'array'],
            'captions.*' => ['nullable', 'string', 'max:255'],
        ];
    }
}
