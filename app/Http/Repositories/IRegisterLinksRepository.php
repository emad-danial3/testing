<?php

namespace App\Http\Repositories;

use App\Constants\RegisterLinkRoute;
use App\Models\RegisterLink;
use Illuminate\Support\Str;

class IRegisterLinksRepository extends BaseRepository implements RegisterLinksRepository
{

    public function __construct(RegisterLink $model)
    {
        parent::__construct($model);
    }


    public function getMyLinks($user_id, $is_free_link)
    {
        return RegisterLink::select('link')->where([
            'user_id' => $user_id,
            'is_free_link' => $is_free_link,
            'is_used' => 0
        ])->first();
    }

    public function createLink($user_id,$is_free_link)
    {
        $url ='';
        if ($is_free_link)
        {
            $url = RegisterLinkRoute::FreeLink;
        }
        else{
            $url = RegisterLinkRoute::PRIMUMLink;
        }
        $token = Str::random(60);
        $full_url = $url .'/'.$user_id.'/'.$token;

      return  RegisterLink::create([
            'link'         => $full_url,
            'user_id'      => $user_id,
            'is_free_link' => $is_free_link
        ]);
    }


    public function getAllData($inputData)
    {
        $data = RegisterLink::select('register_links.id','register_links.link','users.full_name')->orderBy('id','desc')->where([
            'is_used' => 0,
        ])->join('users','users.id','register_links.user_id');
        return  $data->paginate($this->defaultLimit);
    }

    public function deleteLinks($userIdArray)
    {
      return  RegisterLink::destroy($userIdArray);
    }
}
