<?php

namespace App\Http\Controllers\Admin;

use App\Http\Services\NettingJoinService;
use App\Models\NettingJoin;
use Illuminate\Http\Request;

class NettingJoinController extends HomeController
{
    private $NettingJoinService;

    public function __construct(NettingJoinService  $NettingJoinService)
    {
        $this->NettingJoinService = $NettingJoinService;
    }

    public function index()
    {
        $joinUs = $this->NettingJoinService->getAll(request()->all());
        return view('AdminPanel.PagesContent.JoinUs.index')->with('nettingJoin',$joinUs);
    }
}
