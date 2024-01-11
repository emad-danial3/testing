<?php

namespace App\Http\Repositories;

interface AcceptedVersionRepository
{
    public function checkVersion($platform , $version);
}
