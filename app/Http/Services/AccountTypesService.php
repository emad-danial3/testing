<?php

namespace App\Http\Services;

use App\Http\Repositories\AccountCommissionRepository;
use App\Http\Repositories\IAccountTypeRepository;

class AccountTypesService extends BaseServiceController
{

    private $AccountTypeRepo;
    private $AccountCommissionsRepo;
    public function __construct(IAccountTypeRepository $AccountType,AccountCommissionRepository $AccountCommission)
    {
        $this->AccountCommissionsRepo=$AccountCommission;
        $this->AccountTypeRepo=$AccountType;
    }

    public function getAll(){
        return $this->AccountTypeRepo->getAll();
    }

    public function find($id){
        return $this->AccountTypeRepo->find($id);
    }

    public function store($request){
        return $this->AccountTypeRepo->create($request);
    }
    public function update($request,$id){
        return $this->AccountTypeRepo->update($request,$id);
    }
    public function StoreTypeCommission($request){

         $this->AccountCommissionsRepo->create(['account_id'=>$request['account_id'],'level'=>1,'commission'=>$request['level_one']]);
         $this->AccountCommissionsRepo->create(['account_id'=>$request['account_id'],'level'=>2,'commission'=>$request['level_two']]);
         $this->AccountCommissionsRepo->create(['account_id'=>$request['account_id'],'level'=>3,'commission'=>$request['level_three']]);
    }
    public function UpdateTypeCommission($request,$id){

        $this->AccountCommissionsRepo->updateExist($id,1,['commission'=>$request['level_one']]);
        $this->AccountCommissionsRepo->updateExist($id,2,['commission'=>$request['level_two']]);
        $this->AccountCommissionsRepo->updateExist($id,3,['commission'=>$request['level_three']]);
    }



}
