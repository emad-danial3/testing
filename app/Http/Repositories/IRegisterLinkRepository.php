<?php

namespace App\Http\Repositories;

use App\Models\RegisterLink;

class IRegisterLinkRepository extends BaseRepository implements RegisterLinkRepository
{
    public function __construct(RegisterLink $model)
    {
        parent::__construct($model);
    }

    public function insertData($data)
    {
       return RegisterLink::create($data);
    }
}
