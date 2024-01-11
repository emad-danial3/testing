<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Application\ProductController;
use App\Http\Controllers\Application\SpinnerController;
use App\Http\Controllers\Application\OrderController;
use App\Http\Controllers\Application\CartController;
use App\Http\Controllers\Application\UserController;
use App\Http\Controllers\Application\UserDashboardController;
use App\Http\Controllers\Application\UserCartController;
use App\Http\Controllers\Application\FawryPaymentController;
use App\Http\Controllers\Application\GeneralAPIController;
use App\Http\Controllers\Application\CategoriesController;
use App\Http\Controllers\Application\HomeController;
use App\Http\Controllers\Application\PayByWalletController;
use App\Http\Controllers\Application\TestingOracleController;


 Route::post('saveOrderAppTest', [UserCartController::class, 'saveOrderAppTest']);
Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('/getAllProducts', [ProductController::class, 'getAllProducts']);
    Route::post('/getBestProducts', [ProductController::class, 'getBestProducts']);
    Route::post('upgradeCustomerToMember', [UserController::class, 'upgradeCustomerToMember']);
    Route::post('/updateProfile', [UserController::class, 'updateProfile']);
    Route::post('/CalculateProductsAndShipping', [CartController::class, 'CalculateProductsAndShipping']);
    Route::post('/getMyGift', [SpinnerController::class, 'getMyGift']);
    Route::get('/spinnerGifts', [SpinnerController::class, 'spinnerGifts']);
    Route::post('/createOrder', [OrderController::class, 'createOrder']);
    Route::post('/getMyNotification', [UserController::class, 'getMyNotification']);
    Route::post('/getMyShareLinks', [UserController::class, 'getMyShareLinks']);
    Route::post('myNetwork', [UserController::class, 'myNetwork']);
    Route::post('myCommission', [UserController::class, 'myCommission']);
    Route::post('updatePaymentCode', [OrderController::class, 'updatePaymentCode']);
    Route::post('accountTracking', [UserController::class, 'accountTracking']);
    Route::post('myOrder', [OrderController::class, 'myOrder']);
    Route::post('getOrderDetails', [OrderController::class, 'getOrderDetails']);
    Route::post('cancelOrder', [OrderController::class, 'cancelOrder']);
    Route::post('orderDetails', [OrderController::class, 'orderDetails']);
    Route::post('reorder', [OrderController::class, 'reorder']);
    Route::post('getMyWallet', [UserController::class, 'getMyWallet']);
    Route::post('changePassword', [UserController::class, 'changePassword']);
//    Route::post('completeRegister', [UserController::class, 'completeRegister']);
    Route::post('payWithWallet', [PayByWalletController::class, 'payByWallet']);
    Route::post('userInfo', [UserController::class, 'userInfo']);
    Route::post('logOut', [UserController::class, 'logOut']);

//    Route::post('upgradeAccount', [UserController::class, 'upgradeAccount']);

    // Cart
    Route::post('addProductToCart', [UserCartController::class, 'addProductToCart']);
    Route::post('deleteProductFromCart', [UserCartController::class, 'deleteProductFromCart']);
    Route::post('getMyCart', [UserCartController::class, 'getMyCart']);
    Route::post('saveOrderApp', [UserCartController::class, 'saveOrderApp']);
   
    
    Route::post('getOrderCheckout', [UserCartController::class, 'getOrderCheckout']);
    // Favourites
    Route::post('getMyFavourites', [UserController::class, 'getUserFavourites']);
    Route::post('addProductToFavourites', [UserController::class, 'addProductToFavourites']);

    // Addresses
    Route::post('addUserAddress', [UserController::class, 'addUserAddress']);
    Route::post('getMyAddresses', [UserController::class, 'getMyAddresses']);
    Route::post('makeAddressPrime', [UserController::class, 'makeAddressPrime']);




