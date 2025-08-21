<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\KarigarController;
use App\Http\Controllers\PatternController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolepermissionController;
use App\Http\Controllers\PcodeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UomController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\StoneController;
use App\Http\Controllers\CustomerorderController;
use App\Http\Controllers\CustomerordertempController;
use App\Http\Controllers\DaywisereportController;
use App\Http\Controllers\FinishedproductpdiController;
use App\Http\Controllers\FinishproductreceivedentryController;
use App\Http\Controllers\IssuetokarigarController;
use App\Http\Controllers\ItemdescriptionheaderController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MetalController;
use App\Http\Controllers\MetalissueentryController;
use App\Http\Controllers\MetalpurityController;
use App\Http\Controllers\MetalreceiveentriesController;
use App\Http\Controllers\MiscellaneousController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\QualitycheckController;
use App\Http\Controllers\RolepermissionuserController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockoutpdilistController;
use App\Http\Controllers\TollerenceController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VouchertypeController;
use Illuminate\Support\Facades\Artisan;
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/config-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return 'Config cache cleared';
});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
    return 'storage link clear';
});

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');
//Route::get('dashboard', [AuthController::class, 'dashboard']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [AuthController::class, 'dashboard']);
    Route::resource('users', UserController::class);
    Route::resource('rolepermissionusers', RolepermissionuserController::class);
    //Route::resource('rolepermissions', RolepermissionController::class);
    Route::resource('pcodes', PcodeController::class);
    Route::resource('uoms', UomController::class);
    Route::resource('sizes', SizeController::class);
    Route::resource('patterns', PatternController::class);
    Route::resource('stones', StoneController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('products', ProductController::class);
    Route::post('getitemheaderdescription', [ProductController::class, 'getitemheaderdescription'])->name('getitemheaderdescription');
    Route::post('getpcode', [ProductController::class, 'getpcode'])->name('getpcode');
    Route::post('getsize', [ProductController::class, 'getsize'])->name('getsize');
    Route::post('deleteproductstone', [ProductController::class, 'deleteproductstone'])->name('deleteproductstone');
    Route::post('getsizepcodewise', [ProductController::class, 'getsizepcodewise'])->name('getsizepcodewise');


    Route::resource('karigars', KarigarController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('vouchertypes', VouchertypeController::class);
    Route::post('getlocationwisevoucherno', [VouchertypeController::class, 'getlocationwisevoucherno'])->name('getlocationwisevoucherno');

    Route::resource('customerorders', CustomerorderController::class);
    Route::post('customerordersimporttxt', [CustomerorderController::class, 'customerordersimporttxt'])->name('customerordersimporttxt');
    Route::post('getproductinfo', [CustomerorderController::class, 'getproductinfo'])->name('getproductinfo');
    // Route::post('getproductinfo', [CustomerorderController::class, 'getproductinfo'])->name('customerorders.store.manual');

    Route::resource('customerordertemps', CustomerordertempController::class);
    Route::post('customerorderstempimporttxt', [CustomerordertempController::class, 'customerorderstempimporttxt'])->name('customerorderstempimporttxt');
    Route::delete('/customerordertemps/delete/{jo_no}', [CustomerordertempController::class, 'destroy'])->name('customerordertemps.delete');
    Route::post('savetempproducts', [CustomerordertempController::class, 'savetempproducts'])->name('savetempproducts');


    Route::resource('issuetokarigars', IssuetokarigarController::class);
    Route::post('getorderno', [IssuetokarigarController::class, 'getorderno'])->name('getorderno');
    Route::post('getorderitems', [IssuetokarigarController::class, 'getorderitems'])->name('getorderitems');
    Route::get('/issuetokarigars/view/{jo_no}/{kid}/{issue_to_karigar_id}', [IssuetokarigarController::class, 'view'])->name('issuetokarigars.view');
    Route::get('/issuetokarigars/print/{jo_no}/{kid}/{issue_to_karigar_id}', [IssuetokarigarController::class, 'print'])->name('issuetokarigars.print');

    Route::resource('metals', MetalController::class);
    Route::resource('metalpurities', MetalpurityController::class);
    Route::resource('metalreceiveentries', MetalreceiveentriesController::class);
    Route::post('getcustomerdetails', [MetalreceiveentriesController::class, 'getcustomerdetails'])->name('getcustomerdetails');
    Route::post('getmetalpurity', [MetalreceiveentriesController::class, 'getmetalpurity'])->name('getmetalpurity');

    Route::resource('metalissueentries', MetalissueentryController::class);
    Route::post('getmetalname', [MetalissueentryController::class, 'getmetalname'])->name('getmetalname');
    Route::post('getmetalpuritymetalissue', [MetalissueentryController::class, 'getmetalpuritymetalissue'])->name('getmetalpuritymetalissue');
    Route::post('getmetalpurityinfo', [MetalissueentryController::class, 'getmetalpurityinfo'])->name('getmetalpurityinfo');
    Route::post('getkarigardetails', [MetalissueentryController::class, 'getkarigardetails'])->name('getkarigardetails');
    Route::post('getmetalpuritydistinct', [MetalissueentryController::class, 'getmetalpuritydistinct'])->name('getmetalpuritydistinct');
    Route::post('/get-metal-receive-weight', [MetalissueentryController::class, 'gettotalmetalreceiveweight'])->name('gettotalmetalreceiveweight');


    Route::resource('itemdescriptionheaders', ItemdescriptionheaderController::class);
    Route::resource('tollerences', TollerenceController::class);

    // For Master START

    // For Transaction
    Route::resource('finishproductreceivedentries', FinishproductreceivedentryController::class);
    Route::post('getissuetokarigaritems', [FinishproductreceivedentryController::class, 'getissuetokarigaritems'])->name('getissuetokarigaritems');
    Route::post('getkarigardetailsissuetokarigaritems', [FinishproductreceivedentryController::class, 'getkarigardetailsissuetokarigaritems'])->name('getkarigardetailsissuetokarigaritems');

    // For Qualitycheck
    Route::resource('qualitychecks', QualitycheckController::class);
    Route::post('getissuetokarigaritemdetails', [QualitycheckController::class, 'getissuetokarigaritemdetails'])->name('getissuetokarigaritemdetails');
    Route::post('getissuetokarigaritemcodedetails', [QualitycheckController::class, 'getissuetokarigaritemcodedetails'])->name('getissuetokarigaritemcodedetails');
    // For Qualitycheck END

    // For Finished product pdi Start
    Route::resource('finishedproductpdis', FinishedproductpdiController::class);
    // For Finished product pdi END

    // For Finished product pdi Start
    Route::resource('stockoutpdilists', StockoutpdilistController::class);
    // For Finished product pdi END

    // Day Wise Report Start
    Route::resource('daywisereport', DaywisereportController::class);
    Route::get('/export-daywise-report', [DayWiseReportController::class, 'exportDayWiseReport'])->name('export.daywise.report');
    // Day Wise Report END


    Route::resource('vendors', VendorController::class);
    Route::resource('miscellaneouses', MiscellaneousController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::resource('sales', SaleController::class);
    Route::get('/sales/{id}/invoice', [SaleController::class, 'invoice'])->name('sales.invoice');
});
