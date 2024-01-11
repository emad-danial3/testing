<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptedVersion\StoreRequest;
use App\Http\Services\AcceptedVersionService;
use Illuminate\Http\Request;

class AcceptedVersionController extends Controller
{
    private $acceptedVersion;
    public function __construct(AcceptedVersionService $acceptedVersion)
    {
        $this->acceptedVersion= $acceptedVersion;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $types=$this->acceptedVersion->AcceptedVersionRepository->getAll();
        return view('AdminPanel.PagesContent.AcceptedVersion.index')->with('types',$types);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('AdminPanel.PagesContent.AcceptedVersion.form');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        //
        $validated= $request->validated();
        $type=$this->acceptedVersion->AcceptedVersionRepository->create($validated);
        return redirect()->route('AcceptedVersion.index')->with('message','Type Created Successfully');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    // delete version
        $type=$this->acceptedVersion->AcceptedVersionRepository->delete($id);
        return redirect()->route('AcceptedVersion.index')->with('message','Type Delete Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $type=$this->acceptedVersion->AcceptedVersionRepository->find($id);
        return view('AdminPanel.PagesContent.AcceptedVersion.form')->with('type',$type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRequest $request, $id)
    {
        //
        $validated= $request->validated();
        $type=$this->acceptedVersion->AcceptedVersionRepository->update($validated,$id);
        return redirect()->route('AcceptedVersion.index')->with('message','Type Created Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      //
    }

}
