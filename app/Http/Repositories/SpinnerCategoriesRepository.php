<?php

namespace App\Http\Repositories;

interface SpinnerCategoriesRepository
{
    public function updateData($conditions , $updatedData);
}
