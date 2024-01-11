<?php

namespace App\Imports;

use App\Models\UserCommission;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CommissionsImport implements ToCollection, WithHeadingRow
{

    public function collection(Collection $rows)
    {

        Validator::make($rows->toArray(), [
            '*.id' => 'required|exists:user_commissions,id',
            '*.is_redeemed' => 'required|in:1,0',
        ])->validate();
                

        foreach ($rows as $row) {
            UserCommission::where([
                'id' => $row['id'],

            ])->update(['is_redeemed' =>$row['is_redeemed'],'redeem_date'=>now()]);
        }
    }
}
