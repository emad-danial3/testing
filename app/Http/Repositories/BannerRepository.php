<?php

namespace App\Http\Repositories;

interface BannerRepository
{
    public function getAllBanners($inputData);
    public function updateBanner($conditions, $updatedData);
}
