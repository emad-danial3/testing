<?php

namespace App\Http\Repositories;

interface PurchaseInvoiceLinesRepository
{
    public function createLines($invoiceLineData, $invoice_id);
    public function getInvoiceLines($order_id);
}
