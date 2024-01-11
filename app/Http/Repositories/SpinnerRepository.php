<?php

namespace App\Http\Repositories;

interface SpinnerRepository
{
    public function getGift($inputData,$is_free_user);
    public function hasGift($user_id,$created_for_user_id);
    public function updateData($conditions , $updatedData);
}
