<?php

namespace App\Http\Repositories;

use App\Models\SpinnerCategory;

class ISpinnerCategoriesRepository extends BaseRepository implements SpinnerCategoriesRepository
{
    public function __construct(SpinnerCategory $model)
    {
        parent::__construct($model);
    }


    public function updateData($conditions, $updatedData)
    {
        return SpinnerCategory::where($conditions)->update($updatedData);
    }
}
