<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddBookRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            "title" => "required|min:3|max:100",
            "author" => "required|min:3|max:50",
            "release_date" => "required|date",
            "description" => "required|min:10|max:5000",
            "photo" => 'required|mimes:jpg,png,jpeg|max:5048',
            "amount" => "required|integer",
            "format" => "required",
            "pages" => "required|integer",
            "price" => "required",
            "categories" => "required|array",
            "categories.*" => "string"
        ];
    }
}
