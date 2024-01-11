<?php

namespace App\Http\Repositories;

interface WelcomeProgramRepository
{
    public function updateData($conditions , $updatedData);
    public function createProgramProduct($products);
    public function deleteProduct($id);
}
