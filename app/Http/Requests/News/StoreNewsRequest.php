<?php

namespace App\Http\Requests\News;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:news,slug'],
            'body' => ['required', 'string'],
            'date' => ['required', 'date'],
            'image' => ['nullable', 'image', 'max:5120'],
            'link' => ['nullable', 'url', 'max:255'],
        ];
    }
}
