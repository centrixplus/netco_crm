<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Saleline;
use App\Models\Product;
use App\Models\User;
use App\Models\Status;
use App\Models\Brand;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if(Auth::user()->role=='warehouse'){
                return redirect('/dashboard/warehouse');
        }
        // else if(Auth::user()->role=='warehouse'){
        //         return redirect('/dashboard/warehouse');
        // }
        else if(Auth::user()->role=='TL-01' || Auth::user()->role=='SM-01' || Auth::user()->role=='SC-01' || Auth::user()->role=='KO-01'|| Auth::user()->role=='KO-02'){
                return redirect('/receiptdashboard');
        }
        else if(Auth::user()->role=='PR'){
                return redirect('/purchaserequest');
        }
        $title='Dashboard';
        $fromdate = date('Y-m-d');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $customer=Customer::where('delete_status','0')->whereBetween('created_at',[$fromdate, $todate1])->count();
        $sales_count=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->get();

        $new=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','New')->get();
        $processing=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','Processing')->get();
        $delivered=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','Delivered')->get();
        $los = Saleline::where('delete_status','0')->where('reason','PDC')->whereBetween('created_at',[$fromdate, $todate1])->get();
        $netco_los = Saleline::where('delete_status','0')->where('reason','NETCO')->whereBetween('created_at',[$fromdate, $todate1])->get();
        $users=User::get();
        $statuses=Status::where('delete_status','0')->orderBy('order','asc')->get();
        $brands = Brand::where('delete_status', '0')
        ->with(['salelines' => function ($query) use ($fromdate, $todate1) {
            $query->whereBetween('salelines.created_at', [$fromdate, $todate1])
                  ->select('brand_id', 
                           DB::raw('SUM(quantity) as total_quantity'), 
                           DB::raw('SUM(cost_price) as total_cost_price'))
                  ->groupBy('brand_id');
        }])
        ->orderBy('name', 'asc')
        ->get();
        $user_sales = view('sale.usersale',compact('users','fromdate','todate'))->render();
        // $status_sales = view('sale.statussale',compact('statuses','fromdate','todate'))->render();
        $brand_sales = view('report.brand',compact('brands','fromdate','todate'))->render();
        
         return view('welcome',compact('title','fromdate','todate1','todate','customer','sales_count','new','processing','delivered','los','netco_los','user_sales','brand_sales'));  
    }

    public function dashboard($fromdate,$todate)
    {
        $title='Dashboard';
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $customer=Customer::where('delete_status','0')->whereBetween('created_at',[$fromdate, $todate1])->count();

        $sales_count=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->count();
        $new_count=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','New')->count();
        $processing_count=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','Processing')->count();
        $delivered_count=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','Delivered')->count();

        $sales_amount=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->sum('grand_total');
        $new_amount=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','New')->sum('grand_total');
        $processing_amount=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','Processing')->sum('grand_total');
        $delivered_amount=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','Delivered')->sum('grand_total');

        $los_amount = Saleline::where('delete_status','0')->where('reason','!=','0')->where('reason','PDC')->whereBetween('created_at',[$fromdate, $todate1])->sum('l_price');
        $netco_los_amount = Saleline::where('delete_status','0')->where('reason','!=','0')->where('reason','NETCO')->whereBetween('created_at',[$fromdate, $todate1])->sum('l_price');
        $users=User::get();
        $brands = Brand::where('delete_status','0')->orderBy('name','asc')->get();
        $user_sales = view('sale.usersale',compact('users','fromdate','todate'))->render();
        $statuses=Status::where('delete_status','0')->orderBy('order','asc')->get();
        $status_sales = view('sale.statussale',compact('statuses','fromdate','todate'))->render();
        $brand_sales = view('report.brand',compact('brands','fromdate','todate'))->render();
        $sales_amount=number_format($sales_amount, 3, '.', '' );
        $los_amount=number_format($los_amount, 3, '.', '' );
        $netco_los_amount=number_format($netco_los_amount, 3, '.', '' );
        $delivered_amount=number_format($delivered_amount, 3, '.', '' );
        $processing_amount=number_format($processing_amount, 3, '.', '' );
        $new_amount=number_format($new_amount, 3, '.', '' );

         echo json_encode(array("new_count"=>$new_count,"sales_count"=>$sales_count,"processing_count"=>$processing_count,"delivered_count"=>$delivered_count,"sales_amount"=>$sales_amount,"new_amount"=>$new_amount,"processing_amount"=>$processing_amount,"delivered_amount"=>$delivered_amount,"los_amount"=>$los_amount,"netco_los_amount"=>$netco_los_amount,"user_sales"=>$user_sales,"status_sales"=>$status_sales,"brand_sales"=>$brand_sales));
    
    }

    public function warehouse(Request $request)
    {
        $title='Dashboard';
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-t');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $processing=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status_id','2')->get();
        $invoiced=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status_id','10')->get();
        $delivered=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status_id','3')->get();
        return view('warehouse.warehouse',compact('title','processing','invoiced','delivered','fromdate','todate'));  

    }

    public function warehouseOrders($customer_id='0', $location_id='0')
    {
        $title='Orders';
        $orders = Sale::where('delete_status','0');
        
        if($customer_id!=0){
            $orders=$orders->where('customer_id',$customer_id);
        }

        if($location_id!=0){
          
            $orders=$orders->whereHas('customer', function($q) use ($location_id)
            {
                $q->where('location_id',$location_id);

            });
        }

        $orders=$orders->get();
        return view('warehouse.orders',compact('title','orders'));  

    }

   

    public function dashboardV2()
    {
        $title = 'Dashboard V2';

        // Date ranges
        $now = now();
        $currentMonthStart = $now->copy()->startOfMonth()->toDateString();
        $currentMonthEnd   = $now->copy()->endOfMonth()->toDateString();
        $today             = $now->toDateString();
        $prevMonthStart    = $now->copy()->subMonth()->startOfMonth()->toDateString();
        $prevMonthEnd      = $now->copy()->subMonth()->endOfMonth()->toDateString();

        // KPI 1: Current month sales
        $currentSales = DB::table('sales')
            ->where('delete_status', 0)
            ->whereBetween('sale_date', [$currentMonthStart, $currentMonthEnd])
            ->selectRaw('COUNT(*) as order_count, COALESCE(SUM(CAST(grand_total AS DECIMAL(12,3))),0) as total_amount')
            ->first();

        $prevSales = DB::table('sales')
            ->where('delete_status', 0)
            ->whereBetween('sale_date', [$prevMonthStart, $prevMonthEnd])
            ->selectRaw('COUNT(*) as order_count, COALESCE(SUM(CAST(grand_total AS DECIMAL(12,3))),0) as total_amount')
            ->first();

        // KPI 2: Receipts/Collections
        $currentReceipts = DB::table('receipts')
            ->where('delete_status', 0)
            ->whereBetween('receipt_date', [$currentMonthStart, $currentMonthEnd])
            ->selectRaw('COUNT(*) as receipt_count, COALESCE(SUM(receipt_amount),0) as total_receipts')
            ->first();

        $prevReceipts = DB::table('receipts')
            ->where('delete_status', 0)
            ->whereBetween('receipt_date', [$prevMonthStart, $prevMonthEnd])
            ->selectRaw('COUNT(*) as receipt_count, COALESCE(SUM(receipt_amount),0) as total_receipts')
            ->first();

        // KPI 3: Pending orders (status = New)
        $pendingOrders = DB::table('sales')
            ->where('delete_status', 0)
            ->where('status_id', 1)
            ->selectRaw('COUNT(*) as cnt, COALESCE(SUM(CAST(grand_total AS DECIMAL(12,3))),0) as amt')
            ->first();

        // KPI 4: Open purchase requests
        $openPRs = DB::table('purchaserequests')
            ->where('delete_status', 0)
            ->where('prstatus_id', 1)
            ->count();

        // Trend calculations
        $salesTrend = $prevSales->total_amount > 0
            ? (($currentSales->total_amount - $prevSales->total_amount) / $prevSales->total_amount) * 100
            : ($currentSales->total_amount > 0 ? 100 : 0);

        $ordersTrend = $prevSales->order_count > 0
            ? (($currentSales->order_count - $prevSales->order_count) / $prevSales->order_count) * 100
            : ($currentSales->order_count > 0 ? 100 : 0);

        $receiptsTrend = $prevReceipts->total_receipts > 0
            ? (($currentReceipts->total_receipts - $prevReceipts->total_receipts) / $prevReceipts->total_receipts) * 100
            : ($currentReceipts->total_receipts > 0 ? 100 : 0);

        $avgOrderValue = $currentSales->order_count > 0
            ? $currentSales->total_amount / $currentSales->order_count
            : 0;

        // Daily sales trend for chart
        $dailySales = DB::table('sales')
            ->where('delete_status', 0)
            ->whereBetween('sale_date', [$currentMonthStart, $currentMonthEnd])
            ->selectRaw('sale_date, COUNT(*) as order_count, COALESCE(SUM(CAST(grand_total AS DECIMAL(12,3))),0) as daily_total')
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get();

        $salesTrendLabels = $dailySales->pluck('sale_date')->map(function($d) {
            return date('d M', strtotime($d));
        })->values()->toArray();
        $salesTrendValues = $dailySales->pluck('daily_total')->map(function($v) { return round((float)$v, 3); })->values()->toArray();
        $salesTrendCounts = $dailySales->pluck('order_count')->values()->toArray();

        // Order status breakdown
        $orderStatuses = DB::table('sales')
            ->join('status', 'sales.status_id', '=', 'status.id')
            ->where('sales.delete_status', 0)
            ->whereBetween('sales.sale_date', [$currentMonthStart, $currentMonthEnd])
            ->selectRaw('status.name, status.color, COUNT(*) as cnt')
            ->groupBy('status.name', 'status.color')
            ->get();

        $statusLabels = $orderStatuses->pluck('name')->values()->toArray();
        $statusValues = $orderStatuses->pluck('cnt')->values()->toArray();
        // Map Skote badge colors to hex for ApexCharts
        $colorMap = [
            'bg-primary bg-soft' => '#556ee6',
            'bg-secondary bg-soft' => '#74788d',
            'bg-warning bg-soft' => '#f1b44c',
            'bg-danger bg-soft' => '#f46a6a',
            'bg-success' => '#34c38f',
            'bg-success bg-soft' => '#34c38f',
            'bg-danger' => '#f46a6a',
        ];
        $statusColors = $orderStatuses->pluck('color')->map(function($c) use ($colorMap) {
            return $colorMap[$c] ?? '#74788d';
        })->values()->toArray();

        // Brand-wise sales
        $brandSales = DB::table('salelines')
            ->join('sales', 'salelines.slae_id', '=', 'sales.id')
            ->join('products', 'salelines.product_id', '=', 'products.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->where('sales.delete_status', 0)
            ->where('salelines.delete_status', 0)
            ->whereBetween('sales.sale_date', [$currentMonthStart, $currentMonthEnd])
            ->selectRaw('brands.name as brand_name, COALESCE(SUM(CAST(salelines.quantity AS UNSIGNED)),0) as total_qty, COALESCE(SUM(CAST(salelines.cost_price AS DECIMAL(12,3))),0) as total_amount')
            ->groupBy('brands.name')
            ->orderByDesc('total_amount')
            ->get();

        $brandChartLabels = $brandSales->pluck('brand_name')->values()->toArray();
        $brandChartValues = $brandSales->pluck('total_amount')->map(function($v) { return round((float)$v, 3); })->values()->toArray();

        // Receipt pipeline
        $receiptPipeline = DB::table('receipts')
            ->join('rvstages', 'receipts.rvstage_id', '=', 'rvstages.id')
            ->where('receipts.delete_status', 0)
            ->selectRaw('rvstages.id as stage_id, rvstages.name as stage_name, COUNT(*) as cnt, COALESCE(SUM(receipt_amount),0) as total_amount')
            ->groupBy('rvstages.id', 'rvstages.name')
            ->orderBy('rvstages.id')
            ->get();

        // Top 10 customers
        $topCustomers = DB::table('sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->where('sales.delete_status', 0)
            ->whereBetween('sales.sale_date', [$currentMonthStart, $currentMonthEnd])
            ->selectRaw('customers.name, customers.code, COUNT(*) as order_count, COALESCE(SUM(CAST(sales.grand_total AS DECIMAL(12,3))),0) as total_amount')
            ->groupBy('customers.id', 'customers.name', 'customers.code')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

        // Recent 10 orders
        $recentOrders = DB::table('sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->join('status', 'sales.status_id', '=', 'status.id')
            ->where('sales.delete_status', 0)
            ->select('sales.id', 'sales.sale_no', 'sales.sale_date', 'sales.grand_total', 'sales.no_of_qty', 'customers.name as customer_name', 'status.name as status_name', 'status.color as status_color')
            ->orderByDesc('sales.id')
            ->limit(10)
            ->get();

        // Today's collections
        $todayCollections = DB::table('receipts')
            ->join('customers', 'receipts.customer_id', '=', 'customers.id')
            ->join('rvstages', 'receipts.rvstage_id', '=', 'rvstages.id')
            ->where('receipts.delete_status', 0)
            ->where('receipts.receipt_date', $today)
            ->select('receipts.id', 'receipts.receipt_no', 'receipts.receipt_amount', 'customers.name as customer_name', 'rvstages.name as stage_name')
            ->orderByDesc('receipts.id')
            ->get();

        $todayCollectionTotal = $todayCollections->sum('receipt_amount');

        // Low stock alerts - products with stock between 0 and 5 that had sales
        $lowStock = DB::table('productstocks')
            ->join('products', 'productstocks.product_id', '=', 'products.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->where('productstocks.delete_status', 0)
            ->where('products.delete_status', 0)
            ->whereRaw('CAST(productstocks.qty AS SIGNED) BETWEEN 1 AND 5')
            ->whereRaw('CAST(productstocks.t_qty AS SIGNED) >= 0')
            ->select('products.name as product_name', 'brands.name as brand_name', 'productstocks.qty', 'productstocks.t_qty')
            ->orderByRaw('CAST(productstocks.qty AS SIGNED) ASC')
            ->limit(15)
            ->get();

        // User-wise sales
        $userSales = DB::table('sales')
            ->join('users', 'sales.created_by', '=', 'users.id')
            ->where('sales.delete_status', 0)
            ->whereBetween('sales.sale_date', [$currentMonthStart, $currentMonthEnd])
            ->selectRaw('users.name, users.role, COUNT(*) as order_count, COALESCE(SUM(CAST(sales.grand_total AS DECIMAL(12,3))),0) as total_amount')
            ->groupBy('users.id', 'users.name', 'users.role')
            ->orderByDesc('total_amount')
            ->get();

        return view('dashboardv2', compact(
            'title', 'currentMonthStart', 'currentMonthEnd', 'today',
            'currentSales', 'prevSales', 'salesTrend', 'ordersTrend',
            'currentReceipts', 'receiptsTrend',
            'avgOrderValue', 'pendingOrders', 'openPRs',
            'salesTrendLabels', 'salesTrendValues', 'salesTrendCounts',
            'statusLabels', 'statusValues', 'statusColors', 'orderStatuses',
            'brandSales', 'brandChartLabels', 'brandChartValues',
            'receiptPipeline',
            'topCustomers', 'recentOrders',
            'todayCollections', 'todayCollectionTotal',
            'lowStock', 'userSales'
        ));
    }

}
