<?php

namespace App\Http\Services;

use App\Http\Repositories\DigitalBrochureRepository;

class DigitalBrochureService extends BaseServiceController
{

    private  $DigitalBrochureRepository;

    public function __construct(DigitalBrochureRepository  $DigitalBrochureRepository)
    {
        $this->DigitalBrochureRepository = $DigitalBrochureRepository;
    }

    public function getAllDigitalBrochure($inputData)
    {
       return $this->DigitalBrochureRepository->getAllDigitalBrochure($inputData);
    }

    public function updateDigitalBrochureRow($updatedData , $id)
    {
        return  $this->DigitalBrochureRepository->updateDigitalBrochure(['id' => $id] ,$updatedData);
    }

    public function createDigitalBrochureRow($inputData)
    {
        return  $this->DigitalBrochureRepository->create($inputData);
    }

    public function getAllCountriesWithOutPagination()
    {
        return $this->CountryRepository->getAll(['id','name_en','name_ar']);
    }
}
