<?php

namespace App\Http\Requests\RadioAirplays;

use Illuminate\Foundation\Http\FormRequest;

class StoreRadioAirplayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rank' => ['required', 'integer', 'min:1'],
            'chart' => ['required', 'string', 'max:255'],
            'detail' => ['nullable', 'string', 'max:500'],
            'link' => ['nullable', 'url', 'max:500'],
            'image' => ['nullable', 'image', 'max:10240'],
            'album_slug' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
