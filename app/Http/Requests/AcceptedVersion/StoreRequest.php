<?php

namespace App\Http\Requests\AcceptedVersion;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            "version" => "required",
            "platform" => "required",
            "upload_apple_version" => "required",
        ];
    }
}
