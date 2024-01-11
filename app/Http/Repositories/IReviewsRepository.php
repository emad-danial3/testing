<?php

namespace App\Http\Repositories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Model;

class IReviewsRepository extends BaseRepository implements  ReviewsRepository
{
    public function __construct(Review $model)
    {
        parent::__construct($model);
    }

    public function updateData($conditions, $updatedData)
    {
        return Review::where($conditions)->update($updatedData);
    }
}
