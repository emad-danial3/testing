<?php

namespace App\ValidationClasses;

class SpinnerValidation
{


    public static function getGift():array
    {
        return[
            'id'                  => 'required|exists:spinners,id',
            'spinner_category_id' => 'required|exists:spinner_categories,id',
            'created_for_user_id' => 'required|exists:users,id',
        ];
    }

    public static function  getSpinner(): array
    {
        return[
            'created_for_user_id' => 'required|exists:users,id',
        ];
    }


}
