<?php

namespace App\Http\Requests\News;

use App\Models\News;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', Rule::in(News::availableStatuses())],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:news,slug'],
            'body' => ['required', 'string'],
            'date' => ['required', 'date'],
            'image' => ['nullable', 'image', 'max:5120'],
            'link' => ['nullable', 'string', 'max:255', 'regex:/^(https?:\/\/|mailto:|\/|#).+/i'],
            'link_new_window' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'link.regex' => 'The link must be a full URL (https://...) or a relative link starting with /, # or mailto:.',
        ];
    }
}
