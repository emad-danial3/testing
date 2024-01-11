<?php

namespace App\Http\Services;

use App\Http\Repositories\PurchaseInvoiceLinesRepository;

class PurchaseInvoicesLinesService extends BaseServiceController
{
    private  $PurchaseInvoiceLinesRepository;

    public function __construct(PurchaseInvoiceLinesRepository $PurchaseInvoiceLinesRepository)
    {
        $this->PurchaseInvoiceLinesRepository=$PurchaseInvoiceLinesRepository;
    }

    public function createInvoiceLines($invoice_id ,$items)
    {
        foreach ($items as $key => $item)
        {
            if (!empty($item))
            {
                $this->PurchaseInvoiceLinesRepository->createLines($item,$invoice_id);
            }
        }
    }

}
