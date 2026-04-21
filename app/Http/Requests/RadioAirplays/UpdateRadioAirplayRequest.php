<?php

namespace App\Http\Requests\RadioAirplays;

use App\Models\RadioAirplay;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRadioAirplayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', Rule::in(RadioAirplay::availableStatuses())],
            'rank' => ['required', 'integer', 'min:1'],
            'chart' => ['required', 'string', 'max:255'],
            'detail' => ['nullable', 'string', 'max:500'],
            'link' => ['nullable', 'url', 'max:500'],
            'image' => ['nullable', 'image', 'max:10240'],
            'album_slug' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'remove_image' => ['nullable', 'string'],
        ];
    }
}
