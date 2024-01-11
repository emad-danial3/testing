<?php

namespace App\Http\Controllers\Admin;

use App\Constants\OrderTypes;
use App\Http\Requests\FreeProudctSpinnerRequest;
use App\Http\Requests\SpinnerRequest;
use App\Http\Services\ProductService;
use App\Http\Services\SpinnerCategoriesService;
use App\Http\Services\SpinnerService;
use App\Models\Spinner;

class SpinnerController extends HomeController
{
    private $SpinnerService;

    public function __construct(SpinnerService $SpinnerService)
    {
        $this->SpinnerService = $SpinnerService;
    }

    public function index()
    {
        $data = $this->SpinnerService->getAll();
        return view('AdminPanel.PagesContent.Spinners.index')->with('spinners',$data);
    }

    public function create()
    {

    }

    public function store(SpinnerRequest $request)
    {

    }

    public function show(Spinner $spinner)
    {

    }

    public function edit(Spinner $spinner)
    {
        return view('AdminPanel.PagesContent.Spinners.edit', compact( 'spinner'));
    }

    public function update(SpinnerRequest $request,$id)
    {
        $validated = $request->validated();
        $this->SpinnerService->updateRow($validated , $id);
        return redirect()->back()->with('message','Spinner Updated Successfully');
    }

    public function destroy(Spinner $spinner)
    {

    }

    public function editFreeProduct()
    {
        $freeProducts = $this->SpinnerService->getFreeProducts();
        $currentFreeProduct = $this->SpinnerService->find(12);
        return view('AdminPanel.PagesContent.Spinners.editFreeProduct', compact( 'freeProducts','currentFreeProduct'));
    }

    public function editPromoCode()
    {
        $currentFreeProduct = $this->SpinnerService->find(16);
        return view('AdminPanel.PagesContent.Spinners.editPromoCode', compact( 'currentFreeProduct'));
    }

}
