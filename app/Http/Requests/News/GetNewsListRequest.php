<?php

namespace App\Http\Requests\News;

use Illuminate\Foundation\Http\FormRequest;

class GetNewsListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'nullable',
                'string'
            ],
            'type' => [
                'nullable',
                'string'
            ],
            'published_at_from' => [
                'nullable',
                'date',
            ],
            'published_at_to' => [
                'nullable',
                'date',
            ],
        ];
    }
}
