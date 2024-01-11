<?php

namespace App\Http\Repositories;

interface StaticPageRepository
{
    public function updateData($conditions , $updatedData);
}
