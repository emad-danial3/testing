<?php

namespace App\Http\Repositories;

use App\Models\WelcomeProgramProduct;
use App\Models\WelcomeProgramProductDetails;
//use DeepCopy\Filter\Filter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IWelcomeProgramRepository extends BaseRepository implements  WelcomeProgramRepository
{
    public function __construct(WelcomeProgramProduct $model)
    {
        parent::__construct($model);
    }

    public function updateData($conditions, $updatedData)
    {
        return WelcomeProgramProduct::where($conditions)->update($updatedData);
    }
    public function createProgramProduct($programs)
    {
        return WelcomeProgramProductDetails::create($programs);
    }
    public function deleteProduct($id)
    {
        return WelcomeProgramProductDetails::find($id)->delete();
    }

}
