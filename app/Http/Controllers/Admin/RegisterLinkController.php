<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LinksExport;
use App\Http\Requests\DeleteLinksRequest;
use App\Http\Requests\GenerateLinksRequest;
use App\Http\Services\RegisterLinksService;
use App\Http\Services\UserService;
use Maatwebsite\Excel\Facades\Excel;

class RegisterLinkController extends HomeController
{

    private  $RegisterLinkService;
    private  $UserService;

    public function __construct(RegisterLinksService $RegisterLinkService, UserService $UserService)
    {
        $this->RegisterLinkService = $RegisterLinkService;
        $this->UserService         = $UserService;
    }

    public function index()
    {
        $registerLinks = $this->RegisterLinkService->getAll(request()->all());
        $users         = $this->UserService->getAllUsersWithOutPagination();
        return view('AdminPanel.PagesContent.RegisterLinks.index', compact('registerLinks', 'users'));
    }

    public function deleteLinks(DeleteLinksRequest $request)
    {
        $inputData =$request->validated();
        $links_ids = explode(",", $inputData['links_ids']);
        if($links_ids[0] == 'on')
            $links_ids[0]= 0;
        $this->RegisterLinkService->deleteLinks($inputData['links_ids']);
        return redirect()->back()->with("message", 'Deleted Successfully');
    }

    public function generateLinks(GenerateLinksRequest $request)
    {
        $validated = $request->validated();
        $this->RegisterLinkService->GenerateLinks($validated);
        return redirect()->back()->with("message", 'Generated Successfully');
    }

    public function exportLinksSheet(DeleteLinksRequest $request)
    {
        $inputData =$request->validated();
        $links_ids = explode(",", $inputData['links_ids']);
        if($links_ids[0] == 'on')
            $links_ids[0]= 0;
        return Excel::download(new LinksExport($links_ids), 'links.xlsx');
    }
}
