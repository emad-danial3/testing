<?php

namespace App\Exports;

use App\Constants\OrderStatus;
use App\Models\OrderHeader;
use App\Models\Product;
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
        $OrderLines = DB::table('order_headers')
            ->join('order_lines', 'order_lines.order_id', '=', 'order_headers.id')
            ->join('users', 'order_headers.created_for_user_id', '=', 'users.id')
            ->join('products', 'order_lines.product_id', '=', 'products.id')
            ->select(["order_headers.id","users.full_name","users.phone","users.building_number","users.floor_number","users.apartment_number","users.landmark","users.address","users.area","users.city","order_headers.total_order","order_headers.total_order","order_headers.order_type","order_lines.oracle_num","order_headers.shipping_date","order_headers.delivery_date"])
            ->where('order_headers.payment_status',OrderStatus::PAID)
            ->whereBetween('order_headers.created_at',[$this->start_date, $this->end_date])
            ->addSelect(DB::raw("'visa' as visa"))
            ->distinct('order_lines.oracle_num')
            ->get();

        foreach ( $OrderLines as $order)
        {
            $order->weight = DB::table('order_headers')
                ->join('order_lines', 'order_lines.order_id', '=', 'order_headers.id')
                ->join('products', 'order_lines.product_id', '=', 'products.id')
                ->select('products.weight')
                ->where('order_headers.id',$order->id)
                ->sum('products.weight');
        }
        return  $OrderLines;
    }

    public function headings(): array
    {
        return ["invoice number","full_name","mobile","building number","floor number","apartment number","landmark","address","area","city","total","type of order","orecal number","shipping date","delivery date","visa","weight"];
    }
}
