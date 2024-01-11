<?php

namespace App\Http\Services;

use App\Constants\OrderTypes;
use App\Http\Repositories\CartRepository;
use App\Http\Repositories\CommissionRepository;
use App\Http\Services\OrderTypesCommissions\AbstractCommission;
use App\Http\Services\OrderTypesCommissions\CreateFreeUserCommission;
use App\Http\Services\OrderTypesCommissions\CreateSingleUserCommission;
use App\Http\Services\OrderTypesCommissions\CreateUserCommission;
use App\Models\AccountLevel;

class CommissionService extends BaseServiceController
{

    private  $AbstractCommission;
    private  $CommissionRepository;
    private $UserService;

    public function __construct(UserService $UserService,AbstractCommission  $AbstractCommission,CommissionRepository $CommissionRepository)
    {
       $this->AbstractCommission = $AbstractCommission;
       $this->CommissionRepository = $CommissionRepository;
       $this->UserService = $UserService;
    }
    public function createCommission($orderHeader)
    {
            $myPreviousQuarterCommission=$this->myCurrentCommission($orderHeader['user_id'],'quarter');
            $myPreviousYearCommission=$this->myCurrentCommission($orderHeader['user_id'],'year');
            $mytotalPaidOrderThisQuarter = $this->UserService->getMyTeamTotalSales([$orderHeader['user_id']],null,null,'quarter');
            $mytotalPaidOrderThisYear    = $this->UserService->getMyTeamTotalSales([$orderHeader['user_id']],null,null,'year');

          if($myPreviousQuarterCommission < 400 ){
            $current=$mytotalPaidOrderThisQuarter + (float)$orderHeader['total_order_has_commission'];
            if($current >= 4000){
                if($myPreviousQuarterCommission < 200){
                    $data=[
                    "user_id"=>$orderHeader['user_id'],
                    "order_id"=>$orderHeader['id'],
                    "commission"=>400,
                    "commission_type"=>'quarter',
                    ];
                }else{
                     $data=[
                    "user_id"=>$orderHeader['user_id'],
                    "order_id"=>$orderHeader['id'],
                    "commission"=>200,
                    "commission_type"=>'quarter',
                    ];
                }
                $this->AbstractCommission = new CreateUserCommission();
                $this->AbstractCommission->createCommission($data);
            }elseif ($current >= 2000){
                  if($myPreviousQuarterCommission < 200){
                    $data=[
                    "user_id"=>$orderHeader['user_id'],
                    "order_id"=>$orderHeader['id'],
                    "commission"=>200,
                    "commission_type"=>'quarter',
                    ];
                    $this->AbstractCommission = new CreateUserCommission();
                $this->AbstractCommission->createCommission($data);
                }
            }
          }

          if($myPreviousYearCommission < 1000 ){
            $current=$mytotalPaidOrderThisYear + (float)$orderHeader['total_order_has_commission'];
            if($current >= 20000){
                if($myPreviousQuarterCommission < 500){
                    $data=[
                    "user_id"=>$orderHeader['user_id'],
                    "order_id"=>$orderHeader['id'],
                    "commission"=>1000,
                    "commission_type"=>'year',
                    ];
                }else{
                     $data=[
                    "user_id"=>$orderHeader['user_id'],
                    "order_id"=>$orderHeader['id'],
                    "commission"=>500,
                    "commission_type"=>'year',
                    ];
                }
                $this->AbstractCommission = new CreateUserCommission();
                $this->AbstractCommission->createCommission($data);
            }elseif ($current >= 10000){
                  if($myPreviousQuarterCommission < 500){
                    $data=[
                    "user_id"=>$orderHeader['user_id'],
                    "order_id"=>$orderHeader['id'],
                    "commission"=>500,
                    "commission_type"=>'year',
                    ];
                    $this->AbstractCommission = new CreateUserCommission();
                    $this->AbstractCommission->createCommission($data);
                }

            }
          }



    }

    public function updateCommission($whereConditions,$data)
    {
        $this->CommissionRepository->updateCommission($whereConditions,$data);
    }
    public function CreateOrUpdateMonthlyCommission($user_id,$data,$date_from,$date_to)
    {
      return  $this->CommissionRepository->CreateOrUpdateMonthlyCommission($user_id,$data,$date_from,$date_to);
    }
    public function CreateOrUpdateOrderHasMonthlyCommission($created_at,$commissionId,$order_id,$user_id,$level,$new,$value,$persintage,$total_order_has_commission)
    {
      return  $this->CommissionRepository->CreateOrUpdateOrderHasMonthlyCommission($created_at,$commissionId,$order_id,$user_id,$level,$new,$value,$persintage,$total_order_has_commission);
    }

    public function myCommission($user_id,$filterData)
    {
      return  $this->CommissionRepository->getMyCommission($user_id,$filterData);
    }
    public function myCurrentCommission($user_id,$period)
    {
      return  $this->CommissionRepository->myCurrentCommission($user_id,$period);
    }

    public function getTotalCommissionAndRedeem($user_id)
    {
      return  $this->CommissionRepository->getTotalCommissionAndRedeem($user_id);
    }

    public function getAccountTracking($user_id)
    {
        return  $this->CommissionRepository->getAccountTracking($user_id);
    }

    public function getAll($inputData)
    {
        $data= $this->CommissionRepository->getAllData($inputData);
        foreach ($data as $item){
            $item['level']=AccountLevel::where('parent_id',$item->user_id)->where('child_id',$item->commission_by)->first();
        }
        return $data;
    }
    public function getAllMonthly($inputData)
    {
        $data= $this->CommissionRepository->getAllDataMonthly($inputData);
        return $data;
    }
    public function getAllCommissionOrders($inputData)
    {
        $data= $this->CommissionRepository->getAllCommissionOrders($inputData);
        return $data;
    }
    public function getOneCommission($id)
    {
        $data= $this->CommissionRepository->getOneCommission($id);
        return $data;
    }
}
