<?php

namespace App\Http\Controllers\Admin;

use App\Exports\WalletsExport;
use App\Http\Controllers\Controller;
use App\Http\Services\UserWalletService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class WalletController extends Controller
{
    //
    private $WalletService;

    public function __construct(UserWalletService  $WalletService)
    {
        $this->WalletService = $WalletService;
    }

    public function index(){

        $data=$this->WalletService->getAll(request()->all());

        return view('AdminPanel.PagesContent.Wallets.index')->with('wallets',$data);
    }

    public function ExportSheet()
    {
            try{
                return Excel::download(new WalletsExport(), 'wallets.csv');
            }catch(\Exception $e){
                return $e->getMessage();
            }
    }


}
