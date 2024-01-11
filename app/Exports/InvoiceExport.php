<?php

namespace App\Exports;

use App\Models\OracleInvoices;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InvoiceExport implements FromCollection, WithHeadings
{
    private $start_date;
    private $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date   = $end_date;
    }

     public function collection()
    {
        $invices = OracleInvoices::select('order_headers.id as Order_ID', 'oracle_invoices.web_order_number', 'oracle_invoices.oracle_invoice_number', 'order_headers.wallet_status', 'order_headers.payment_code',DB::raw("(order_headers.total_order - order_headers.shipping_amount) as total_order "), 'oracle_invoices.order_amount', 'oracle_invoices.actual_amount', 'oracle_invoices.total_order_in_oracle',
            'oracle_invoices.total_order_out_oracle', 'order_headers.created_at')
            ->leftJoin('order_headers', 'oracle_invoices.web_order_number', '=', 'order_headers.id')
            ->where('order_headers.id', '>', '0')->distinct('oracle_invoices.oracle_invoice_number')->orderBy('order_headers.id', 'DESC')
            ->whereBetween('order_headers.created_at', [$this->start_date, $this->end_date])->get();
        return $invices;
    }

    public function headings(): array
    {
        return ["Order ID 4U", "Invoice Number 4U", "Invoice Oracle Number", "Payment Type", "Payment Code", "Total Order 4U", "Total Oracle Amount", "Total Oracle Actual Amount", "General Total Oracle", "Total Order Out Oracle", "Created At"];
    }
}
