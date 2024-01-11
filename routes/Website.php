<?php

use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\DigitalBrochureController;
use App\Http\Controllers\Website\ProductController;
use App\Http\Controllers\Website\CartController;
use App\Http\Controllers\Admin\OracleProductsController;
use App\Http\Controllers\Admin\OracleInvoicesController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('/returnFromMylers', [HomeController::class, 'returnFromMylers']);
Route::get('/shop/{id?}', 'CartController@shop')->name('shop');
Route::post('/shop/{id?}', 'CartController@search')->name('shop');
Route::get('/cart/{id?}', 'CartController@cart')->name('cart.index');

Route::post('/proceed', 'CartController@proceed')->name('cart.proceed');
Route::post('/add', 'CartController@add')->name('cart.store');
Route::post('/update', 'CartController@update')->name('cart.update');
Route::post('/remove', 'CartController@remove')->name('cart.remove');
Route::post('/clear', 'CartController@clear')->name('cart.clear');
Route::get('/fawry', 'CartController@fawry')->name('fawry');

// emad danial
Route::get('/registeruser', 'RegistrationController@create');
Route::post('/registeruser', 'RegistrationController@store');
Route::get('/beforeregister', 'RegistrationController@beforeRegister');
Route::get('/contactus', 'RegistrationController@contactus');
Route::get('/joinus', 'RegistrationController@joinus');
Route::get('/getCart', 'User\UserCartController@index');
Route::post('/joinus', 'RegistrationController@joinusPost');
Route::get('/login', 'RegistrationController@loginForm')->name('login');
Route::get('/forgot', 'RegistrationController@forgot')->name('forgot');
//Route::get('/testtest', 'RegistrationController@loginForm')->name('testtest');


Route::get('/updateProductsPriceOraclelJob', [OracleProductsController::class,'updateProductsPriceOraclelJob']);
Route::get('/updateInvoiceOraclelJob', [OracleInvoicesController::class,'updateInvoiceOraclelJob']);
Route::get('/sendOrderToOracleThatNotSending', [OracleProductsController::class,'sendOrderToOracleThatNotSending']);
Route::get('/sendOrderToOracleChashThatNotSending', [OracleProductsController::class,'sendOrderToOracleChashThatNotSending']);

Route::get('/checkGiftProductsAvailability', [OracleProductsController::class,'checkGiftProductsAvailability']);
Route::post('/sendOrderToOracleNotSending', [OracleProductsController::class,'sendOrderToOracleNotSending'])->name('sendOrderToOracleNotSending');
Route::post('/sendOrderToOracleOnline', [OracleProductsController::class,'sendOrderToOracleOnline'])->name('sendOrderToOracleOnline');
Route::get('/sendOrderFromISupplay/{store}', [OracleProductsController::class,'sendOrderFromISupplay'])->name('sendOrderFromISupplay');

Route::post('/login', 'RegistrationController@signIn');
Route::post('/forgot', 'RegistrationController@forgotPost');


Route::group(['middleware' => ['auth']], function () {
    // members routes
    Route::post('/updateUser', 'RegistrationController@updateUser')->name('updateUser');
    Route::post('/addNewG1Member', 'RegistrationController@addNewG1Member')->name('addNewG1Member');
    Route::get('/getCheckout', 'User\UserCartController@getCheckout');
    Route::get('/signout', 'RegistrationController@signOut');
    Route::get('/orderSuccess/{id}', 'User\UserCartController@orderSuccess');
    Route::get('/orderDetails/{id}', 'User\UserCartController@orderDetails');
    Route::post('/saveWebOrder', 'User\UserCartController@saveWebOrder');
    Route::post('/saveProductReview', 'User\UserCartController@saveProductReview');
    Route::post('/checkWebOrder', 'User\UserCartController@checkWebOrder');
    Route::post('/addUserAddress', 'RegistrationController@addUserAddress');
    Route::post('/deleteUserAddress', 'RegistrationController@deleteUserAddress');
    Route::post('/updateUserProfileImage', 'RegistrationController@updateUserProfileImage');
    Route::post('/updateUserContactInformation', 'RegistrationController@updateUserContactInformation');
    Route::get('/memberProfile', 'User\MemberController@index');
    Route::post('/order/cancelMemberOrder', 'User\MemberController@cancelMemberOrder')->name('order.cancelMemberOrder');
    Route::post('/ExportActiveTeamSheet', 'User\MemberController@ExportActiveTeamSheet')->name('ExportActiveTeamSheet');
});
Route::post('/payWithfawry', 'User\UserCartController@payWithfawry')->name('payWithfawry');
Route::get('/returnFromfawry', 'User\UserCartController@returnFromfawry')->name('returnFromfawry');

Route::get('registrationfree/{id}/{token}', 'RegistrationController@createfree');
Route::post('registrationfree/{id}/{token}', 'RegistrationController@storefree');

