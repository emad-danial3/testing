<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UpdateDigitalRequest;
use App\Http\Services\DigitalBrochureService;
use App\Models\DigitalBrochure;
use Illuminate\Http\Request;
use App\Libraries\UploadImagesController;

class DigitalBrochureController extends HomeController
{
    private $DigitalBrochureService;
    private $MediaController;

    public function __construct(UploadImagesController $MediaController, DigitalBrochureService $DigitalBrochureService)
    {
        $this->MediaController        = $MediaController;
        $this->DigitalBrochureService = $DigitalBrochureService;
    }

    public function index()
    {
        $digital_brochures = $this->DigitalBrochureService->getAllDigitalBrochure(request()->all());
        return view('AdminPanel.PagesContent.DigitalBrochure.index')->with('digital_brochures', $digital_brochures);
    }

    public function create()
    {
        return view('AdminPanel.PagesContent.DigitalBrochure.edit');
    }

    public function store(UpdateDigitalRequest $request)
    {
        $validated = $request->validated();
        if (isset($request['image'])) {
            $validated['image'] = $this->MediaController->UploadImage($request['image'], 'images/digitalBrochure');
        }
        if (isset($request['file'])) {
            $validated['file'] = $this->MediaController->UploadImage($request['file'], 'images/digitalBrochure');
        }
        $this->DigitalBrochureService->createDigitalBrochureRow($validated);
        return redirect()->route('digital_brochure.index')->with('message', 'Digital Brochure Created Successfully');
    }

    public function show(Country $country)
    {

    }

    public function edit(DigitalBrochure $DigitalBrochure)
    {
        return view('AdminPanel.PagesContent.DigitalBrochure.edit')->with('DigitalBrochure', $DigitalBrochure);
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validate(request(), [
            "title"    => "required",
            "title_en" => "required",
        ]);

        if (isset($request['image'])) {
            $validated['image'] = $this->MediaController->UploadImage($request['image'], 'images/digitalBrochure');
        }
        if (isset($request['file'])) {
            $validated['file'] = $this->MediaController->UploadImage($request['file'], 'images/digitalBrochure');
        }
        $this->DigitalBrochureService->updateDigitalBrochureRow($validated, $id);
        return redirect()->back()->with('message', 'Country Updated Successfully');
    }

    public function destroy($id)
    {
         $DigitalBrochure = DigitalBrochure::find($id);
        $DigitalBrochure->delete();

        //  Return response
      return redirect()->route('digital_brochure.index')->with('message', 'Digital Brochure Deleted Successfully');
    }
}
