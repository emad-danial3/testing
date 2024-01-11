<?php

namespace App\Http\Services;

use App\Http\Repositories\WelcomeProgramRepository;
use App\Libraries\UploadImagesController;

class WelcomeProgramService extends BaseServiceController
{
    private $WelcomeProgramRepository;
    private $MediaController;

    public function __construct(WelcomeProgramRepository $WelcomeProgramRepository, UploadImagesController $MediaController)
    {
        $this->WelcomeProgramRepository = $WelcomeProgramRepository;
        $this->MediaController          = $MediaController;
    }

    public function getAll()
    {
        return $this->WelcomeProgramRepository->getAll(['*'], [['id', '>', '0']], ['product', 'product.product']);
    }

    public function updateRow($updatedData, $id)
    {

        if (isset($updatedData['image'])){
            $updatedData['image'] = $this->MediaController->UploadImage($updatedData['image'], 'images/programs');
            $updatedData['image'] = $this->MediaController->imageName;
        }
        return $this->WelcomeProgramRepository->updateData(['id' => $id], $updatedData);
    }

    public function createRow($inputData)
    {
        $inputData['image'] = $this->MediaController->UploadImage($inputData['image'], 'images/programs');
        $inputData['image'] = $this->MediaController->imageName;
        return $this->WelcomeProgramRepository->create($inputData);
    }

    public function createProgramProduct($inputData)
    {
        return $this->WelcomeProgramRepository->createProgramProduct($inputData);
    }

    public function deleteProduct($id)
    {
        return $this->WelcomeProgramRepository->deleteProduct($id);
    }

}
