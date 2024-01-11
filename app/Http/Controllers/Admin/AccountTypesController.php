<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountTypes\StoreRequest;
use App\Http\Services\AccountTypesService;
use Illuminate\Http\Request;

class AccountTypesController extends Controller
{
    private $AccountTypesService;
    public function __construct(AccountTypesService $AccountType)
    {
        $this->AccountTypesService=$AccountType;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $types=$this->AccountTypesService->getAll();
        return view('AdminPanel.PagesContent.AccountTypes.index')->with('types',$types);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('AdminPanel.PagesContent.AccountTypes.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        //refactor to events
        $validated=$request->except(['level_one','level_two','level_three']);

        $type=$this->AccountTypesService->store($validated);

            $validated=$request->only(['level_one','level_two','level_three']);
            $validated['account_id']=$type->id;

            $this->AccountTypesService->StoreTypeCommission($validated);
            return redirect()->route('accountTypes.index')->with('message','Type Created Successfully');




    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $type=$this->AccountTypesService->find($id);
        return view('AdminPanel.PagesContent.AccountTypes.form')->with('type',$type);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        //refactor to events
        $validated=$request->except(['level_one','level_two','level_three']);

        $type=$this->AccountTypesService->update($validated,$id);

        $validated=$request->only(['level_one','level_two','level_three']);

        $this->AccountTypesService->UpdateTypeCommission($validated,$id);
        return redirect()->route('accountTypes.index')->with('message','Type Created Successfully');

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
