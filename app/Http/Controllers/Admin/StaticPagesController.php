<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StaticPagesRequest;
use App\Http\Services\StaticPagesService;
use App\Models\StaticPages;

class StaticPagesController extends HomeController
{
    private $StaticPagesService;

    public function __construct(StaticPagesService $StaticPagesService)
    {
        $this->StaticPagesService = $StaticPagesService;
    }

    public function index()
    {
        $data = $this->StaticPagesService->getAll();
        return view('AdminPanel.PagesContent.StaticPages.index')->with('staticPages',$data);
    }

    public function create()
    {
        return view('AdminPanel.PagesContent.StaticPages.edit');
    }

    public function store(StaticPagesRequest $request)
    {
        $validated = $request->validated();
        $this->StaticPagesService->createRow($validated);
        return redirect()->route('staticPages.index')->with('message','Spinner Category Created Successfully');
    }

    public function show(StaticPages $staticPage)
    {

    }

    public function edit(StaticPages $staticPage)
    {
        return view('AdminPanel.PagesContent.StaticPages.edit')->with('staticPage', $staticPage);
    }

    public function update(StaticPagesRequest $request,$id)
    {
        $validated = $request->validated();
        $this->StaticPagesService->updateRow($validated , $id);
        return redirect()->back()->with('message','StaticPages Updated Successfully');
    }
    public function destroy(StaticPages $staticPages)
    {

    }
}
