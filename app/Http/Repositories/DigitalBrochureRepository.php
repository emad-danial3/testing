<?php

namespace App\Http\Repositories;

interface DigitalBrochureRepository
{
    public function  getAllDigitalBrochure($inputData);
    public function  updateDigitalBrochure($conditions , $updatedData);
}
