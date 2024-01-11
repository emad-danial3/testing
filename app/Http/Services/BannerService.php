<?php

namespace App\Http\Services;

use App\Http\Repositories\BannerRepository;
use App\Libraries\UploadImagesController;

class BannerService extends BaseServiceController
{
    private  $BannerRepository;
    private  $MediaController;

    public function __construct(BannerRepository  $BannerRepository, UploadImagesController $MediaController)
    {
        $this->BannerRepository = $BannerRepository;
        $this->MediaController  = $MediaController;
    }

    public function getAllBanners($inputData)
    {
        return $this->BannerRepository->getAllBanners($inputData);
    }

    public function updateBannerRow($updatedData , $id)
    {
        if (isset($updatedData['url'])){
            $updatedData['url'] = $this->MediaController->UploadImage($updatedData['url'] ,'images/banners');
            $updatedData['url']=$this->MediaController->imageName;
        }

        return  $this->BannerRepository->updateBanner(['id' => $id] ,$updatedData);
    }

    public function createBannerRow($inputData)
    {
        $inputData['url'] = $this->MediaController->UploadImage($inputData['url'] ,'images/banners');
        $inputData['url']=$this->MediaController->imageName;
        return  $this->BannerRepository->create($inputData);
    }
}
