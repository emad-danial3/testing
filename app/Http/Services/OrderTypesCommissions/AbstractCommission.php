<?php

namespace App\Http\Services\OrderTypesCommissions;

interface AbstractCommission
{
    public static  function createCommission($orderHeader);
}
