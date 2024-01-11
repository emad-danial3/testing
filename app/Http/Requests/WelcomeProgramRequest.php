<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WelcomeProgramRequest extends FormRequest
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
            "image"                           => "required|image|mimes:jpeg,png,jpg,svg",
            "product_ids"                     => "required|array|min:1",
            "product_ids.*"                   => "required|string|distinct|min:1",
            "product_quantitys"               => "required|array|min:1",
            "product_quantitys.*"             => "required|string|min:1",
            "product_discounts"               => "required|array|min:1",
            "product_discounts.*"             => "required|string|min:1",
            "product_prices"                  => "required|array|min:1",
            "product_prices.*"                => "required|string|min:1",
            "product_prices_after_discount"   => "required|array|min:1",
            "product_prices_after_discount.*" => "required|string|min:1",
        ];
    }
}
