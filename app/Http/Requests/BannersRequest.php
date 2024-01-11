<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "url"      => "image|mimes:jpeg,png,jpg|max:2048",
            "priority" => "required",
            "title_ar" => "nullable",
            "title_en" => "nullable",
            "description_ar" => "nullable",
            "description_en" => "nullable",
            "button_ar" => "nullable",
            "button_en" => "nullable",
            "button_url" => "nullable",
        ];
    }
}
