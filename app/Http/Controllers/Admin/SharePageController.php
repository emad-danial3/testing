<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SharePageRequest;
use App\Http\Services\SharePageCategoryService;
use App\Http\Services\SharePageService;
use App\Models\SharePage;

class SharePageController extends HomeController
{
    private $SharePageService;
    private $SharePageCategoryService;

    public function __construct(SharePageService $SharePageService, SharePageCategoryService  $SharePageCategoryService)
    {
        $this->SharePageService         = $SharePageService;
        $this->SharePageCategoryService = $SharePageCategoryService;
    }

    public function index()
    {
        $data = $this->SharePageService->getAll(request()->all());
        return view('AdminPanel.PagesContent.SharePages.index')->with('sharePages',$data);
    }

    public function create()
    {
        $sharePagesCategories = $this->SharePageCategoryService->getAll(null);
        return view('AdminPanel.PagesContent.SharePages.edit',compact('sharePagesCategories'));
    }

    public function store(SharePageRequest $request)
    {
        $validated = $request->validated();
        $this->SharePageService->createRow($validated);
        return redirect()->route('sharePages.index')->with('message','sharePage Created Successfully');
    }

    public function show(SharePage $sharePage)
    {

    }

    public function edit(SharePage $sharePage)
    {
        $sharePagesCategories = $this->SharePageCategoryService->getAll(null);
        return view('AdminPanel.PagesContent.SharePages.edit', compact('sharePagesCategories', 'sharePage'));
    }

    public function update(SharePageRequest $request,$id)
    {
        $validated = $request->validated();
        $this->SharePageService->updateRow($validated , $id);
        return redirect()->back()->with('message','SharePage Updated Successfully');
    }
    public function destroy(SharePage $sharePage)
    {
        $sharePage->delete();
        return redirect()->route('sharePages.index')->with('message', 'SharePage Deleted Successfully');
    }
}
