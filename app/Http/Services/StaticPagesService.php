<?php

namespace App\Http\Services;

use App\Http\Repositories\StaticPageRepository;

class StaticPagesService extends BaseServiceController
{
    private $StaticPageRepository;

    public function __construct(StaticPageRepository  $StaticPageRepository)
    {
        $this->StaticPageRepository = $StaticPageRepository;
    }

    public function getAll()
    {
        return $this->StaticPageRepository->getAll(['*']);
    }

    public function updateRow($updatedData , $id)
    {
        return  $this->StaticPageRepository->updateData(['id' => $id] ,$updatedData);
    }

    public function createRow($inputData)
    {
        return  $this->StaticPageRepository->create($inputData);
    }
}
