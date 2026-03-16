<?php

namespace App\Http\Requests\News;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('news', 'slug')->ignore($this->route('news'))],
            'body' => ['required', 'string'],
            'date' => ['required', 'date'],
            'image' => ['nullable', 'image', 'max:5120'],
            'link' => ['nullable', 'url', 'max:255'],
        ];
    }
}