//    dashboard
    Route::post('getMyDashboardInfo', [UserDashboardController::class, 'getMyDashboardInfo']);
    Route::post('getMyCommission', [UserDashboardController::class, 'getMyCommission']);
    Route::post('getMyCashback', [UserDashboardController::class, 'getMyCashback']);
    Route::post('getMyReports', [UserDashboardController::class, 'getMyReports']);
    Route::post('exportActiveTeamSheet', [UserDashboardController::class, 'exportActiveTeamSheet']);

    Route::post('sendContactUsMessageToEmail', [UserController::class, 'sendContactUsMessageToEmail']);

Route::post('calculateMyMonthlyCommission', [UserDashboardController::class, 'calculateMyMonthlyCommission']);

// UserWallet
    Route::post('getMyUserWallet', [UserDashboardController::class, 'getMyUserWallet']);

});

Route::get('calculateCommissionForAllUsersToday', [UserDashboardController::class, 'calculateCommissionForAllUsersToday']);
Route::post('registerWithoutToken', [UserController::class, 'register']);
Route::post('registerAsCustomer', [UserController::class, 'RegisterAsCustomer']);
Route::post('registerAsMember', [UserController::class, 'RegisterAsMember']);

Route::post('changeOrderStatus', [FawryPaymentController::class, 'changeOrderStatus'])->name('changeOrderStatus');
Route::post('changeOrderStatusFromOldProject', [FawryPaymentController::class, 'changeOrderStatusFromOldProject'])->name('changeOrderStatusFromOldProject');


Route::post('login', [UserController::class, 'login']);
Route::post('checkForQrcode', [\App\Http\Controllers\Application\QrcodeController::class, 'check'])->name('checkForQrcode');
Route::get('test', [\App\Http\Controllers\Application\TestController::class, 'index']);

Route::post('refreshToken', [UserController::class, 'refreshToken']);
Route::post('forgotPassword', [UserController::class, 'forgotPassword']);
Route::post('setNewPassword', [UserController::class, 'SetNewPassword']);
Route::post('checkValidationCode', [UserController::class, 'CheckValidationCode']);
Route::get('FAQ', [GeneralAPIController::class, 'FAQ']);
Route::get('staticPages', [GeneralAPIController::class, 'staticPages']);
Route::get('sharePagesCategory', [GeneralAPIController::class, 'sharePagesCategory']);
Route::get('getBrochure', [GeneralAPIController::class, 'getBrochure']);
Route::get('sharePages/{id}', [GeneralAPIController::class, 'sharePages']);
Route::get('banners', [GeneralAPIController::class, 'Banners']);
Route::post('uploadImage', [GeneralAPIController::class, 'uploadImage']);
Route::post('getBrands', [CategoriesController::class, 'getCategories']);
Route::post('getCategories', [HomeController::class, 'getFilters']);
Route::post('checkMyVersion', [UserController::class, 'checkMyVersion']);
Route::get('getCountries', [GeneralAPIController::class, 'country']);
Route::get('getFirstScreens', [GeneralAPIController::class, 'getFirstScreens']);
Route::get('getCities/{id}', [GeneralAPIController::class, 'city']);
Route::get('getAreas/{id}', [GeneralAPIController::class, 'area']);
Route::get('getAccountTypes', [GeneralAPIController::class, 'AccountTypes']);
Route::post('sendUserToOracle', [TestingOracleController::class, 'sendUser']);
Route::post('sendUOrderToOracle', [TestingOracleController::class, 'sendOrder']);
//testing only
//Route::post('changePassword', [TestingOracleController::class, 'changePassword']);
//Route::get('stock', [OrderController::class, 'stockManagement']);
//Route::post('testPay', [TestingOracleController::class, 'testPay']);

Route::post('updateTableJS', [\App\Http\Controllers\Admin\OracleProductsController::class, 'updateTableJS']);


Route::get("test", function () {
    echo "dasd";
});







