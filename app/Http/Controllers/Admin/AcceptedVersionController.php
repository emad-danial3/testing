<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptedVersion\StoreRequest;
use App\Http\Services\AcceptedVersionService;
use App\Models\Setting;
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
        $setting=Setting::all();
        return view('AdminPanel.PagesContent.AcceptedVersion.index')->with('types',$types)->with('setting',$setting);
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

        if($id==10000){
            $setting=Setting::find(1);
            return view('AdminPanel.PagesContent.AcceptedVersion.setting')->with('setting',$setting);
        }
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

    public function updateSetting(Request $request)
    {

        $inputs = $request->all();
        $setting=Setting::where('id',1)->first();
        if(!empty($setting)){
            $setting->show_wallet =$inputs['show_wallet'];
            $setting->show_welcome_programme =$inputs['show_welcome_programme'];
            $setting->show_fawry_payemnt =$inputs['show_fawry_payemnt'];
            $setting->save();
        }

        $types=$this->acceptedVersion->AcceptedVersionRepository->getAll();
        $setting=Setting::all();
        return view('AdminPanel.PagesContent.AcceptedVersion.index')->with('types',$types)->with('setting',$setting);

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
