<?php

namespace App\Http\Repositories;

use App\Models\WalletEvaluation;

class IWalletEvaluationRepository extends BaseRepository implements WalletEvaluationRepository
{
    public function __construct(WalletEvaluation $model)
    {
        parent::__construct($model);
    }
}
