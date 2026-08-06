<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
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
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UomController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\PackagetypeController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\PaymenttypeController;
use App\Http\Controllers\PaymentbankController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\PurchaserequestController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\PoController;
use App\Http\Controllers\GrnController;

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
Route::post('/clearleads', [ApiController::class, 'clearleads'])->name('clearleads');

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
Route::get('/dashboard/po', [HomeController::class, 'dashboardpo'])->name('dashboardpo');
Route::post('/dashboard/po', [HomeController::class, 'dashboardpo'])->name('dashboardpo');
Route::get('/dashboard/po/new', [HomeController::class, 'dashboardpoNew']);
Route::post('/dashboard/po/new', [HomeController::class, 'dashboardpoNew']);
Route::get('/api/po/details/{po_no}', [HomeController::class, 'getPoDetails']);
Route::get('/api/vendor/orders/{vendor_id}', [HomeController::class, 'getVendorOrders']);

Route::get('/customer', [CustomerController::class, 'index'])->name('customer');
Route::post('/customer/store', [CustomerController::class, 'store']);
Route::get('/customer/{id}/delete', [CustomerController::class, 'destroy']);
Route::get('/customerinfo/{id}', [CustomerController::class, 'customerinfo']);

Route::get('/vendor', [VendorController::class, 'index'])->name('vendor');
Route::post('/vendor/store', [VendorController::class, 'store']);
Route::get('/vendor/{id}/delete', [VendorController::class, 'destroy']);
Route::get('/vendorinfo/{id}', [VendorController::class, 'vendorinfo']);
Route::get('/vendorsearch/{company_id}/{brand_id}', [VendorController::class, 'vendorsearch'])->name('vendorsearch');

Route::get('/products', [ProductsController::class, 'index'])->name('products');
Route::post('/products/store', [ProductsController::class, 'store']);
Route::get('/products/{id}/delete', [ProductsController::class, 'destroy']);
Route::get('/stock', [ProductsController::class, 'stock'])->name('stock');
Route::post('/stock/store', [ProductsController::class, 'stockstore']);
Route::post('/products/upload', [ProductsController::class, 'upload']);
Route::get('/products/{id}/show', [ProductsController::class, 'show']);
Route::post('/productsupload', [ProductsController::class, 'productsupload']);
Route::post('/productshistoryupload', [ProductsController::class, 'productshistoryupload']);
Route::post('/productslatestupload', [ProductsController::class, 'productslatestupload']);
Route::post('/products/search', [ProductsController::class, 'index']);
Route::post('/productstock/search', [ProductsController::class, 'stock']);
Route::post('/products/price_stock_upload', [ProductsController::class, 'price_stock_upload']);
Route::get('/productQueryWindow', [ProductsController::class, 'productQueryWindow'])->name('productQueryWindow');
Route::post('/productsupload/dealerprice', [ProductsController::class, 'dealerpriceupload']);
Route::post('/productsinfoupload', [ProductsController::class, 'productsinfoupload']);



Route::get('/sale', [SaleController::class, 'index'])->name('sale');
Route::post('/sale', [SaleController::class, 'index'])->name('sale');
Route::get('/sale/create', [SaleController::class, 'create'])->name('sale');
Route::get('/sale/new', [SaleController::class, 'newsale'])->name('sale');
Route::get('/appendsalerow/{sale_line_count}', [SaleController::class, 'appendsalerow']);
Route::get('/appendsalerownew/{sale_line_count}', [SaleController::class, 'appendsalerownew']);
Route::get('/getproductinfo/{id}', [SaleController::class, 'getproductinfo']);
Route::get('/getproductinfo/{id}/{customer_id}', [SaleController::class, 'getproductinfo']);
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
Route::get('/report/uploadlogs', [ReportController::class, 'uploadlogs'])->name('uploadlogs');
Route::post('report/uploadlogs', [ReportController::class, 'uploadlogs']);
Route::get('/report/pilosreport', [ReportController::class, 'pilosreport'])->name('pilosreport');
Route::post('report/pilosreport', [ReportController::class, 'pilosreport']);
Route::get('/getproductwindow/{id}', [ReportController::class, 'getproductwindowinfo']);
Route::get('/report/pisalereport', [ReportController::class, 'pisalereport'])->name('pisalereport');
Route::post('report/pisalereport', [ReportController::class, 'pisalereport']);
Route::get('/viewuploadlog/{id}', [ReportController::class, 'viewuploadlog'])->name('viewuploadlog');
Route::get('/report/draftinvoice', [ReportController::class, 'draftinvoice'])->name('reportdraftinvoice');
Route::post('report/draftinvoice', [ReportController::class, 'draftinvoice']);
Route::get('/report/brand', [ReportController::class, 'brand'])->name('brand');
Route::post('report/brand', [ReportController::class, 'brand']);

