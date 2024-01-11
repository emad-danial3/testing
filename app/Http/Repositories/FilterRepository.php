<?php

namespace App\Http\Repositories;

interface FilterRepository
{
    public function updateData($conditions , $updatedData);
}
