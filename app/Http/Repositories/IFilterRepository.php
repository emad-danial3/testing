<?php

namespace App\Http\Repositories;

use App\Models\Filter;
//use DeepCopy\Filter\Filter;
use Illuminate\Database\Eloquent\Model;

class IFilterRepository extends BaseRepository implements  FilterRepository
{
    public function __construct(Filter $model)
    {
        parent::__construct($model);
    }


    public function updateData($conditions, $updatedData)
    {
        return Filter::where($conditions)->update($updatedData);
    }
}
