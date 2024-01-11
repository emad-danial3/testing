<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OracleInvoices extends AbstractModel
{
    use HasFactory;

    public function lines()
    {
        return $this->hasMany(OrderLine::class, 'oracle_num','web_order_number');
    }
    public function order_header()
    {
        return $this->belongsTo(OrderHeader::class, 'web_order_number','id');

    }
     public function order_header_link_with_invoices()
    {
        return $this->belongsTo(OrderHeader::class, 'oracle_invoice_number','oracle_invoice_number');

    }
}
