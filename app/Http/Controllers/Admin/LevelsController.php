<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersLevelsExport;
use App\Http\Controllers\Controller;
use App\Http\Repositories\IAccountLevelRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LevelsController extends HomeController
{
    //

    private $levels;
    public function __construct(IAccountLevelRepository $levels)
    {
        $this->levels=$levels;
    }

    public function index(){

        $data=$this->levels->getAllData(request()->all());
        return view('AdminPanel.PagesContent.Levels.index')->with('levels',$data);
    }
    public function ExportSheet()
    {
        try{
            return Excel::download(new UsersLevelsExport(), 'UsersLevels.csv');
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }


}
