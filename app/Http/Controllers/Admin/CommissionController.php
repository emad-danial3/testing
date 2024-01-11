<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CommissionsExport;
use App\Http\Requests\CommissionsExportRequest;
use App\Http\Requests\ImportCommissionsRequest;
use App\Http\Services\CommissionService;
use App\Imports\CommissionsImport;
use App\Models\AccountLevel;
use App\Models\UserCommission;
use App\Models\UserMonthlyCommission;
use Maatwebsite\Excel\Facades\Excel;

class CommissionController extends HomeController
{

    private $CommissionService;

    public function __construct(CommissionService  $CommissionService)
    {
        $this->CommissionService = $CommissionService;
    }

    public function index()
    {
        $data = $this->CommissionService->getAll(request()->all());
        //change this

        return view('AdminPanel.PagesContent.Commissions.index')->with('commissions',$data);
    }

    public function ExportCommissionsSheet(CommissionsExportRequest $request)
    {

        $validated = $request->validated();
        $data = UserCommission::where('updated_at','>=',$validated['start_date'])
            ->where('updated_at','<=',$validated['end_date'])->where('is_paid',$request->type??1)->orderBy('updated_at','desc')->get();
        try {
            return Excel::download(new CommissionsExport($validated['start_date'],$validated['end_date'],$data), 'commissions.csv');
        }
        catch (\Exception $exception)
        {
            return redirect()->back()->withErrors(['error' => 'Commissions Error in Import']);
        }
    }

    public function importCommissionsSheet(ImportCommissionsRequest $request)
    {
        $validated = $request->validated();
        try {
            Excel::import(new CommissionsImport(),request()->file('file'),\Maatwebsite\Excel\Excel::XLSX);
            return redirect()->back()->with('message','Commissions Updated Successfully');
        }
        catch (\Exception $exception)
        {
            return redirect()->back()->withErrors(['error' => $exception->getMessage()]);
        }
    }
}
