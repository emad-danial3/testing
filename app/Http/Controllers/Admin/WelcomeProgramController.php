<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\WelcomeProgramRequest;
use App\Http\Requests\WelcomeProgramRequestUpdate;
use App\Http\Services\WelcomeProgramService;

use App\Models\WelcomeProgramProduct;

use Carbon\Carbon;
use Illuminate\Http\Request;

class WelcomeProgramController extends HomeController
{
    private $WelcomeProgramService;


    public function __construct(WelcomeProgramService $WelcomeProgramServiceService)
    {
        $this->WelcomeProgramService = $WelcomeProgramServiceService;
    }

    public function index()
    {
        $data = $this->WelcomeProgramService->getAll();
        return view('AdminPanel.PagesContent.WelcomeProgram.index')->with('programs', $data);
    }

    public function create()
    {
        return view('AdminPanel.PagesContent.WelcomeProgram.edit');
    }

    public function store(WelcomeProgramRequest $request)
    {
        $validated = $request->validated();
        $data               = $request->only('month', 'name_ar', 'name_en','image','total_price','total_old_price');

        $data['start_date'] = Carbon::now()->toDateString();
        $program            = $this->WelcomeProgramService->createRow($data);
        if (!empty($program)) {
            if (isset($validated['product_ids']) && count($validated['product_ids'])) {
                for ($i = 0; $i < count($validated['product_ids']); $i++) {
                    $obj = ['welcome_program_id' => $program->id, 'product_id' => $validated['product_ids'][$i], 'quantity' => $validated['product_quantitys'][$i], 'discount_rate' => $validated['product_discounts'][$i], 'price' => $validated['product_prices'][$i], 'price_after_discount' => $validated['product_prices_after_discount'][$i]];
                    $this->WelcomeProgramService->createProgramProduct($obj);
                }
            }
        }
        return redirect()->route('welcome_program.index')->with('message', 'Program Created Successfully');
    }

    public function show(WelcomeProgramProduct $filter)
    {

    }

    public function edit($id)
    {
        $model = WelcomeProgramProduct::with('product')->with('product.product')->find($id);
        return view('AdminPanel.PagesContent.WelcomeProgram.edit', compact('model'));
    }

    public function update(WelcomeProgramRequestUpdate $request, $id)
    {
        $validated        = $request->validated();
        $data['end_date'] = Carbon::now()->toDateString();
        $data               = $request->only('month', 'name_ar', 'name_en','image','total_price','total_old_price','status');
        $this->WelcomeProgramService->updateRow($data, $id);
            if (isset($validated['product_ids']) && count($validated['product_ids'])) {
                for ($i = 0; $i < count($validated['product_ids']); $i++) {
                    $obj = ['welcome_program_id' => $id, 'product_id' => $validated['product_ids'][$i], 'quantity' => $validated['product_quantitys'][$i], 'discount_rate' => $validated['product_discounts'][$i], 'price' => $validated['product_prices'][$i], 'price_after_discount' => $validated['product_prices_after_discount'][$i]];
                    $this->WelcomeProgramService->createProgramProduct($obj);
                }
            }
        return redirect()->route('welcome_program.index')->with('message', 'Program Created Successfully');
    }

    public function destroy(Request $request, $id)
    {
// $delete = WelcomeProgramProduct::where('id', $id)->delete();

        $data['end_date'] = Carbon::now()->toDateString();
        $data['status']   = '0';
        $delete           = $this->WelcomeProgramService->updateRow($data, $id);
        // check data deleted or not
        if ($delete == 1) {
            $success = true;
            $message = 'delete Success';
        }
        else {
            $success = true;
            $message = 'delete Error';
        }

        //  Return response
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }
    public function deleteProduct(Request $request)
    {
        $inputs=$request->only('delete_product_id');
        $delete           = $this->WelcomeProgramService->deleteProduct($inputs['delete_product_id']);
        // check data deleted or not
       if ($delete == 1) {
          return redirect()->back()->with('message','Delete Successfully');
        }
        return redirect()->back()->with('message','Delete Fail');
    }

}
