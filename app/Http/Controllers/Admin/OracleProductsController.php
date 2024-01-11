<?php

namespace App\Http\Controllers\Admin;

use App\Http\Services\OracleProductService;
use App\Http\Services\ProductService;
use App\Models\Product;
use App\Exports\OracleProductExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Services\PaymentService;
use DB;
use Illuminate\Support\Facades\Http;

class OracleProductsController extends HomeController
{
    private   $ProductService;
    private   $OracleProductService;
    protected $PaymentService;

    public function __construct(ProductService $ProductService, PaymentService $PaymentService, OracleProductService $OracleProductService)
    {
        $this->ProductService       = $ProductService;
        $this->PaymentService       = $PaymentService;
        $this->OracleProductService = $OracleProductService;
    }


    public function index()
    {
        $data = $this->OracleProductService->getAll(request()->all());
        return json_encode($data);
    }

    public function getViewOracleProducts()
    {
        $data = $this->OracleProductService->getViewOracleProducts(request()->all());

        return view('AdminPanel.PagesContent.Products.oracleProducts')->with('products', $data);
    }

    public function updateProductsCodes()
    {

// $affectedRows = User::where('back_id_image','like','%rest/public/images%')->limit(50)->offset(0)->get();
       
//          foreach ($affectedRows as $product)
//         {
//              $array        = explode('images/', $product->back_id_image);
//              $newimage='images/'.$array[1];
            
//              $product->back_id_image=$newimage;
//              $product->save();

//         }

//         dd($affectedRows);

//        $affectedRows = Product::where('image','like','%version_2/public/images%')->limit(5)->offset(0)->get();
//        dd($affectedRows);
//         foreach ($affectedRows as $product)
//        {
//             $array        = explode('images/', $product->image);
//             $newimage='images/'.$array[1];
//             dd($newimage);
//             $product->image=$newimage;
//             $product->save();

//           $nfull_name1= trim($user->full_name);
//           $nfull_name= preg_replace('/\s{2}/s', ' ',$nfull_name1);
//            User::where('id', $user->id)->update(['full_name' =>$nfull_name]);
//        }

//        dd($affectedRows);
//        $affectedRows = User::all();
//        foreach ($affectedRows as $user)
//        {
//           $nfull_name1= trim($user->full_name);
//           $nfull_name= preg_replace('/\s{2}/s', ' ',$nfull_name1);
//            User::where('id', $user->id)->update(['full_name' =>$nfull_name]);
//        }
//dd('fggffg');
        $client   = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://sales.atr-eg.com/api/RefreshNettinghubItems.php', ['verify'      => false,
                                                                                                         'form_params' => array(
                                                                                                             'number' => '100',
                                                                                                             'name'   => 'Test user',
                                                                                                         )

        ]);
        $products = $response->getBody();

        if (isset($products)) {
            $products = json_decode($products);
            if (isset($products) && isset($products[0]) && !isset($products[0]->Message)) {
                $this->OracleProductService->truncateModel();
                foreach ($products as $product) {
                    $this->OracleProductService->createOrUpdate($product);
                }
                return redirect()->back()->with('message', "Items Updated  Successfully");
            }
            return redirect()->back()->withErrors(['error' => "error in get Data from oracle"]);
        }
        else {
            return redirect()->back()->withErrors(['error' => "error in get Data"]);
        }
    }

    public function updateTableJS(Request $request)
    {

        $products = $request->input('myData');
        if (isset($products)) {
            $products = json_decode($products);
            foreach ($products as $product) {
                $this->OracleProductService->createOrUpdate($product);
            }
            return "Items Updated  Successfully";
        }
    }

    public function updateProductsPrice()
    {
        $this->OracleProductService->updatePrices();
        return redirect()->back()->with('message', "Items Prices Updated  Successfully");
    }

    public function sendOrderFromISupplay($store)
    {
        $orders = Http::withBody(json_encode([
            'token' => '2iz6EFMPopKdQDTWGxBvMRDCyWk26WSoAASANKiUzEuoo0ic6nAm',
        ]), 'application/json')->get('http://sales.atr-eg.com/Atr_app/public/api/v1/i_supply/orders/place-order?Isupply_branch='.$store)->json();
        dd($orders);
    }


    public function ExportOracleProductsSheet(Request $request)
    {
            try {
                return Excel::download(new OracleProductExport(null), 'products.xlsx');
            }
            catch (\Exception $e) {
                return $e->getMessage();
            }
    }


    public function getOracleProduct()
    {
        $data = $this->OracleProductService->find(request()->id);
        return json_encode($data);
    }

    public function updateProductsPriceOraclelJob()
    {
        $this->updateProductsCodes();
        $this->updateProductsPrice();
    }

    public function sendOrderToOracleThatNotSending()
    {
        $this->PaymentService->sendOrderToOracleThatNotSending();
    }

      public function sendOrderToOracleChashThatNotSending()
    {
        $this->PaymentService->sendOrderToOracleChashThatNotSending();
    }

    public function checkGiftProductsAvailability()
    {
        $this->PaymentService->checkGiftProductsAvailability();
    }

    public function sendOrderToOracleNotSending(Request $request)
    {
        $order_id = $request->input('order_id');
        $this->PaymentService->sendOrderToOracleNotSending($order_id);
        return redirect()->back()->with('message', "Order  Successfully");
    }

    public function sendOrderToOracleOnline(Request $request)
    {

        $order_id = $request->input('order_id');
        $this->PaymentService->sendOrderToOracleOnline($order_id);
        return redirect()->back()->with('message', "Order  Successfully");
    }

}
