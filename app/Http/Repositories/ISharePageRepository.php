<?php

namespace App\Http\Repositories;

use App\Models\SharePage;
use Illuminate\Database\Eloquent\Model;

class ISharePageRepository extends BaseRepository implements SharePageRepository
{
    public function __construct(SharePage $model)
    {
        parent::__construct($model);
    }

    public function getAllData($inputData)
    {
        $data = SharePage::orderBy('id','asc');
        if (isset($inputData['name']))
        {
            $data->where('title_en','like','%'.$inputData['name'].'%');
        }
        return  $data->paginate($this->defaultLimit);
    }

    public function updateData($conditions, $updatedData)
    {
        return SharePage::where($conditions)->update($updatedData);
    }
}
