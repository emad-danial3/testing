<?php

namespace App\Http\Repositories;

interface SharePageCategoryRepository
{
    public function getAllData($inputData);
    public function updateData($conditions , $updatedData);
}
