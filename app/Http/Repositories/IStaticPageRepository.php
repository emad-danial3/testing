<?php

namespace App\Http\Repositories;

use App\Models\StaticPages;
use Illuminate\Database\Eloquent\Model;

class IStaticPageRepository extends BaseRepository implements  StaticPageRepository
{
    public function __construct(StaticPages $model)
    {
        parent::__construct($model);
    }


    public function updateData($conditions, $updatedData)
    {
        return StaticPages::where($conditions)->update($updatedData);
    }
}
