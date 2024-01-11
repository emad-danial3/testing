<?php

namespace App\Http\Repositories;

interface CommissionRepository
{
    public function updateCommission($conditions , $data);
    public function CreateOrUpdateMonthlyCommission($user_id , $data,$date_from,$date_to);
    public function CreateOrUpdateOrderHasMonthlyCommission($created_at,$commissionId,$order_id,$user_id,$level,$new,$value,$persintage,$total_order_has_commission);
    public function getMyCommission($user_id , $filterData);
    public function myCurrentCommission($user_id,$period);
    public function getTotalCommissionAndRedeem($user_id);
    public function getAllData($inputData);
    public function getAllDataMonthly($inputData);
    public function getAllCommissionOrders($inputData);
    public function getOneCommission($id);
}
