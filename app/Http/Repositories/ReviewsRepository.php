<?php

namespace App\Http\Repositories;

interface ReviewsRepository
{
    public function updateData($conditions , $updatedData);
}
