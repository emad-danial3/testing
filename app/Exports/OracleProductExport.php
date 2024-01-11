<?php

namespace App\Exports;

use App\Models\OracleProducts;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OracleProductExport implements FromCollection , WithHeadings
{

    public function __construct()
    {
    }

    public function collection()
    {
        $nowsubdate=Carbon::now()->subDays(30);
        $data = OracleProducts::leftJoin('products', 'products.oracle_short_code', '=', 'oracle_products.item_code')
            ->select('products.id as product_id','products.full_name','products.oracle_short_code','oracle_products.quantity as oracle_quantity','products.quantity as available_quantity',
                DB::raw("(SELECT SUM(order_lines.quantity) FROM order_headers JOIN order_lines ON order_lines.order_id = order_headers.id
WHERE
    order_headers.order_status = 'pending'
    AND(wallet_status = 'cash' OR payment_status = 'PAID')
    AND(order_headers.oracle_flag IS NULL OR order_headers.oracle_flag < 0)
    AND order_headers.platform != 'admin'
    AND order_lines.product_id = products.id
    AND order_headers.created_at > '{$nowsubdate}') as pending_query ")
                ,'products.barcode','oracle_products.created_at'
            )->orderBy('oracle_products.id', 'asc');
        $data=$data->get();
        return $data;
    }

    public function headings(): array
    {
        return  [
            'product_id',
            'full_name',
            'oracle_short_code',
            'oracle_quantity',
            'available_quantity',
            'pending_query_now',
            'barcode',
            'created_at',
            ];
    }
}
