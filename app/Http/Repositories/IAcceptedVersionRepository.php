<?php

namespace App\Http\Repositories;

use App\Models\AcceptedVersion;
use Illuminate\Database\Eloquent\Model;

class IAcceptedVersionRepository extends BaseRepository implements AcceptedVersionRepository
{
    public function __construct(AcceptedVersion $model)
    {
        parent::__construct($model);
    }

    public function checkVersion($platform, $version)
    {
        return AcceptedVersion::where([
            'platform' => $platform ,
            'version' => $version
        ])->first();
    }
}