Route::get('/location', [LocationController::class, 'index'])->name('location');
Route::post('/location/store', [LocationController::class, 'store']);
Route::get('/location/{id}/delete', [LocationController::class, 'destroy']);

Route::get('/brand', [BrandController::class, 'index'])->name('brand');
Route::post('/brand/store', [BrandController::class, 'store']);
Route::get('/brand/{id}/delete', [BrandController::class, 'destroy']);

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
Route::post('/enquiry/store', [SearchController::class, 'store'])->name('enquirystore');
Route::get('/enquirycart/{id}/delete', [SearchController::class, 'destroy']);
Route::post('/enquiryrequest', [SearchController::class, 'enquiryrequest'])->name('enquirysave');
Route::get('/enquiryrequestrest', [SearchController::class, 'enquiryrequestrest'])->name('enquiryrequestrest');
Route::get('/enquiryrequestlist', [EnquiryController::class, 'index'])->name('enquiryrequestlist');
Route::post('/enquiryrequestlist', [EnquiryController::class, 'index'])->name('enquiryrequestlist');
Route::get('/showenquiryrequest/{id}', [EnquiryController::class, 'show']);
Route::get('/enquirystage/update/{id}/{val}', [EnquiryController::class, 'updateEnquirystage']);
Route::get('/enquiry/{id}/delete', [EnquiryController::class, 'destroy']);
Route::get('/loadEnqlist/{id}', [EnquiryController::class, 'loadEnqlist']);
Route::get('/quote', [SearchController::class, 'quoteindex']);
Route::get('/quotecart/{id}/delete/{customer_id}', [SearchController::class, 'quotecartdestroy']);
Route::get('/quoterequestrest/{customer_id}', [SearchController::class, 'quoterequestrest'])->name('quoterequestrest');
Route::post('/updateinvoicestatus', [SearchController::class, 'updatepistatus'])->name('updateinvoicestatus');


Route::get('/createinvoice', [SearchController::class, 'createinvoice']);
Route::get('/loadInvoiceLines/{id}', [SearchController::class, 'loadInvoiceLines']);
Route::get('/updateInvoiceLine/{customer_id}/{product}/{order_quantity}/{so_quantity}/{target_price}/{is_invoiced}/{editid}/{invoice_id}', [SearchController::class, 'updateInvoiceLine']);
Route::post('/invoice/store', [SearchController::class, 'invoicestore'])->name('invoicestore');
Route::get('/deleteInvoiceLine/{id}', [SearchController::class, 'deleteInvoiceLine']);
Route::get('/invoice', [SearchController::class, 'invoice']);
Route::post('/invoice', [SearchController::class, 'invoice']);
Route::get('/viewInvoice/{id}', [SearchController::class, 'viewInvoice']);
Route::get('/deleteInvoice/{id}', [SearchController::class, 'deleteInvoice']);
Route::get('/invoiceapprove/{id}', [SearchController::class, 'invoiceapprove']);
Route::get('/invoicetoso/{id}', [SearchController::class, 'invoicelist']);
Route::get('/getPIOrderList/{id}', [SearchController::class, 'getPIOrderList']);
Route::get('/editinvoice/{id}', [SearchController::class, 'editinvoice']);
Route::get('/loadInvoiceLinesAppointment/{id}', [SearchController::class, 'loadInvoiceLinesAppointment']);
Route::post('/convertinvoicetoso', [SearchController::class, 'convertinvoicetoso'])->name('convertinvoicetoso');
Route::post('/invoice/update', [SearchController::class, 'invoiceupdate'])->name('invoiceupdate');

Route::post('/createquote', [EnquiryController::class, 'createquote']);
Route::get('/convertsaleorder/{id}', [EnquiryController::class, 'convertsaleorder']);

Route::get('/menurole', [MenuroleController::class, 'index'])->name('menurole');
Route::post('/menurole', [MenuroleController::class, 'index'])->name('menurole');
Route::get('/menuroleupdate/{id}/{val}', [MenuroleController::class, 'update']);

Route::get('/group', [GroupController::class, 'index'])->name('group');
Route::post('/group/store', [GroupController::class, 'store']);
Route::get('/group/{id}/delete', [GroupController::class, 'destroy']);

Route::get('/color', [ColorController::class, 'index'])->name('color');
Route::post('/color/store', [ColorController::class, 'store']);
Route::get('/color/{id}/delete', [ColorController::class, 'destroy']);

Route::get('/uom', [UomController::class, 'index'])->name('uom');
Route::post('/uom/store', [UomController::class, 'store']);
Route::get('/uom/{id}/delete', [UomController::class, 'destroy']);

