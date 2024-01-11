<?php

namespace App\Http\Repositories;

interface OrderRepository
{
    public function isFirstOrder($created_for_user_id);
    public function createOrder($orderHeaderData);
    public function getOrder($order_id);
    public function updateOrder($conditions, $data);
    public function getMyOrder($user_id);
    public function cancelOrder($order_id,$canceled_reason);
    public function userIsActiveInCurrentMonth($user_id,$date_from,$date_to);
    public function checkUserDeserveGift($user_id,$created_at);
    public function getMyTeamTotalSales($team,$date_from,$date_to,$period);
    public function getMyTeamOrdersIds($team,$date_from,$date_to,$period);
    public function getMyTotalSalesWithStatus($team,$status,$type,$date_from,$date_to,$period);
    public function getMyTeamDataAndTotalSales($team,$date_from,$date_to,$period);
    public function userHasReceivedGift($id);
    public function getUsersActiveSalesTeam($team);
    public function getUsersNotActiveSalesTeam($team);
    public function getMyOrderDetails($order_id);
    public function getAllData($inputData);
    public function updateData($conditions , $updatedData);
}
