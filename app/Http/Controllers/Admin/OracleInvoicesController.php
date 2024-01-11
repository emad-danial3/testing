<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InvoiceExport;
use App\Http\Requests\ExportOracleInvoicesSheet;
use App\Http\Services\OracleInvoicesService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class OracleInvoicesController extends HomeController
{

    private   $OracleInvoicesService;

    public function __construct(OracleInvoicesService $OracleInvoicesService)
    {
        $this->OracleInvoicesService = $OracleInvoicesService;
    }

    public function all_data()
    {
        $data = $this->OracleInvoicesService->all(request()->all());
        return response()->json([
            'data' => $data,
        ]);
    }
     public function all_view(Request $request)
    {
       
        $from = $request->from;
        $to = $request->to;
        if(!isset($from) || !isset($to))
        {
            $from = Carbon::now()->subDays(3)->toDateString();
            $to   = Carbon::now()->toDateString();

        }
        return view('AdminPanel.PagesContent.oracleInvoices.all',get_defined_vars());
    }
    public function index()
    {
       
       
        $data = $this->OracleInvoicesService->getAll(request()->all());
      
      
//        $ordersCountNotSentToOracle=0;
        $ordersCountSentToOracleNotReturn=0;

         $ordersCountNotSentToOracle=$this->OracleInvoicesService->getOrdersNotSentToOracle();
        //  $ordersCountSentToOracleNotReturn=$this->OracleInvoicesService->ordersCountSentToOracleNotReturn();

      
        return view('AdminPanel.PagesContent.oracleInvoices.index')->with('oracleInvoices', $data)->with('ordersCountNotSentToOracle',$ordersCountNotSentToOracle)->with('ordersCountSentToOracleNotReturn',$ordersCountSentToOracleNotReturn);
    }

    public function updateOracleInvoices()
    {
        $client   = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://sales.atr-eg.com/api/RefreshNettinghubInvoices.php', ['verify'      => false,
                                 'form_params' => array(
                                     'number' => '100',
                                     'name'   => 'Test user',
                                 )

        ]);
        $invoices = $response->getBody();

        if (isset($invoices)) {
        $invoices = json_decode($invoices);
        foreach ($invoices as $invoice) {
        $this->OracleInvoicesService->createOrUpdate($invoice);
        }
        return redirect()->back()->with('message', "Items Updated  Successfully");
        }
        else {
        return redirect()->back()->withErrors(['error' => "error in get Data"]);
        }
    }


   public function refreshOracleInvoices()
    {
        $this->OracleInvoicesService->updateInvoices();
        return redirect()->back()->with('message', "Items Invoices Updated  Successfully");
    }

    public function ExportOracleInvoicesSheet(ExportOracleInvoicesSheet $request)
    {
        $validated = $request->validated();
        try {
                return Excel::download(new InvoiceExport($validated['start_date'], $validated['end_date']), 'Invoices.xlsx');
        } catch (\Exception $exception) {

            return redirect()->back()->withErrors(['error' => $exception->getMessage()]);
        }
    }


    public function updateInvoiceOraclelJob()
    {
        $this->updateOracleInvoices();
        $this->refreshOracleInvoices();
    }

}
