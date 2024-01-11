<?php

namespace App\Exports;

use App\Constants\OrderStatus;
use App\Models\OrderHeader;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShippingSheetSheetExport implements FromCollection , WithHeadings
{
    private  $start_date;
    private  $end_date;
    public function __construct($start_date , $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        if (isset($this->start_date) &&isset($this->end_date) ){
            $this->start_date= Carbon::parse($this->start_date)->startOfDay()->toDateTimeString();
            $this->end_date=Carbon::parse($this->end_date)->endOfDay()->toDateTimeString();
        }
        $condtions=[];
        if(isset($this->payment_status) && $this->payment_status !=''){
            $condtions=[['order_headers.payment_status','=',$this->payment_status]];
        }else{
            $condtions=[['order_headers.id','>',0]];
        }

        $OrderLines= DB::table('order_headers')
            ->join('users', 'order_headers.user_id', '=', 'users.id')
            ->join('user_addresses', 'order_headers.address_id', '=', 'user_addresses.id')
            ->join('cities', 'user_addresses.city_id', '=', 'cities.id')
            ->join('areas', 'user_addresses.area_id', '=', 'areas.id')
            ->leftJoin('order_lines', 'order_lines.order_id', '=', 'order_headers.id')
            ->leftJoin('oracle_invoices', 'oracle_invoices.web_order_number', '=', 'order_lines.oracle_num')
            ->groupBy('order_headers.id')
            ->select(
                'order_headers.id',
                'order_headers.payment_code',
                'order_headers.total_order',
                'users.full_name',
                'users.user_type',
                'users.account_id',
                'users.phone',
                'users.telephone',
                'users.email',
                'user_addresses.address',
                'cities.name_en',
                'areas.region_en',
                'user_addresses.floor_number as building_number',
                'user_addresses.floor_number',
                'user_addresses.apartment_number',
                'user_addresses.landmark',
                'users.nationality_id',
                'users.user_type as order_type',
                'order_headers.shipping_amount',
                'order_headers.payment_status',
                'order_headers.order_status',
                'order_headers.shipping_date',
                'order_headers.delivery_date',
                'order_headers.wallet_status',
                'order_headers.wallet_used_amount',
                'order_headers.gift_id',
                'order_headers.created_at'
                ,'oracle_invoices.check_valid'
                ,'order_headers.total_order_has_commission'
            )
            ->orderBy('order_headers.created_at')
            ->where($condtions)->where('wallet_used_amount','>',0)
            ->whereBetween('order_headers.created_at',[$this->start_date, $this->end_date])
            ->get();


        return  $OrderLines;

    }


    public function headings(): array
    {
        return [
            "Invoice Number",
            "payment_code",
            "total_order",
            "User Name",
            "User Type",
            "User Serial Number",
            "User phone",
            "User landline number",
            "User email",
            "User Street",
            "User City",
            "User Area",
            "User Building Number",
            "User Floor Number",
            "User Apartment Number",
            "User Landmark",
            "User NationalID",
            "order_type",
            "shipping_amount",
            "payment_status",
            "order_status",
            "shipping_date",
            "delivery_date",
            "wallet_status",
            "wallet_used_amount",
            "gift_category_id",
            "Date",
            "Validate Oracle",
            "total order has commission",
        ];
    }
}
