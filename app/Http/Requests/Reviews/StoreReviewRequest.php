<?php

namespace App\Http\Requests\Reviews;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'excerpt' => ['required', 'string', 'max:1000'],
            'body' => ['required', 'string'],
            'author' => ['nullable', 'string', 'max:255'],
            'publication' => ['required', 'string', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
