<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WelcomeProgramRequestUpdate extends FormRequest
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
            "month"                           => "required",
            "name_ar"                         => "required",
            "name_en"                         => "required",
            "total_price"                         => "required",
            "image"                           => "nullable|image|mimes:jpeg,png,jpg,svg",
            "product_ids"                     => "nullable|array|min:1",
            "product_ids.*"                   => "nullable|string|distinct|min:1",
            "product_quantitys"               => "nullable|array|min:1",
            "product_quantitys.*"             => "nullable|string|min:1",
            "product_discounts"               => "nullable|array|min:1",
            "product_discounts.*"             => "nullable|string|min:1",
            "product_prices"                  => "nullable|array|min:1",
            "product_prices.*"                => "nullable|string|min:1",
            "product_prices_after_discount"   => "nullable|array|min:1",
            "product_prices_after_discount.*" => "nullable|string|min:1",
        ];
    }
}
