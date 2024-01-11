<?php

namespace App\Http\Requests\AccountTypes;

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
            "name_ar"=>'required',
            "name_en"=>'required',
            "min_required"=>'required|numeric',
            "amount"=>'required|numeric',
            "delivery_fees"=>'required|numeric',
            "level_one"=>'required|numeric',
            "level_two"=>'required|numeric',
            "level_three"=>'required|numeric',
        ];
    }
}
