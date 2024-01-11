<?php

namespace App\Http\Repositories;

use App\Models\CartHeader;
use App\Models\Product;
use App\Models\RegisterLink;
use App\Models\Spinner;
use Illuminate\Support\Str;


class ISpinnerRepository extends  BaseRepository implements SpinnerRepository
{
    public function __construct(Spinner $model)
    {
        parent::__construct($model);
    }

    public function getGift($inputData,$is_free_user)
    {

        $id=$inputData['id'];
        $spinner_category_id=$inputData['spinner_category_id'];
        $items=[];
        if ($spinner_category_id==1 ||$spinner_category_id==2)
        {

            $productId = $this->find($id,['gift_id']);
            $items     = Product::select('id','flag','full_name','name_en','name_ar','description_en',
                'description_ar','image','oracle_short_code','discount_rate',
                'price','price_after_discount')->find($productId);


        }

        else if($spinner_category_id==3)
        {
            $ProductItems=$inputData['items'];


            $code=$this->model->find($id,['promo_value']);

            foreach ($ProductItems as $item) {

                $productRow=Product::select('id','flag','full_name','name_en','name_ar','description_en',
                    'description_ar','image','oracle_short_code','discount_rate',
                    'price','price_after_discount')->where('id',$item['id'])->first();

                if (!empty($productRow)){
                    $productRow->price=($is_free_user)?($productRow->price_after_discount-(($productRow->price_after_discount*$code->promo_value) /100)):($productRow->price-(($productRow->price*$code->promo_value) /100)) ;
                    $productRow->price_after_discount=$productRow->price;
                    $productRow->quantity=$item['quantity'];
                    $items[]=$productRow;
                }

            }
        }

        else if ($spinner_category_id==5)
        {
            $user_id=$inputData['user_id'];
            $token=Str::random(60);
            $freeLink=$user_id.'/'.$token;
            $items['free_link']=$freeLink;
            RegisterLink::insert([
                'link'=>$freeLink,
                'user_id'=>$user_id,
                'is_used'=>0,
                'is_free_link'=>1
            ]);
        }

        return $items;
    }

    public function hasGift($user_id,$created_for_user_id): int
    {
        $cartHeader = CartHeader::where([
            'user_id'             => $user_id,
            'created_for_user_id' => $created_for_user_id,
        ])->select('gift_category_id')->first();

       return (!empty($cartHeader))?  $cartHeader->gift_category_id : 0 ;
    }


    public function updateData($conditions, $updatedData)
    {
        return Spinner::where($conditions)->update($updatedData);
    }
}
