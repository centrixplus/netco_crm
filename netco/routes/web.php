<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\WarehousepersionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\MenuroleController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\PaymenttypeController;
use App\Http\Controllers\PaymentbankController;
use App\Http\Controllers\PrintController;

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

Route::get('/clearcache', function() {
	Artisan::call('cache:clear');
	Artisan::call('config:clear');
	Artisan::call('route:clear');
	Artisan::call('view:clear');
	return "Cleared!";
});

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('admin');
Route::get('/dashboard', [HomeController::class, 'index'])->name('admin');
Route::post('/dashboard', [HomeController::class, 'index'])->name('admin');
Route::get('/refreshDashboard/{fromdate}/{todate}', [HomeController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard/warehouse', [HomeController::class, 'warehouse'])->name('warehouse');
Route::post('/dashboard/warehouse', [HomeController::class, 'warehouse'])->name('warehouse');
Route::get('/warehouseOrders/{customer_id}/{location_id}', [HomeController::class, 'warehouseOrders'])->name('warehouseOrders');

Route::get('/customer', [CustomerController::class, 'index'])->name('customer');
Route::post('/customer/store', [CustomerController::class, 'store']);
Route::get('/customer/{id}/delete', [CustomerController::class, 'destroy']);
Route::get('/customerinfo/{id}', [CustomerController::class, 'customerinfo']);


Route::get('/products', [ProductsController::class, 'index'])->name('products');
Route::post('/products/store', [ProductsController::class, 'store']);
Route::get('/products/{id}/delete', [ProductsController::class, 'destroy']);
Route::get('/stock', [ProductsController::class, 'stock'])->name('stock');
Route::post('/stock/store', [ProductsController::class, 'stockstore']);
Route::post('/products/upload', [ProductsController::class, 'upload']);
Route::post('/productsupload', [ProductsController::class, 'productsupload']);

Route::get('/sale', [SaleController::class, 'index'])->name('sale');
Route::post('/sale', [SaleController::class, 'index'])->name('sale');
Route::get('/sale/create', [SaleController::class, 'create'])->name('sale');
Route::get('/sale/new', [SaleController::class, 'newsale'])->name('sale');
Route::get('/appendsalerow/{sale_line_count}', [SaleController::class, 'appendsalerow']);
Route::get('/appendsalerownew/{sale_line_count}', [SaleController::class, 'appendsalerownew']);
Route::get('/getproductinfo/{id}', [SaleController::class, 'getproductinfo']);
Route::post('/sale/store', [SaleController::class, 'store']);
Route::get('/sale/{id}/delete', [SaleController::class, 'destroy']);
Route::get('/sale/{id}/{process}', [SaleController::class, 'sale'])->name('sale');
Route::post('/updatestatus', [SaleController::class, 'updatestatus']);
Route::get('/showsales/{id}', [SaleController::class, 'showsales']);
Route::get('/loadLastPurchase/{product_id}/{customer_id}', [SaleController::class, 'loadLastPurchase']);
Route::get('/salestatus/{status_id}', [SaleController::class, 'statussale'])->name('statussale');
Route::post('/salestatus/{status_id}', [SaleController::class, 'statussale'])->name('statussale');
Route::post('/assignperson', [SaleController::class, 'assignperson']);
Route::post('/searchsaleparts', [SaleController::class, 'searchsaleparts'])->name('searchsaleparts');
Route::get('/searchsaleparts', [SaleController::class, 'create'])->name('sale');
Route::get('/addSaleList/{brand_id}/{sale_line_count}/{search_text}', [SaleController::class, 'addSaleList'])->name('addSaleList');

Route::get('/user', [UserController::class, 'index']);
Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::post('/user/store', [UserController::class, 'store']);
Route::post('/user/update', [UserController::class, 'update']);

Route::get('/report/sales', [ReportController::class, 'sales'])->name('reportsales');
Route::post('report/sales', [ReportController::class, 'sales']);
Route::get('/report/productsales', [ReportController::class, 'productsales'])->name('productsales');
Route::post('/report/productsales', [ReportController::class, 'productsales']);
Route::get('/report/los', [ReportController::class, 'los'])->name('los');
Route::post('report/los', [ReportController::class, 'los']);
Route::get('/report/location', [ReportController::class, 'location'])->name('location');
Route::post('report/location', [ReportController::class, 'location']);
Route::get('/report/status', [ReportController::class, 'status'])->name('status');
Route::post('report/status', [ReportController::class, 'status']);

Route::get('/location', [LocationController::class, 'index'])->name('location');
Route::post('/location/store', [LocationController::class, 'store']);
Route::get('/location/{id}/delete', [LocationController::class, 'destroy']);

Route::get('/brand', [BrandController::class, 'index'])->name('brand');
Route::post('/brand/store', [BrandController::class, 'store']);
Route::get('/brand/{id}/delete', [BrandController::class, 'destroy']);

Route::get('/country', [CountryController::class, 'index'])->name('country');
Route::post('/country/store', [CountryController::class, 'store']);
Route::get('/country/{id}/delete', [CountryController::class, 'destroy']);
Route::get('/active_user', [CountryController::class, 'activeUsers'])->name('activeUsers');
Route::get('/activelist', [CountryController::class, 'activelist'])->name('activelist');

Route::get('/company', [CompanyController::class, 'index'])->name('company');
Route::post('/company/store', [CompanyController::class, 'store']);
Route::get('/company/{id}/delete', [CompanyController::class, 'destroy']);

Route::get('/branch', [BranchController::class, 'index'])->name('branch');
Route::post('/branch/store', [BranchController::class, 'store']);
Route::get('/branch/{id}/delete', [BranchController::class, 'destroy']);

Route::get('/paymenttype', [PaymenttypeController::class, 'index'])->name('paymenttype');
Route::post('/paymenttype/store', [PaymenttypeController::class, 'store']);
Route::get('/paymenttype/{id}/delete', [PaymenttypeController::class, 'destroy']);

Route::get('/paymentbank', [PaymentbankController::class, 'index'])->name('paymentbank');
Route::post('/paymentbank/store', [PaymentbankController::class, 'store']);
Route::get('/paymentbank/{id}/delete', [PaymentbankController::class, 'destroy']);

Route::get('/receipt', [ReceiptController::class, 'index'])->name('receipt');
Route::get('/authorizedreceipt', [ReceiptController::class, 'posted'])->name('authorizedreceipt');
Route::get('/authorized', [ReceiptController::class, 'authorized'])->name('authorized');
Route::get('/reconciledreceipt', [ReceiptController::class, 'reconciled'])->name('reconciled');
Route::post('/reconciledreceipt', [ReceiptController::class, 'reconciled'])->name('reconciled');
Route::get('/bouncedreceipt', [ReceiptController::class, 'bouncedreceipt'])->name('bouncedreceipt');
Route::post('/bouncedreceipt', [ReceiptController::class, 'bouncedreceipt'])->name('bouncedreceipt');
Route::post('/receipt/store', [ReceiptController::class, 'store']);
Route::get('/receiptview/{id}', [ReceiptController::class, 'view']);
Route::get('/receiptprint/{id}', [PrintController::class, 'receiptprint']);
Route::get('/receipt/{id}/delete', [ReceiptController::class, 'destroy']);
Route::get('/customerinforeceipt/{id}', [ReceiptController::class, 'customerinforeceipt']);
Route::post('/receiptlog/store', [ReceiptController::class, 'receiptlogstore']);
Route::get('/receiptdashboard', [ReceiptController::class, 'receiptdashboard'])->name('receiptdashboard');
Route::post('/receiptdashboard', [ReceiptController::class, 'receiptdashboard']);
Route::get('/receipt/report', [ReceiptController::class, 'receiptreport'])->name('receiptreport');
Route::post('/receipt/report', [ReceiptController::class, 'receiptreport']);
Route::get('/updatereconcil/{val}', [ReceiptController::class, 'updatereconcil']);
Route::get('/customersearch/{company_id}/{brand_id}', [ReceiptController::class, 'customersearch'])->name('customersearch');

Route::get('/category', [CategoryController::class, 'index'])->name('category');
Route::post('/category/store', [CategoryController::class, 'store']);
Route::get('/category/{id}/delete', [CategoryController::class, 'destroy']);

Route::get('/subcategory', [SubcategoryController::class, 'index'])->name('subcategory');
Route::post('/subcategory/store', [SubcategoryController::class, 'store']);
Route::get('/subcategory/{id}/delete', [SubcategoryController::class, 'destroy']);

Route::get('/warehousepersion', [WarehousepersionController::class, 'index'])->name('warehousepersion');
Route::post('/warehousepersion/store', [WarehousepersionController::class, 'store']);
Route::get('/warehousepersion/{id}/delete', [WarehousepersionController::class, 'destroy']);

Route::get('/enquiry', [SearchController::class, 'index']);
Route::post('/enquiry', [SearchController::class, 'index'])->name('enquiry');
Route::get('/enquirycart/{id}/delete', [SearchController::class, 'destroy']);
Route::get('/enquiryrequest', [SearchController::class, 'enquiryrequest']);
Route::get('/enquiryrequestlist', [EnquiryController::class, 'index'])->name('enquiryrequestlist');
Route::post('/enquiryrequestlist', [EnquiryController::class, 'index'])->name('enquiryrequestlist');
Route::get('/showenquiryrequest/{id}', [EnquiryController::class, 'show']);
Route::get('/enquiry/{id}/delete', [EnquiryController::class, 'destroy']);

Route::get('/menurole', [MenuroleController::class, 'index'])->name('menurole');
Route::post('/menurole', [MenuroleController::class, 'index'])->name('menurole');
Route::get('/menuroleupdate/{id}/{val}', [MenuroleController::class, 'update']);