Route::get('/packagetype', [PackagetypeController::class, 'index'])->name('packagetype');
Route::post('/packagetype/store', [PackagetypeController::class, 'store']);
Route::get('/packagetype/{id}/delete', [PackagetypeController::class, 'destroy']);

Route::get('/size', [SizeController::class, 'index'])->name('size');
Route::post('/size/store', [SizeController::class, 'store']);
Route::get('/size/{id}/delete', [SizeController::class, 'destroy']);

Route::get('/discount', [DiscountController::class, 'index'])->name('discount');
Route::post('/discount/store', [DiscountController::class, 'store']);
Route::get('/discount/{id}/delete', [DiscountController::class, 'destroy']);

Route::get('/quotationlist', [QuotationController::class, 'index'])->name('quotationlist');
Route::post('/quotationlist', [QuotationController::class, 'index'])->name('quotationlist');
Route::get('/quote/{id}', [QuotationController::class, 'quote']);
Route::get('/quoteapprove/{id}', [QuotationController::class, 'quoteapprove']);
Route::post('/enquirytoso/store', [QuotationController::class, 'store'])->name('enquirytosostore');

Route::get('/saleorder/{id}', [QuotationController::class, 'saleorder'])->name('createsaleorder');
Route::post('/linksaleorder', [QuotationController::class, 'linksaleorder'])->name('linksaleorder');
Route::get('/getQuoteOrderList/{ids}', [QuotationController::class, 'getQuoteOrderList'])->name('getQuoteOrderList');
Route::get('/convertedquote', [QuotationController::class, 'convertedquote'])->name('convertedquote');
Route::post('/convertedquote', [QuotationController::class, 'convertedquote'])->name('convertedquote');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::post('/settings/store', [SettingsController::class, 'store']);

Route::post('/getPart', [ProductsController::class, 'getPart'])->name('autocomplete');	

Route::get('/country', [CountryController::class, 'index'])->name('country');
Route::post('/country/store', [CountryController::class, 'store']);
Route::get('/country/{id}/delete', [CountryController::class, 'destroy']);

Route::get('/currency', [CurrencyController::class, 'index'])->name('currency');
Route::post('/currency/store', [CurrencyController::class, 'store']);
Route::get('/currency/{id}/delete', [CurrencyController::class, 'destroy']);

Route::get('/company', [CompanyController::class, 'index'])->name('company');
Route::post('/company/store', [CompanyController::class, 'store']);
Route::get('/company/{id}/delete', [CompanyController::class, 'destroy']);

Route::get('/branch', [BranchController::class, 'index'])->name('branch');
Route::post('/branch/store', [BranchController::class, 'store']);
Route::get('/branch/{id}/delete', [BranchController::class, 'destroy']);

Route::get('/store', [StoreController::class, 'index'])->name('store');
Route::post('/store/store', [StoreController::class, 'store']);
Route::get('/store/{id}/delete', [StoreController::class, 'destroy']);


Route::get('/paymenttype', [PaymenttypeController::class, 'index'])->name('paymenttype');
Route::post('/paymenttype/store', [PaymenttypeController::class, 'store']);
Route::get('/paymenttype/{id}/delete', [PaymenttypeController::class, 'destroy']);

Route::get('/paymentbank', [PaymentbankController::class, 'index'])->name('paymentbank');
Route::post('/paymentbank/store', [PaymentbankController::class, 'store']);
Route::get('/paymentbank/{id}/delete', [PaymentbankController::class, 'destroy']);

Route::get('/receipt', [ReceiptController::class, 'index'])->name('receipt');
Route::get('/newreceipt', [ReceiptController::class, 'newreceipt'])->name('newreceipt');
Route::post('/newreceipt', [ReceiptController::class, 'newreceipt'])->name('newreceipt');
Route::get('/authorizedreceipt', [ReceiptController::class, 'posted'])->name('authorizedreceipt');
Route::post('/authorizedreceipt', [ReceiptController::class, 'posted'])->name('authorizedreceipt');
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
Route::get('/updatereconcil/{val}/{reconcil_refrence_no}', [ReceiptController::class, 'updatereconcil']);
Route::get('/customersearch/{company_id}/{brand_id}', [ReceiptController::class, 'customersearch'])->name('customersearch');
Route::get('/receiptviewreport/{id}', [ReceiptController::class, 'receiptviewreport']);
Route::get('/refreshDashboard/{fromdate}/{todate}/{company_id}/{brand_id}', [ReceiptController::class, 'refreshDashboard'])->name('refreshDashboard');

