<?php

namespace App\Http\Repositories;

interface SharePageRepository
{
    public function getAllData($inputData);
    public function updateData($conditions , $updatedData);
}
