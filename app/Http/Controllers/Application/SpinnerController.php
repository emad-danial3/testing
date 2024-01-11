<?php

namespace App\Http\Controllers\Application;

use App\Http\Repositories\ISpinnerCategoriesRepository;
use App\Http\Repositories\ISpinnerRepository;
use App\Http\Services\CartService;
use App\Http\Services\SpinnerService;
use App\Http\Services\UserService;
use App\Libraries\ApiResponse;
use App\Libraries\ApiValidator;
use App\Models\Spinner;
use App\ValidationClasses\SpinnerValidation;
use Illuminate\Http\Request;

class SpinnerController extends HomeController
{

    protected  $API_VALIDATOR;
    protected  $API_RESPONSE;
    protected  $SpinnerValidation;
    protected  $SpinnerService;
    protected  $UserService;
    protected  $CartService;
    public function __construct(CartService  $CartService,UserService  $UserService,SpinnerService  $SpinnerService,ApiValidator  $apiValidator,ApiResponse  $API_RESPONSE,SpinnerValidation $SpinnerValidation)
    {
        $this->API_RESPONSE      = $API_RESPONSE;
        $this->API_VALIDATOR     = $apiValidator;
        $this->SpinnerValidation = $SpinnerValidation;
        $this->SpinnerService    = $SpinnerService;
        $this->UserService       = $UserService;
        $this->CartService       = $CartService;
    }
    public function spinnerGifts(Request  $request)
    {
        $validator=$this->API_VALIDATOR->validateWithNoToken($this->SpinnerValidation->getSpinner());

        if ($validator) {
            $errorMessages=[];
            foreach ($validator->keys() as $error) {
                $errorMessages[] = trans('validation.'.$error);
            }
            return $this->API_RESPONSE->errors($errorMessages,400);
        }
        $user_id             = $request->user_id;
        $created_for_user_id = $request->input('created_for_user_id');
        $totalOrder          = 0;
        $totalOrder          = $this->CartService->getTotalPriceOfCart($user_id, $created_for_user_id);
        $spinner             = $this->SpinnerService->getSpinnerGifts($user_id,$totalOrder);
        return $this->API_RESPONSE->data(['Spinner_Gifts'=>$spinner],'Spinner Gifts',200);
    }

    public function getMyGift(Request  $request)
    {
        $validator=$this->API_VALIDATOR->validateWithNoToken($this->SpinnerValidation->getGift());
        if ($validator) {
            $errorMessages=[];
            foreach ($validator->keys() as $error) {
                $errorMessages[] = trans('validation.'.$error);
            }
            return $this->API_RESPONSE->errors($errorMessages,400);
        }
        $data = [
            'id'                  => $request->input('id'),
            'spinner_category_id' => $request->input('spinner_category_id'),
            'user_id'             => $request->user_id,
            'created_for_user_id' => $request->input('created_for_user_id'),
        ];
        $checkIfHasGift = $this->SpinnerService->checkIfHasGift($data['user_id'],$data['created_for_user_id']);
        //for testing
        $this->SpinnerService->setGift($data);
        $CartItems = $this->CartService->getMyCart($data['user_id'],$data['created_for_user_id']);
        return $this->API_RESPONSE->data($CartItems,'My Spinner Gifts',200);
    }



}
