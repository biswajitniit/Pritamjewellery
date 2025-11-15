<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerorderController;
use App\Http\Controllers\CustomerOrderItemController;
use App\Http\Controllers\CustomerordertempController;
use App\Http\Controllers\DaywisereportController;
use App\Http\Controllers\FinancialYearController;
use App\Http\Controllers\FinishedproductpdiController;
use App\Http\Controllers\FinishproductreceivedentryController;
use App\Http\Controllers\IssuetokarigarController;
use App\Http\Controllers\ItemcodedetailorderController;
use App\Http\Controllers\ItemdescriptionheaderController;
use App\Http\Controllers\KarigarController;
use App\Http\Controllers\KarigarDetailController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MetalController;
use App\Http\Controllers\MetalissueentryController;
use App\Http\Controllers\MetalpurityController;
use App\Http\Controllers\MetalreceiveentriesController;
use App\Http\Controllers\MiscellaneousController;
use App\Http\Controllers\PatternController;
use App\Http\Controllers\PcodeController;
use App\Http\Controllers\PendinglistController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\QualitycheckController;
use App\Http\Controllers\RolepermissionController;
use App\Http\Controllers\RolepermissionuserController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\StockEffectController;
use App\Http\Controllers\StockoutpdilistController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\StoneController;
use App\Http\Controllers\TollerenceController;
use App\Http\Controllers\TransactionReportController;
use App\Http\Controllers\UomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VouchertypeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/optimize-clear', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('optimize:clear');

    return '✅ Config, Cache, View, Route cleared & optimized successfully!';
});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');

    return 'storage link clear';
});

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');
// Route::get('dashboard', [AuthController::class, 'dashboard']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [AuthController::class, 'dashboard']);
    Route::resource('users', UserController::class);
    Route::resource('rolepermissionusers', RolepermissionuserController::class);
    // Route::resource('rolepermissions', RolepermissionController::class);

    Route::resource('financial-years', FinancialYearController::class);

    Route::resource('pcodes', PcodeController::class);
    Route::resource('uoms', UomController::class);
    Route::resource('sizes', SizeController::class);
    Route::resource('patterns', PatternController::class);
    Route::resource('stones', StoneController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('companies', CompanyController::class);
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
    Route::post('/customerorders/store-manual', [CustomerOrderController::class, 'storeManual'])->name('customerorders.store.manual');

    Route::delete('/customerorderitems/{id}', [CustomerOrderItemController::class, 'destroy']);
    Route::get('/get-minmax-weight/{item_code}', [CustomerOrderItemController::class, 'getMinMaxWeight']);

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
    Route::post('getitemlist', [MetalreceiveentriesController::class, 'getitemlist'])->name('getitemlist');

    Route::resource('metalissueentries', MetalissueentryController::class);
    Route::post('getmetalname', [MetalissueentryController::class, 'getmetalname'])->name('getmetalname');
    Route::post('getmetalpuritymetalissue', [MetalissueentryController::class, 'getmetalpuritymetalissue'])->name('getmetalpuritymetalissue');
    Route::post('getmetalpurityinfo', [MetalissueentryController::class, 'getmetalpurityinfo'])->name('getmetalpurityinfo');
    Route::post('getkarigardetails', [MetalissueentryController::class, 'getkarigardetails'])->name('getkarigardetails');
    Route::post('getmetalpuritydistinct', [MetalissueentryController::class, 'getmetalpuritydistinct'])->name('getmetalpuritydistinct');
    Route::post('/get-metal-receive-weight', [MetalissueentryController::class, 'gettotalmetalreceiveweight'])->name('gettotalmetalreceiveweight');
    Route::post('getitemlistissuetokarigar', [MetalissueentryController::class, 'getitemlistissuetokarigar'])->name('getitemlistissuetokarigar');
    Route::post('getmetalpuritygroup', [MetalissueentryController::class, 'getmetalpuritygroup'])->name('getmetalpuritygroup');
    Route::post('getmetalpuritylist', [MetalissueentryController::class, 'getmetalpuritylist'])->name('getmetalpuritylist');
    Route::get('/check-stock', [MetalissueentryController::class, 'checkStock'])->name('metalissueentries.checkStock');


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
    Route::post('getordertype', [QualitycheckController::class, 'getordertype'])->name('getordertype');
    // For Qualitycheck END

    // For Finished product pdi Start
    Route::resource('finishedproductpdis', FinishedproductpdiController::class);
    // For Finished product pdi END

    // For Finished product pdi Start
    Route::resource('stockoutpdilists', StockoutpdilistController::class);
    // For Finished product pdi END

    Route::resource('vendors', VendorController::class);
    Route::resource('miscellaneouses', MiscellaneousController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::resource('sales', SaleController::class);
    Route::get('/sales/{id}/invoice', [SaleController::class, 'invoice'])->name('sales.invoice');

    Route::get('/purchase-ledger', [TransactionReportController::class, 'purchase_ledger'])->name('purchase-ledger');
    Route::get('/purchase-ledger/{id}/items', [TransactionReportController::class, 'purchase_ledger_items'])->name('purchase-ledger.items');
    Route::get('/sales-register', [TransactionReportController::class, 'sales_register'])->name('sales-register');
    Route::get('/sales-register/{id}/items', [TransactionReportController::class, 'sales_register_items'])->name('sales-register.items');

    // Report Start
    /* Pending list */
    Route::resource('pendinglist', PendinglistController::class);
    Route::resource('stockeffect', StockEffectController::class);

    // Report Start
    Route::resource('stock-transfers', StockTransferController::class);
    Route::post('getlocationwisestockeffectitemname', [StockTransferController::class, 'getlocationwisestockeffectItemname'])->name('getlocationwisestockeffectitemname');
    Route::post('gettolocation', [StockTransferController::class, 'gettolocation'])->name('gettolocation');
    Route::post('getstockavailable', [StockTransferController::class, 'getstockavailable'])->name('getstockavailable');

    Route::resource('daywisereport', DaywisereportController::class);
    Route::get('/export-daywise-report', [DayWiseReportController::class, 'exportDayWiseReport'])->name('export.daywise.report');

    Route::resource('itemcodedetailorder', ItemcodedetailorderController::class);
    Route::get('/export-itemcodedetailorder-report', [ItemcodedetailorderController::class, 'exportItemcodedetailorder'])->name('export.itemcodedetailorder.report');

    // Report END
    Route::get('/new-report', [TransactionReportController::class, 'report_new'])->name('new-report');
    Route::get('ledger-names', [TransactionReportController::class, 'getLedgerNames'])->name('ledger.names');

    Route::get('/transaction-reports/generate', [TransactionReportController::class, 'generateReport'])->name('transaction.report.generate');
    Route::post('/export-ledger-excel', [TransactionReportController::class, 'exportToExcel'])->name('export.ledger.excel');

    Route::get('/item-code-wise-karigar-details', [KarigarDetailController::class, 'itemCodeWiseDetail'])->name('karigar.itemcodes');
    Route::get('/item-code-wise-karigar-details-report', [KarigarDetailController::class, 'generateReport'])->name('karigar.itemcodes.report');
    Route::post('/item-code-wise-karigar-details-excel', [KarigarDetailController::class, 'exportToExcel'])->name('karigar.itemcodes.export');

    Route::get('/job-wise-delivary-details', [KarigarDetailController::class, 'jobWiseDetail'])->name('karigar.jobdetails');
    Route::get('/job-wise-delivary-details-report', [KarigarDetailController::class, 'generateJobReport'])->name('karigar.jobdetails.report');
    Route::post('/job-wise-delivary-details-excel', [KarigarDetailController::class, 'exporJobReporttToExcel'])->name('karigar.jobdetails.export');

    Route::get('/quality-check', [KarigarDetailController::class, 'qualityCheck'])->name('karigar.qualitycheck');
    Route::get('/quality-check-report', [KarigarDetailController::class, 'generateQualityCheckReport'])->name('karigar.qualitycheck.report');
    Route::post('/quality-check-excel', [KarigarDetailController::class, 'exportQualityCheckToExcel'])->name('karigar.qualitycheck.export');
});
