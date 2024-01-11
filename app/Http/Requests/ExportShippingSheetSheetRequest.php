<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportShippingSheetSheetRequest extends FormRequest
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
            "start_date" => "required|date",
            "end_date"   => "required|date|after_or_equal:start_date",
        ];
    }
}
