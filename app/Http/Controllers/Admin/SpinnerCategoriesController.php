<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SpinnerCategoryRequest;
use App\Http\Services\SpinnerCategoriesService;
use App\Models\SpinnerCategory;

class SpinnerCategoriesController extends HomeController
{
    private $SpinnerCategoriesService;

    public function __construct(SpinnerCategoriesService $SpinnerCategoriesService)
    {
        $this->SpinnerCategoriesService = $SpinnerCategoriesService;
    }

    public function index()
    {
        $data = $this->SpinnerCategoriesService->getAll();
        return view('AdminPanel.PagesContent.SpinnerCategories.index')->with('spinnerCategories',$data);
    }

    public function create()
    {
        return view('AdminPanel.PagesContent.SpinnerCategories.edit');
    }

    public function store(SpinnerCategoryRequest $request)
    {
        $validated = $request->validated();
        $this->SpinnerCategoriesService->createRow($validated);
        return redirect()->route('spinnerCategories.index')->with('message','Spinner Category Created Successfully');
    }

    public function show(SpinnerCategory $spinnerCategory)
    {

    }

    public function edit(SpinnerCategory $spinnerCategory)
    {
        return view('AdminPanel.PagesContent.SpinnerCategories.edit')->with('spinnerCategory', $spinnerCategory);
    }

    public function update(SpinnerCategoryRequest $request,$id)
    {
        $validated = $request->validated();
        $this->SpinnerCategoriesService->updateRow($validated , $id);
        return redirect()->back()->with('message','SpinnerCategory Updated Successfully');
    }
    public function destroy(SpinnerCategory $spinnerCategory)
    {

    }
}
