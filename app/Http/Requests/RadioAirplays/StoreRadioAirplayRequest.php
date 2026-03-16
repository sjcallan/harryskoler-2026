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
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
