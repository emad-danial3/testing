<?php

namespace App\Http\Services;

use App\Http\Repositories\NettingJoinRepository;

class NettingJoinService extends BaseServiceController
{

    private  $NettingJoinRepository;

    public function __construct(NettingJoinRepository  $NettingJoinRepository)
    {
        $this->NettingJoinRepository = $NettingJoinRepository;
    }

    public function getAll($inputData)
    {
        return $this->NettingJoinRepository->getAllData($inputData);
    }
}
