<?php

namespace App\Http\Requests\UserPrefrences;

use App\DataObject\NewsSourceData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserPrefrenceRequest extends FormRequest
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
            "news_type" => [
                'nullable',
                'string',
            ],
            "news_source" => [
                'nullable',
                'string',
                Rule::in(array_values(NewsSourceData::getConstants())),
            ]
        ];
    }
}
