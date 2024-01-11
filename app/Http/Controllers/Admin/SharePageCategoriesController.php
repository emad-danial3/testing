<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SharePageCategoryRequest;
use App\Http\Services\SharePageCategoryService;
use App\Models\SharePageCategory;
use Illuminate\Http\Request;

class SharePageCategoriesController extends HomeController
{

    private $SharePageCategoryService;

    public function __construct(SharePageCategoryService $SharePageCategoryService)
    {
        $this->SharePageCategoryService = $SharePageCategoryService;
    }

    public function index()
    {
        $data = $this->SharePageCategoryService->getAll(request()->all());
        return view('AdminPanel.PagesContent.SharePageCategories.index')->with('sharePageCategories',$data);
    }

    public function create()
    {
        return view('AdminPanel.PagesContent.SharePageCategories.edit');
    }

    public function store(SharePageCategoryRequest $request)
    {
        $validated = $request->validated();
        $this->SharePageCategoryService->createRow($validated);
        return redirect()->route('sharePageCategories.index')->with('message','sharePageCategory Created Successfully');
    }

    public function show(SharePageCategory $sharePageCategory)
    {

    }

    public function edit(SharePageCategory $sharePageCategory)
    {
        return view('AdminPanel.PagesContent.SharePageCategories.edit')->with('sharePageCategory', $sharePageCategory);
    }

    public function update(SharePageCategoryRequest $request,$id)
    {
        $validated = $request->validated();
        $this->SharePageCategoryService->updateRow($validated , $id);
        return redirect()->back()->with('message','SharePageCategory Updated Successfully');
    }
    public function destroy(SharePageCategory $sharePageCategory)
    {

    }
}
