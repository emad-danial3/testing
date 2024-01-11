<?php

namespace App\Http\Services;

use App\Http\Repositories\FilterRepository;

class FilterService extends BaseServiceController
{
    private $FilterRepository;

    public function __construct(FilterRepository  $FilterRepository)
    {
        $this->FilterRepository = $FilterRepository;
    }

    public function getAll()
    {
        return $this->FilterRepository->getAll(['*']);
    }

    public function updateRow($updatedData , $id)
    {
        return  $this->FilterRepository->updateData(['id' => $id] ,$updatedData);
    }

    public function createRow($inputData)
    {
        return  $this->FilterRepository->create($inputData);
    }
}
