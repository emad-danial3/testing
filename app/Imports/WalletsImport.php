<?php

namespace App\Imports;

use App\Models\User;
use App\Models\UserWallet;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Auth;
class WalletsImport implements ToCollection, WithHeadingRow
{

    public function collection(Collection $rows)
    {
        Validator::make($rows->toArray(), [
        '*.user_id' => 'required',
        '*.user_commission_id' => 'required',
        '*.amount' => 'required',
        ])->validate();
        foreach ($rows as $row) {
          $curentwallet=  UserWallet::where('user_id',$row['user_id'])->first();;
            if(!empty($curentwallet)){
                $curentwallet->updated_at=Carbon::now()->toDateTimeString();
                $curentwallet->added_by=Auth::guard('admin')->user()->id;
                $curentwallet->total_wallet=$curentwallet->total_wallet+$row['amount'];
                $curentwallet->current_wallet=$curentwallet->current_wallet+$row['amount'];
                $curentwallet->save();
            }else{
                $createWallet=[
                    "created_at"=>Carbon::now()->toDateTimeString(),
                    "updated_at"=>Carbon::now()->toDateTimeString(),
                    "user_id"=>$row['user_id'],
                    "current_wallet"=>$row['amount'],
                    "total_wallet"=>$row['amount'],
                    "used_wallet"=>0.00,
                    "added_by"=> Auth::guard('admin')->user()->id,
                ];
                UserWallet::create($createWallet);
            }

        }
    }
}