Route::get('export', 'MyController@export')->name('export');
Route::get('importExportView', 'MyController@importExportView');
Route::post('import', 'MyController@import')->name('import');

Route::get('registration/{id}/{token}', 'RegistrationController@create');

Route::post('registration/{id}/{token}', 'RegistrationController@store');


Route::post('get-regions', 'RegistrationController@getRegions');
Route::post('get-cities', 'RegistrationController@getCities');


Route::get('fawry2', 'CartController@fawry2');


Route::get('complete', 'CartController@complete')->name('complete');
Route::post('complete', 'CartController@completestore');

Route::group(['middleware' => ['auth']], function () {
    /**
     * Logout Route
     */
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
});
//Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');

Route::get('forgot/{id}/{token}', 'HomeController@forgot')->name('forgot/{id}/{token}');
Route::post('forgot/{id}', 'HomeController@updateforgot');
Route::post('/addSubscriberEmail', 'HomeController@addSubscriberEmail');
Route::post('/sendMessageEmail', 'HomeController@sendMessageEmail');
Route::get('paySuccess', 'HomeController@paySuccess')->name('paySuccess');
Route::get('/subscribers', 'HomeController@subscribers')->name('subscribers');

Route::get('/lang/{locale}', [HomeController::class, 'lang']);
// Route::get('/', function () {
//     return redirect((session()->get('locale'))?session()->get('locale'):app()->getLocale());
// });
//Route::post('/get-regions', [HomeController::class, 'getregions']);

// Route::group([
// 	'prefix' => '{locale}',
// 	'where' => ['locale' => '[a-zA-Z]{2}'],
// 	'middleware' => 'setlocale'], function() {

// 	})->defaults('locale', 'ar');

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/plan', [HomeController::class, 'plan']);
Route::get('/free', [HomeController::class, 'free']);
Route::get('/premium', [HomeController::class, 'premium']);
Route::get('/about', [HomeController::class, 'about']);
Route::get('/segment_commission', [HomeController::class, 'segment_commission']);
Route::get('/terms', [HomeController::class, 'terms']);
Route::get('/privacy', [HomeController::class, 'privacy']);
Route::get('/refund', [HomeController::class, 'refund']);
Route::get('/increase_income', [HomeController::class, 'increaseIncome']);
Route::get('/digitalBrochure', [DigitalBrochureController::class, 'digitalBrochure']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/specialoffers', [ProductController::class, 'specialoffers']);
Route::get('/bestseller', [ProductController::class, 'bestseller']);
Route::get('/insider', [ProductController::class, 'insider']);
Route::get('/blog', [ProductController::class, 'blog']);
Route::get('/start_business', [ProductController::class, 'start_business']);
Route::get('/common_questions', [ProductController::class, 'common_questions']);
Route::get('/fast_facts', [ProductController::class, 'fast_facts']);
Route::get('/testimonials', [ProductController::class, 'testimonials']);
Route::get('/product-details/{id}', [ProductController::class, 'productDetails']);
Route::post('saveProductToCart', [CartController::class, 'saveProductToCart'])->name('saveProductToCart');
Route::get('/how', [HomeController::class, 'how']);
Route::post('/contact-us', [HomeController::class, 'contactUs'])->name('contactUs');
Route::get('/contact-us', [HomeController::class, 'contact_us'])->name("contact_us");
Route::get('/wishlist', [HomeController::class, 'wishlist'])->name("wishlist");
Route::post('/singUpFreeHandel/', [HomeController::class, 'singUpFreeHandel'])->name('singUpFreeHandel');
Route::post('/singUpPremiumHandel/', [HomeController::class, 'singUpPremiumHandel'])->name('singUpPremiumHandel');
Route::get('/singUpFree/{id}/{token}', [HomeController::class, 'singUpFree'])->name('singUpFree');
Route::get('/singUpPremium/{id}/{token}', [HomeController::class, 'singUpPremium'])->name('singUpPremium');

Route::get('/signup', function () {
    return view('signup');
});
Route::get('/category', [HomeController::class, 'category']);
Route::get('/deleteOldPendingOrder', [HomeController::class, 'deleteOldPendingOrder']);
Route::get('/changeOrderChargeStatusJob', [HomeController::class, 'changeOrderChargeStatusJob']);

Route::get('/page', function () {
    return view('index');
});

Route::get('/thank', [HomeController::class, 'thank'])->name('thank');
Route::get('/thanksPay', [HomeController::class, 'thanksPay'])->name('thanksPay');
Route::get('/signIn', [HomeController::class, 'signIn'])->name('signIn');
Route::post('/signInHandel', [HomeController::class, 'signInHandel'])->name('signInHandel');
Route::get('/testorcal', [HomeController::class, 'testorcal'])->name('testorcal');
