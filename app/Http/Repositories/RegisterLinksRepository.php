<?php

namespace App\Http\Repositories;

interface RegisterLinksRepository
{

    public function getMyLinks($user_id ,$is_free_link);
    public function createLink($user_id, $is_free_link);
    public function getAllData($inputData);
    public function deleteLinks($userIdArray);
}
