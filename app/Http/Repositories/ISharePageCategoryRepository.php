<?php

namespace App\Http\Repositories;

use App\Models\SharePageCategory;
use Illuminate\Database\Eloquent\Model;

class ISharePageCategoryRepository extends BaseRepository implements SharePageCategoryRepository
{
    public function __construct(SharePageCategory $model)
    {
        parent::__construct($model);
    }

    public function getAllData($inputData)
    {
        $data = SharePageCategory::orderBy('id','desc');
        return  $data->paginate($this->defaultLimit);
    }

    public function updateData($conditions, $updatedData)
    {
        return SharePageCategory::where($conditions)->update($updatedData);
    }
}
