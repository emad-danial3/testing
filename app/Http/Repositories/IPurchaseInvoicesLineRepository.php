<?php

namespace App\Http\Repositories;

use App\Models\PurchaseInvoiceLines;

class IPurchaseInvoicesLineRepository extends  BaseRepository implements PurchaseInvoiceLinesRepository
{
    public function __construct(PurchaseInvoiceLines $model)
    {
        parent::__construct($model);
    }


    public function createLines($invoiceLineData, $invoice_id)
    {
        PurchaseInvoiceLines::create([
            'purchase_invoice_id'   => $invoice_id,
            'product_id' => $invoiceLineData['id'],
            'purchase_price'      => $invoiceLineData['purchase_price'],
//            'selling_price'      => $invoiceLineData['selling_price'],
//            'discount_rate'      => $invoiceLineData['discount_rate'],
//            'price_after_discount'=>(floatval($invoiceLineData['selling_price'])-(( floatval($invoiceLineData['selling_price']) * floatval($invoiceLineData['discount_rate'])) / 100)),
            'quantity'   => $invoiceLineData['quantity'],
            'stock_status'   => $invoiceLineData['stock_status'],
        ]);
    }
     public function getInvoiceLines($invoice_id)
    {
      return  PurchaseInvoiceLines::where('purchase_invoice_id',$invoice_id)->get();
    }
}
