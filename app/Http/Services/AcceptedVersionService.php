<?php

namespace App\Http\Services;

use App\Http\Repositories\AcceptedVersionRepository;

class AcceptedVersionService extends BaseServiceController
{

    public $AcceptedVersionRepository;

    public function __construct(AcceptedVersionRepository $AcceptedVersionRepository)
    {
        $this->AcceptedVersionRepository = $AcceptedVersionRepository;
    }

    public function checkVersion($platform , $version)
    {
        return $this->AcceptedVersionRepository->checkVersion($platform , $version);
    }

}
