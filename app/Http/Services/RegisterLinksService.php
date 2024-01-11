<?php

namespace App\Http\Services;

use App\Http\Repositories\RegisterLinksRepository;

class RegisterLinksService extends BaseServiceController
{
    private $RegisterLinksRepository;

    public function __construct(RegisterLinksRepository $RegisterLinksRepository)
    {
        $this->RegisterLinksRepository = $RegisterLinksRepository;
    }

    public function getOrCreateMyLink($user_id, $is_free)
    {
        if ($is_free) {
            $links = $this->RegisterLinksRepository->getMyLinks($user_id,1) ;
            if (empty($links)) {
                $this->RegisterLinksRepository->createLink($user_id,1) ;
                $links = $this->RegisterLinksRepository->getMyLinks($user_id,1) ;
            }
//            return $this->RegisterLinksRepository->getMyLinks($user_id,1) ;

        }

        else {
               $links = $this->RegisterLinksRepository->getMyLinks($user_id,0) ;
              if (empty($links)) {
                  $this->RegisterLinksRepository->createLink($user_id,0) ;
                  $links = $this->RegisterLinksRepository->getMyLinks($user_id,0) ;
               }

        }
        return  $links;
    }

    public function getAll($inputData)
    {
        return $this->RegisterLinksRepository->getAllData($inputData);
    }

    public function deleteLinks($userIdArray)
    {
        return $this->RegisterLinksRepository->deleteLinks($userIdArray);
    }

    public function GenerateLinks($inputData)
    {
        $counter = $inputData['number'];
        for ($i = 0; $i < $counter ; $i++)
        {
            $this->RegisterLinksRepository->createLink($inputData['user_id'],1);
        }
    }
}
