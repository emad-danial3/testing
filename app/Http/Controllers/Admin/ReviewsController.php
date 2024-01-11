<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\WelcomeProgramRequest;
use App\Http\Services\ReviewsService;

use App\Models\Review;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ReviewsController extends HomeController
{
    private $ReviewsService;


    public function __construct(ReviewsService $ReviewsService)
    {
        $this->ReviewsService = $ReviewsService;
    }

    public function index()
    {
        $data = $this->ReviewsService->getAll();
        return view('AdminPanel.PagesContent.Reviews.index')->with('reviews',$data);
    }

    public function create()
    {
        return view('AdminPanel.PagesContent.Reviews.edit');
    }

    public function store(WelcomeProgramRequest $request)
    {
        $validated = $request->validated();
        $data   = $request->only('month','product_id','price','discount_rate','price_after_discount');
        $data['start_date']=Carbon::now()->toDateString();
        $this->ReviewsService->createRow($data);
        return redirect()->route('welcome_program.index')->with('message','Program Created Successfully');
    }

    public function show(WelcomeProgramProduct $filter)
    {

    }

    public function edit($id)
    {
        $model=Review::find($id);
        return view('AdminPanel.PagesContent.Reviews.edit',compact('model'));
    }

    public function update(WelcomeProgramRequest $request,$id)
    {
        $validated = $request->validated();
        $data['end_date']= Carbon::now()->toDateString();
        $data['status']= '0';
        $this->ReviewsService->updateRow($data , $id);
        $data   = $request->only('month','product_id','price','discount_rate','price_after_discount');
        $data['start_date']=Carbon::now()->toDateString();
        $this->ReviewsService->createRow($data);
        return redirect()->route('welcome_program.index')->with('message','Program Created Successfully');
    }
        public function destroy(Request $request, $id)
    {
        $data   = $request->only('type_operation');
        if(isset($data['type_operation'])&& $data['type_operation'] == 'active'){
              $updata['status']= '1';
              $delete=$this->ReviewsService->updateRow($updata , $id);
        }
        if(isset($data['type_operation'])&& $data['type_operation'] == 'reject'){
              $updata['status']= '2';
              $delete=$this->ReviewsService->updateRow($updata , $id);
        }
        // check data deleted or not
        if ($delete == 1) {
            $success = true;
            $message = 'delete Success';
        } else {
            $success = true;
            $message = 'delete Error';
        }

        //  Return response
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

}