Route::get('/createpurchaserequest', [PurchaserequestController::class, 'index']);
Route::get('/updatePurchaseRequestLine/{customer_id}/{product}/{order_quantity}/{target_price}/{editid}/{notes}', [PurchaserequestController::class, 'updatePurchaseRequestLine']);
Route::get('/updatePurchaseRequestLine/{customer_id}/{product}/{order_quantity}/{target_price}/{editid}/{notes}/{pr_id}', [PurchaserequestController::class, 'updatePurchaseRequestLine']);

Route::get('/loadpurchaselines/{customer_id}', [PurchaserequestController::class, 'loadpurchaselines']);
Route::get('/loadpurchaselines/{customer_id}/{pr_id}', [PurchaserequestController::class, 'loadpurchaselines']);
Route::get('/deletePurchaserequestLine/{id}', [PurchaserequestController::class, 'deletePurchaserequestLine']);
Route::post('/purchaserequest/store', [PurchaserequestController::class, 'store']);
Route::get('/purchaserequest', [PurchaserequestController::class, 'list']);
Route::post('/purchaserequest', [PurchaserequestController::class, 'list']);
Route::get('/load5oldpurchaseRequestlines/{id}', [PurchaserequestController::class, 'load5oldprlines']);
Route::get('/view/purchaserequest/{id}', [PurchaserequestController::class, 'view']);
Route::get('/delete/purchaserequest/{id}', [PurchaserequestController::class, 'delete']);
Route::get('/status/purchaserequest/{id}/{status}', [PurchaserequestController::class, 'statusupdate']);
Route::get('/edit/purchaserequest/{id}', [PurchaserequestController::class, 'edit']);
Route::post('/purchaserequest/update', [PurchaserequestController::class, 'update']);
Route::get('/report/productrequest', [ReportController::class, 'productrequest'])->name('reportproductrequest');
Route::post('report/productrequest', [ReportController::class, 'productrequest']);

Route::get('/report/prdraftreport', [ReportController::class, 'prdraftreport'])->name('reportprdraftreport');
Route::post('report/prdraftreport', [ReportController::class, 'prdraftreport']);


Route::get('/quote', [QuoteController::class, 'create']);
Route::post('/quote/store', [QuoteController::class, 'store'])->name('quotestore');
Route::get('/quotelist', [QuoteController::class, 'index'])->name('quote');
Route::post('/quotelist', [QuoteController::class, 'index'])->name('quote');
Route::get('/view/quote/{id}', [QuoteController::class, 'view'])->name('quoteview');
Route::get('/edit/quote/{id}', [QuoteController::class, 'edit'])->name('quoteedit');
Route::get('/delete/quote/{id}', [QuoteController::class, 'delete'])->name('quotedelete');
Route::post('/quoteupdate', [QuoteController::class, 'update'])->name('quoteupdate');
Route::get('/report/quote', [ReportController::class, 'quotereport'])->name('reportquote');
Route::post('report/quote', [ReportController::class, 'quotereport']);

Route::get('/po', [PoController::class, 'create']);
Route::post('/po/store', [PoController::class, 'store'])->name('postore');
Route::get('/polist', [PoController::class, 'index'])->name('po');
Route::post('/polist', [PoController::class, 'index'])->name('po');
Route::get('/view/po/{id}', [PoController::class, 'view'])->name('poview');
Route::get('/edit/po/{id}', [PoController::class, 'edit'])->name('poedit');
Route::get('/delete/po/{id}', [PoController::class, 'delete'])->name('podelete');
Route::post('/poupdate', [PoController::class, 'update'])->name('poupdate');
Route::get('/print/po/{id}', [PoController::class, 'printpo'])->name('poprint');
Route::get('/report/po', [ReportController::class, 'poreport'])->name('reportpo');
Route::post('report/po', [ReportController::class, 'poreport'])->name('reportpo');
Route::get('/report/poline', [ReportController::class, 'polinereport'])->name('reportpoline');
Route::post('/report/poline', [ReportController::class, 'polinereport'])->name('reportpoline');

Route::get('/grn', [GrnController::class, 'create']);
Route::post('/grn/store', [GrnController::class, 'store'])->name('grnstore');
Route::get('/grnlist', [GrnController::class, 'index'])->name('grn');
Route::post('/grnlist', [GrnController::class, 'index'])->name('grn');
Route::get('/view/grn/{id}', [GrnController::class, 'view'])->name('grnview');
Route::get('/delete/grn/{id}', [GrnController::class, 'delete'])->name('grndelete');
Route::post('/grnupdate', [GrnController::class, 'update'])->name('grnupdate');
Route::get('/report/grn', [ReportController::class, 'grnreport'])->name('reportquote');
Route::post('report/grn', [ReportController::class, 'grnreport'])->name('reportquote');
Route::get('/report/grnline', [ReportController::class, 'grnlinereport'])->name('reportgrnline');
Route::post('report/grnline', [ReportController::class, 'grnlinereport'])->name('reportgrnline');

