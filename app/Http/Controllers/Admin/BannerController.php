<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BannersRequest;
use App\Http\Services\BannerService;
use App\Http\Services\CategoryService;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends HomeController
{

    private  $BannerService;
    private  $CategoryService;

    public function __construct(BannerService $BannerService,CategoryService  $CategoryService)
    {
       $this->BannerService = $BannerService;
       $this->CategoryService=$CategoryService;
    }

    public function index()
    {
        $banners = $this->BannerService->getAllBanners(request()->all());

        return view('AdminPanel.PagesContent.Banners.index')->with('banners',$banners);
    }

    public function create()
    {
        $categories = $this->CategoryService->getAllSubCategories();

        return view('AdminPanel.PagesContent.Banners.edit',compact('categories'));
    }

    public function store(BannersRequest $request)
    {
        $validated = $request->validated();
        $this->BannerService->createBannerRow($validated);
        return redirect()->route('banners.index')->with('message','Banner Created Successfully');
    }

    public function show(Banner  $banner)
    {

    }

    public function edit(Banner  $banner)
    {
        $categories = $this->CategoryService->getAllSubCategories();

        return view('AdminPanel.PagesContent.Banners.edit',compact('categories','banner'));
    }

    public function update(BannersRequest $request,$id)
    {
        $validated = $request->validated();
        $this->BannerService->updateBannerRow($validated , $id);
        return redirect()->back()->with('message','Banner Updated Successfully');
    }
    public function destroy(Banner  $banner)
    {
        @unlink(public_path($banner->url));
        $banner->delete();
        return redirect()->route('banners.index')->with('message', 'Banner Deleted Successfully');
    }
}
