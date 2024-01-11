<?php

namespace App\Http\Repositories;

use App\Models\NettingJoin;
use Illuminate\Database\Eloquent\Model;

class INettingJoinRepository extends BaseRepository implements NettingJoinRepository
{
    public function __construct(NettingJoin $model)
    {
        parent::__construct($model);
    }

    public function getAllData($inputData)
    {
        $data = NettingJoin::orderBy('id','desc');
        if (isset($inputData['name']))
        {
            $data->where('name','like','%'.$inputData['name'].'%');
        }
        return  $data->paginate($this->defaultLimit);
    }
}
