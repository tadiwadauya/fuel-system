<?php

namespace App\Http\Controllers;

use Auth;
use DateTime;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $petrollitres = DB::table('transactions')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Petrol')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $petrolcash = DB::table('cash_sales')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Petrol')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $petrolCashData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $petrolAllocationsData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($petrolcash as $order){
                $petrolCashData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($petrollitres as $order){
                $petrolAllocationsData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $diesellitres = DB::table('transactions')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Diesel')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $dieselcash = DB::table('cash_sales')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Diesel')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $dieselCashData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $dieselAllocationsdata = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($dieselcash as $order){
                $dieselCashData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($diesellitres as $order){
                $dieselAllocationsdata[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $sssales = DB::table('invoices')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $serviceStationData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($sssales as $order){
                $serviceStationData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $stockFuelPetrol = DB::table('stock_issues')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Petrol')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $stockFuelDiesel = DB::table('stock_issues')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Diesel')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $petrolStockData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $dieselStockData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($stockFuelPetrol as $order){
                $petrolStockData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($stockFuelDiesel as $order){
                $dieselStockData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $days = DB::table('transactions')
                ->select(DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(CASE WHEN ftype = "Petrol" THEN quantity END) as Peturu'),
                    DB::raw('SUM(CASE WHEN ftype = "Diesel" THEN quantity END) as Dhiziri'),
                    DB::raw('sum(quantity) as count')
                )
                ->where('deleted_at','=', null)
                ->groupBy(DB::raw('DATE(created_at)') )
                ->orderBy('created_at', 'DESC')
                ->take(6)
                ->get();

            $currentMonth = date('m');
            $currentAllPetrol = DB::table('transactions')->select(
                DB::raw('sum(quantity) as currentPetrol'))
                ->where('ftype','Petrol')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->get();
            $currentAllDiesel = DB::table('transactions')->select(
                DB::raw('sum(quantity) as currentDiesel'))
                ->where('ftype','Diesel')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->get();

            $currentPetrol = DB::table('transactions')
                ->select(DB::raw('sum(quantity) as currentPetrol'))
                ->where('employee',Auth::user()->paynumber)
                ->where('ftype','Petrol')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->get();

            $currentDiesel = DB::table('transactions')
                ->select(DB::raw('sum(quantity) as currentDiesel'))
                ->where('employee',Auth::user()->paynumber)
                ->where('ftype','Diesel')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->get();

            if (auth()->user()->allocation == 'Allocation'){
                $currentAllocation = DB::table('allocations')
                    ->select('allocation','balance', 'alloc_size')
                    ->where('paynumber',Auth::user()->paynumber)
                    ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                    ->first();

                if (is_null($currentAllocation) OR is_null($currentAllocation->balance)){
                    $percentAllocation = 100;
                    $usedAllocation = 0;
                } elseif($currentAllocation->balance == 0.0) {
                    $percentAllocation = 0;
                    $usedAllocation = $currentAllocation->alloc_size;
                }else {
                    $percentAllocation = ($currentAllocation->balance / $currentAllocation->alloc_size) * 100;
                    $usedAllocation = $currentAllocation->alloc_size - $currentAllocation->balance;
                }
            } else {
                $percentAllocation = 0;
                $usedAllocation = 0;
                $currentAllocation = null;
            }

            return view('pages.admin.home', compact('days', 'percentAllocation', 'usedAllocation', 'currentAllocation', 'petrolCashData', 'petrolAllocationsData', 'dieselCashData', 'dieselAllocationsdata', 'currentAllPetrol','currentAllDiesel','currentPetrol','currentDiesel','serviceStationData','petrolStockData','dieselStockData'));

        }
        elseif ($user->isManager()){

            $petrollitres = DB::table('transactions')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Petrol')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $petrolcash = DB::table('cash_sales')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Petrol')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $petrolCashData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $petrolAllocationsData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($petrolcash as $order){
                $petrolCashData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($petrollitres as $order){
                $petrolAllocationsData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $diesellitres = DB::table('transactions')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Diesel')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $dieselcash = DB::table('cash_sales')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Diesel')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $dieselCashData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $dieselAllocationsdata = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($dieselcash as $order){
                $dieselCashData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($diesellitres as $order){
                $dieselAllocationsdata[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $sssales = DB::table('invoices')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $serviceStationData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($sssales as $order){
                $serviceStationData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $stockFuelPetrol = DB::table('stock_issues')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Petrol')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $stockFuelDiesel = DB::table('stock_issues')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Diesel')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $petrolStockData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $dieselStockData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($stockFuelPetrol as $order){
                $petrolStockData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($stockFuelDiesel as $order){
                $dieselStockData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $days = DB::table('transactions')
                ->select(DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(CASE WHEN ftype = "Petrol" THEN quantity END) as Peturu'),
                    DB::raw('SUM(CASE WHEN ftype = "Diesel" THEN quantity END) as Dhiziri'),
                    DB::raw('sum(quantity) as count')
                )
                ->where('deleted_at','=', null)
                ->groupBy(DB::raw('DATE(created_at)') )
                ->orderBy('created_at', 'DESC')
                ->take(6)
                ->get();

            $currentMonth = date('m');
            $currentAllPetrol = DB::table('transactions')->select(
                DB::raw('sum(quantity) as currentPetrol'))
                ->where('ftype','Petrol')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->get();

            $currentAllDiesel = DB::table('transactions')->select(
                DB::raw('sum(quantity) as currentDiesel'))
                ->where('ftype','Diesel')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->get();

            $currentPetrol = DB::table('transactions')
                ->select(DB::raw('sum(quantity) as currentPetrol'))
                ->where('employee',Auth::user()->paynumber)
                ->where('ftype','Petrol')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->get();

            $currentDiesel = DB::table('transactions')
                ->select(DB::raw('sum(quantity) as currentDiesel'))
                ->where('employee',Auth::user()->paynumber)
                ->where('ftype','Diesel')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->get();

            if (auth()->user()->allocation == 'Allocation'){
                $currentAllocation = DB::table('allocations')
                    ->select('allocation','balance', 'alloc_size')
                    ->where('paynumber',Auth::user()->paynumber)
                    ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                    ->first();

                if (is_null($currentAllocation) OR is_null($currentAllocation->balance)){
                    $percentAllocation = 100;
                    $usedAllocation = 0;
                } elseif($currentAllocation->balance == 0.0) {
                    $percentAllocation = 0;
                    $usedAllocation = $currentAllocation->alloc_size;
                }else {
                    $percentAllocation = ($currentAllocation->balance / $currentAllocation->alloc_size) * 100;
                    $usedAllocation = $currentAllocation->alloc_size - $currentAllocation->balance;
                }
            } elseif (auth()->user()->allocation == 'Director') {
                $percentAllocation = 0;
                $usedAllocation = 0;
                $currentAllocation = DB::table('users')
                    ->select('allocation', 'alloc_size')
                    ->where('paynumber',Auth::user()->paynumber)
                    ->first();

            }

            return view('pages.manager.home', compact('days', 'percentAllocation', 'usedAllocation', 'currentAllocation', 'petrolCashData', 'petrolAllocationsData', 'dieselCashData', 'dieselAllocationsdata', 'currentAllPetrol','currentAllDiesel' ,'currentPetrol','currentDiesel','serviceStationData', 'petrolStockData', 'dieselStockData'));
        }
        elseif ($user->isDiesel()){
            $petrollitres = DB::table('transactions')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Petrol')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $petrolcash = DB::table('cash_sales')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Petrol')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $petrolCashData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $petrolAllocationsData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($petrolcash as $order){
                $petrolCashData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($petrollitres as $order){
                $petrolAllocationsData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $diesellitres = DB::table('transactions')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Diesel')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $dieselcash = DB::table('cash_sales')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Diesel')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $dieselCashData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $dieselAllocationsdata = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($dieselcash as $order){
                $dieselCashData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($diesellitres as $order){
                $dieselAllocationsdata[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $stockFuelPetrol = DB::table('stock_issues')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Petrol')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $stockFuelDiesel = DB::table('stock_issues')->select(
                DB::raw('sum(quantity) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('ftype','Diesel')
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $petrolStockData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $dieselStockData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($stockFuelPetrol as $order){
                $petrolStockData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($stockFuelDiesel as $order){
                $dieselStockData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $days = DB::table('transactions')
                ->select(DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(CASE WHEN ftype = "Petrol" THEN quantity END) as Peturu'),
                    DB::raw('SUM(CASE WHEN ftype = "Diesel" THEN quantity END) as Dhiziri'),
                    DB::raw('sum(quantity) as count')
                )
                ->where('deleted_at','=', null)
                ->groupBy(DB::raw('DATE(created_at)') )
                ->orderBy('created_at', 'DESC')
                ->take(5)
                ->get();

            $currentMonth = date('m');
            $currentAllPetrol = DB::table('transactions')->select(
                DB::raw('sum(quantity) as currentPetrol'))
                ->where('ftype','Petrol')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->get();

            $currentAllDiesel = DB::table('transactions')->select(
                DB::raw('sum(quantity) as currentDiesel'))
                ->where('ftype','Diesel')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->get();

            $currentPetrol = DB::table('transactions')
                ->select(DB::raw('sum(quantity) as currentPetrol'))
                ->where('employee',Auth::user()->paynumber)
                ->where('ftype','Petrol')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->get();

            $currentDiesel = DB::table('transactions')
                ->select(DB::raw('sum(quantity) as currentDiesel'))
                ->where('employee',Auth::user()->paynumber)
                ->where('ftype','Diesel')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->get();

            if (auth()->user()->allocation == 'Allocation'){
                $currentAllocation = DB::table('allocations')
                    ->select('allocation','balance', 'alloc_size')
                    ->where('paynumber',Auth::user()->paynumber)
                    ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                    ->first();

                if (is_null($currentAllocation) OR is_null($currentAllocation->balance)){
                    $percentAllocation = 100;
                    $usedAllocation = 0;
                } elseif($currentAllocation->balance == 0.0) {
                    $percentAllocation = 0;
                    $usedAllocation = $currentAllocation->alloc_size;
                }else {
                    $percentAllocation = ($currentAllocation->balance / $currentAllocation->alloc_size) * 100;
                    $usedAllocation = $currentAllocation->alloc_size - $currentAllocation->balance;
                }
            } else {
                $percentAllocation = 0;
                $usedAllocation = 0;
                $currentAllocation = null;
            }

            return view('pages.diesel.home', compact('days', 'percentAllocation', 'usedAllocation', 'currentAllocation', 'petrolCashData', 'petrolAllocationsData', 'dieselCashData', 'dieselAllocationsdata', 'currentAllPetrol','currentAllDiesel','currentPetrol','currentDiesel','petrolStockData', 'dieselStockData'));
        }
        else {
            $currentMonth = date('m');
            $currentPetrol = DB::table('transactions')
                ->select(DB::raw('sum(quantity) as currentPetrol'))
                ->where('employee',Auth::user()->paynumber)
                ->where('ftype','Petrol')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->get();

            $currentDiesel = DB::table('transactions')
                ->select(DB::raw('sum(quantity) as currentDiesel'))
                ->where('employee',Auth::user()->paynumber)
                ->where('ftype','Diesel')
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->get();

            if (auth()->user()->allocation == 'Allocation'){
                $user_detail = Auth::user()->paynumber;
                $search = $user_detail.date('FY');
                $currentAllocation = DB::table('allocations')
                    ->select('allocation','balance', 'alloc_size')
                    ->where('paynumber',Auth::user()->paynumber)
                    ->where('allocation',$search)
                    // ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                    ->first();

                if (is_null($currentAllocation) OR is_null($currentAllocation->balance) ){
                    $percentAllocation = 100;
                    $usedAllocation = 0;
                } elseif($currentAllocation->balance == 0.0) {
                    $percentAllocation = 0;
                    $usedAllocation = $currentAllocation->alloc_size;
                }else {
                    $percentAllocation = ($currentAllocation->balance / $currentAllocation->alloc_size) * 100;
                    $usedAllocation = $currentAllocation->alloc_size - $currentAllocation->balance;
                }
            } else {
                $percentAllocation = 0;
                $usedAllocation = 0;
                $currentAllocation = null;
            }

            return view('pages.user.home', compact('currentPetrol','currentDiesel', 'currentAllocation', 'percentAllocation', 'usedAllocation'));
        }
    }
}
