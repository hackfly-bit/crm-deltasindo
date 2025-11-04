<?php

use App\Http\Controllers\CallController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EventLogController;
use App\Http\Controllers\KegiatanOtherController;
use App\Http\Controllers\KegiatanVisitController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PresentasiController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SphController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\UserController;
use App\Models\Category\Principal;
use FontLib\Table\Type\name;

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

Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('register', [UserController::class, 'register_action'])->name('register.action');
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('login', [UserController::class, 'login_action'])->name('login.action');
Route::get('/password', [UserController::class, 'password'])->name('password');
Route::post('password', [UserController::class, 'password_action'])->name('password.action');
Route::get('logout', [UserController::class, 'logout'])->name('logout');


Route::group(['middleware' => ['auth']], function () {
  Route::get('/', 'MainController@index')->name('dashboard');
  Route::get('/setting-select', 'MainController@setting_select')->name('setting.select')->middleware('role:admin|manager|supervisor');
  Route::get('/user', 'MainController@user')->name('setting.user')->middleware('role:admin|manager|supervisor');
  Route::get('/user/edit/{id}', 'UserController@editUser')->name('setting.user.edit');
  Route::put('/user/update/{id}', 'UserController@updateUser')->name('setting.user.update');
  Route::delete('/user/delete/{id}', 'UserController@deleteUser')->name('setting.user.delete');
  Route::get('/profile/{id}', 'UserController@profile')->name('user.profile');

  // Router Produk 
  Route::get('/create-produk' ,'ProductController@create')->name('user.create-produk');
  Route::post('/post-produk' ,'ProductController@store')->name('user.post-produk');
  Route::get('/edit-produk/{id}' ,'ProductController@edit')->name('user.edit-produk');
  Route::put('/update-produk/{id}' ,'ProductController@update')->name('user.update-produk');
  Route::delete('/destroy-produk/{id}','ProductController@destroy')->name('user.destroy-produk');
  // Router Brand
  Route::get('/create-brand' ,'ProductController@createPrincipal')->name('user.create-principal');
  Route::post('/post-brand' ,'ProductController@storePrincipal')->name('user.post-principal');
  Route::get('/edit-brand/{id}' ,'ProductController@editPrincipal')->name('user.edit-principal');
  Route::put('/update-brand/{id}' ,'ProductController@updatePrincipal')->name('user.update-principal');
  Route::delete('/destroy-brand/{id}','ProductController@destroyPrincipal')->name('user.destroy-principal');

  Route::get('/excel-date', 'ReportController@generateReport')->name('generateReport');
  Route::get('/export-data-by-model', 'ReportController@generateReportByModel')->name('generateReportByModel');

  // Router For View Dasboard Card

  Route::get('/customer-view', 'MainController@view_customer')->name('dashboard.customer');
  Route::get('/call-view', 'MainController@view_call')->name('dashboard.call');
  Route::get('/visit-view', 'MainController@view_visit')->name('dashboard.visit');
  Route::get('/presentasi-view', 'MainController@view_presentasi')->name('dashboard.presentasi');
  Route::get('/quotation-view', 'MainController@view_quotation')->name('dashboard.quotation');
  Route::get('/po-view', 'MainController@view_po')->name('dashboard.po');

  // Custom View 
  Route::get('/quotation-all', 'MainController@custom_view_quotation')->name('quotation.custom');

  // End Router for View Dasboard Card


  Route::get('/products/{brand}', function (Principal $brand) {
    $products = $brand->products;

    return response()->json($products);
  });


  //  Call Route

  Route::controller(CallController::class)->name('call.')->prefix('call')->group(function () {
    Route::post('/store', 'store')->name('store');
    Route::get('edit/{customer_id}/{id}', 'edit')->name('edit');
    Route::put('/update/{id}', 'update')->name('update');
    Route::delete('/destroy/{customer_id}/{id}', 'destroy')->name('destroy');
  });

  // Quotation 

//  Route::controller(QuotationController::class)->name('quotation.')->prefix('quotation')->group(function () {
//   //   Route::post('/store', 'store')->name('store');
//   //   Route::put('/update/{id}', 'update')->name('update');
//   //   Route::delete('/destroy/{customer_id}/{id}', 'destroy')->name('destroy');
//   });

  // Presentasi Route
  Route::controller(PresentasiController::class)->name('presentasi.')->prefix('presentasi')->group(function(){
    Route::post('/store', 'store')->name('store');
    Route::get('edit/{customer_id}/{id}', 'edit')->name('edit');
    Route::put('/update/{id}', 'update')->name('update');
    Route::delete('/destroy/{customer_id}/{id}', 'destroy')->name('destroy');
  });

  // Preorder Route

  Route::controller(PreorderController::class)->name('preorder.')->prefix('preorder')->group(function(){
    Route::post('/store', 'store')->name('store');
    Route::get('edit/{customer_id}/{id}', 'edit')->name('edit');
    Route::put('/update/{id}', 'update')->name('update');
    Route::delete('/destroy/{customer_id}/{id}', 'destroy')->name('destroy');
  });

  Route::controller(CustomerController::class)->name('customer.')->prefix('customer')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('create', 'create')->name('create');
    Route::get('show/{id}', 'show')->name('single');
    Route::get('edit/{id}', 'edit')->name('edit');
    Route::get('call/{id}', 'call')->name('call');
    Route::get('visit/{id}', 'visit')->name('visit');
    Route::get('qutation/{id}','quotation')->name('quotation');
    Route::get('history/{id}', 'history')->name('history');
    Route::get('presentasi/{id}','presentasi')->name('presentasi');
    Route::get('preorder/{id}','preorder')->name('preorder');
    Route::get('sph/{id}', 'sph')->name('sph');

    // Post Method
    Route::post('/store', 'store')->name('store');
    Route::put('/update/{id}', 'update')->name('update');
    Route::delete('/destroy/{id}', 'destroy')->name('destroy');
  });

  Route::controller(KegiatanVisitController::class)->name('visit.')->prefix('visit')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('create', 'create')->name('create');
    Route::get('show/{id}', 'show')->name('single');
    Route::get('edit/{customer_id}/{id}', 'edit')->name('edit');
    Route::get('history/{id}', 'history')->name('history');

    // Post Method
    Route::post('/store', 'store')->name('store');
    Route::put('/update/{id}', 'update')->name('update');
    Route::delete('/destroy/{customer_id}/{id}', 'destroy')->name('destroy');
  });

  Route::controller(KegiatanOtherController::class)->name('other.')->prefix('other')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('create', 'create')->name('create');
    Route::get('show/{id}', 'show')->name('single');
    Route::get('edit/{id}', 'edit')->name('edit');
    Route::get('history/{id}', 'history')->name('history');

    // Post Method
    Route::post('/store', 'store')->name('store');
    Route::put('/update/{id}', 'update')->name('update');
    Route::delete('/destroy/{id}', 'destroy')->name('destroy');
  });

  Route::controller(SphController::class)->name('sph.')->prefix('sph')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('create', 'create')->name('create');
    Route::get('show/{id}', 'show')->name('single');
    Route::get('edit/{customer_id}/{id}', 'edit')->name('edit');
    Route::get('history/{id}', 'history')->name('history');

    // Post Method
    Route::post('/store', 'store')->name('store');
    Route::put('/update/{id}', 'update')->name('update');
    Route::delete('/destroy/{customer_id}/{id}', 'destroy')->name('destroy');
  });

  Route::controller(EventLogController::class)->name('history.')->prefix('history')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('create', 'create')->name('create');
    Route::get('show/{id}', 'show')->name('single');
    Route::get('edit/{id}', 'edit')->name('edit');

    // Post Method
    Route::post('/store', 'store')->name('store');
    Route::put('/update/{id}', 'update')->name('update');
    Route::delete('/destroy/{id}', 'destroy')->name('destroy');
  });

  Route::controller(ReportController::class)->name('report.')->prefix('report')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('create', 'create')->name('create');
    Route::get('show/{id}', 'show')->name('single');
    Route::get('edit/{id}', 'edit')->name('edit');
    Route::get('/pdf', 'generatePDF')->name('generatePDF');
    Route::get('/customer/{id}', 'show_report_by_customer')->name('reportCustomer');
    Route::get('sales-kpi/{id}','ReportController@generateExportSalesPerformance')->name('salesKpiReport');


    // Post Method
    Route::post('/store', 'store')->name('store');
    Route::put('/update/{id}', 'update')->name('update');
    Route::delete('/destroy/{id}', 'destroy')->name('destroy');
  });
});

//     Route::group(['prefix' => 'error'], function () {
//         Route::get('404', function () {
//             return view('pages.error.404');
//         });
//         Route::get('500', function () {
//             return view('pages.error.500');
//         });
//     });

    Route::get('/clear-cache', function () {
        Artisan::call('cache:clear');
        return "Cache is cleared";
    });

//     // 404 for undefined routes
//     // Route::any('/{page?}',function(){
//     //     return View::make('pages.error.404');
//     // })->where('page','.*');
// });
