<?php

namespace App\Http\Repositories;

use App\Models\Banner;

class IBannerRepositories extends BaseRepository implements  BannerRepository
{

    public function __construct(Banner $model)
    {
        parent::__construct($model);
    }


    public function getAllBanners($inputData)
    {
        $country = Banner::orderBy('priority','asc')->orderBy('id','desc')->with('category');
        if (isset($inputData['name']))
        {
            $country->where('title_en','like','%'.$inputData['name'].'%');
        }
        return  $country->paginate($this->defaultLimit);
    }

    public function updateBanner($conditions, $updatedData)
    {
        return Banner::where($conditions)->update($updatedData);
    }
}
