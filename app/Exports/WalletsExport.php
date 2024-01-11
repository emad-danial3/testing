<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\UserWallet;
class WalletsExport implements FromCollection , WithHeadings
{

    public function collection()
    {
        $data= UserWallet::select(
                'user_wallets.total_orders_amount',
                'user_wallets.total_wallet',
                'user_wallets.current_wallet',
                'users.full_name',
            )
            ->leftJoin('users','user_wallets.user_id','=','users.id')
            ->where('user_wallets.total_orders_amount','>',0)
            ->where('user_wallets.total_wallet','>',0)
            ->get();
        return $data;
    }

    public function headings(): array
    {
        return  [
            'TotalOrdersAmount',
            'TotalWallet',
            'CurrentWallet',
            'UserName',
            ];
    }
}
