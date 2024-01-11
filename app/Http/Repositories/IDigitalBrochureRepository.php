<?php

namespace App\Http\Repositories;

use App\Models\DigitalBrochure;

class IDigitalBrochureRepository extends BaseRepository implements DigitalBrochureRepository
{
    public function __construct(DigitalBrochure $model){
        parent::__construct($model);
    }

    public function getAllDigitalBrochure($inputData)
    {
        $country = DigitalBrochure::orderBy('id','asc');
        if (isset($inputData['title']))
        {
            $country->where('title','like','%'.$inputData['title'].'%');
        }
        return  $country->paginate($this->defaultLimit);
    }

    public function updateDigitalBrochure($conditions, $updatedData)
    {
       return DigitalBrochure::where($conditions)->update($updatedData);
    }
}
