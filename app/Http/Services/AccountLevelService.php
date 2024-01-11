<?php

namespace App\Http\Services;

use App\Http\Repositories\AccountLevelRepository;
use App\Http\Repositories\UserRepository;

class AccountLevelService extends BaseServiceController
{

    private $AccountLevelRepository;
    private $UserRepository;

    public function __construct(AccountLevelRepository $AccountLevelRepository, UserRepository $UserRepository)
    {
      $this->AccountLevelRepository = $AccountLevelRepository;
      $this->UserRepository            = $UserRepository;
    }

    public function createLevels($child_id , $parent_id): int
    {
        $level = 1 ;
        $this->AccountLevelRepository->create([
            'parent_id' => $parent_id,
            'child_id' => $child_id,
            'level'   => $level
        ]);
        $parents_of_parent = $this->UserRepository->getAccountParent($parent_id);

        foreach ( $parents_of_parent as  $parent) {
            $level ++;
            $this->AccountLevelRepository->create([
                'parent_id' => $parent->parent_id,
                'child_id' => $child_id,
                'level'   =>  $level
            ]);
        }
        return 1;
    }

    public function getAccountTracking($user_id)
    {
        return $this->AccountLevelRepository->getAccountTracking($user_id);
    }
}
