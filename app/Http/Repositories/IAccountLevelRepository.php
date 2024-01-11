<?php

namespace App\Http\Repositories;

use App\Models\AccountLevel;

class IAccountLevelRepository extends BaseRepository implements AccountLevelRepository
{
    public function __construct(AccountLevel $model){
        parent::__construct($model);
    }

    public function getAccountTracking($user_id)
    {


        return AccountLevel::where([
            'account_levels.parent_id'=>$user_id,
            'users.is_active' => 1
        ])
        ->join('users', 'users.id', '=', 'account_levels.child_id')
        ->join('account_types', 'account_types.id', '=', 'users.account_type')
        ->where('account_levels.level','<',4)
        ->select('users.full_name','users.account_id','account_types.name_en as name','users.created_at','account_levels.level')
        ->get();

    }
    public function getAllData($inputData)
    {

        $data = AccountLevel::orderBy('account_levels.updated_at','desc');
        if (isset($inputData['name']))
        {
            $data->where('account_levels.parent_id',$inputData['name'])->orWhere('account_levels.child_id',$inputData['name']);
        }

        if ( (isset($inputData['email']) && $inputData['email']!='') && (isset($inputData['user_name']) && $inputData['user_name']!=''))
        {
            $data->join('users','account_levels.parent_id' ,'users.id')
                ->where('users.email','like','%'.$inputData['email'].'%')->where('users.full_name','like','%'.$inputData['user_name'].'%')->select('account_levels.*');
        }
        if ( (isset($inputData['email']) && $inputData['email']!='') && !(isset($inputData['user_name']) && $inputData['user_name']!=''))
        {
                $data->join('users','account_levels.parent_id' ,'users.id')
                    ->where('users.email','like','%'.$inputData['email'].'%')->select('account_levels.*');
        }
        if ( !(isset($inputData['email']) && $inputData['email']!='') && (isset($inputData['user_name']) && $inputData['user_name']!=''))
        {
                $data->join('users','account_levels.parent_id' ,'users.id')
                    ->where('users.full_name','like','%'.$inputData['user_name'].'%')->select('account_levels.*');
        }
        if ( (isset($inputData['child_name']) && $inputData['child_name']!=''))
        {
                $data->join('users','account_levels.child_id' ,'users.id')
                    ->where('users.full_name','like','%'.$inputData['child_name'].'%')->select('account_levels.*');
        }

        return  $data->paginate($this->defaultLimit);
    }
}
