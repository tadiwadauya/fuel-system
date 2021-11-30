<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Container;
use App\Models\ContainerTransaction;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportsController extends Controller
{

    /**
     * ReportsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function allocationsForm(){
        $users = User::where('allocation','=','Allocation')
            ->orWhere('allocation','=','Director')
        ->get();

        return view('reports.allocations-report', compact('users'));
    }

    public function getAllocations(){
        $daterange = $_POST['date_range'];
        $split = explode('/', $daterange);
        $users = User::where('allocation','=','Allocation')
            ->orWhere('allocation','=','Director')
            ->get();

        $count = count($split);

        if ($count <> 2) {
            return redirect('allocations-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $employee = $_POST['paynumber'];

        $allocations = Transaction::where('employee', $employee)
            ->whereBetween('created_at', [$start, $end])
            ->where('deleted_at','=', null)
            ->get();

        return view('reports.allocations-report', compact('allocations', 'users', 'employee'));
    }

    public function allAllocationsForm(){
        return view('reports.all-allocations-report');
    }

    public function getAllAllocations(){
        $daterange = $_POST['date_range'];
        $split = explode('/', $daterange);
        $users = User::where('allocation','=','Allocation')
            ->orWhere('allocation','=','Director')
            ->get();

        $count = count($split);

        if ($count <> 2) {
            return redirect('all-allocations-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $allocations = DB::table('transactions as t')
            ->join('users as u', function($join) {
                $join->on('u.paynumber', '=', 't.employee');
            })
            ->select('t.created_at','u.first_name','u.last_name','u.paynumber','t.reg_num','t.ftype','t.quantity','t.allocation')
            ->whereBetween('t.created_at', [$start, $end])
            ->where('t.deleted_at','=', null)
            ->get();

        return view('reports.all-allocations-report', compact('allocations', 'users'));
    }

    public function allocationsBalancesForm(){
        return view('reports.allocation-balances');
    }

    public function getAllocationsBalances(){
        $daterange = $_POST['date_range'];
        $split = explode('/', $daterange);
        $users = User::all()->where('allocation','=','Allocation');

        $count = count($split);

        if ($count <> 2) {
            return redirect('allocations-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $allocations = DB::table('transactions as t')
            ->join('users as u', function($join) {
                $join->on('u.paynumber', '=', 't.employee');
            })
            ->join('allocations as a', function($join) {
                $join->on('a.paynumber', '=', 't.employee')
                ->on('a.allocation','=','t.allocation');
            })
            ->select('u.first_name','u.last_name','u.paynumber','a.allocation','a.alloc_size','a.balance')
            ->whereBetween('t.created_at', [$start, $end])
            ->where('t.deleted_at','=', null)
            ->groupBy('u.first_name','u.last_name','u.paynumber','a.allocation','a.alloc_size','a.balance')
            ->get();
//dd($allocations);
        return view('reports.allocation-balances', compact('allocations', 'start', 'end'));

    }

    public function cashSalesForm(){
        return view('reports.cash-sale-reports');
    }

    public function getCashSales (){
        $daterange = $_POST['date_range'];
        $split = explode('/', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('cash-sale-reports')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $cashsales = DB::table('cash_sales as c')
            ->join('users as u', function($join) {
                $join->on('u.paynumber', '=', 'c.employee');
            })
            ->select('u.first_name','u.last_name','u.paynumber','c.ftype','c.reg_num','c.quantity','c.created_at')
            ->whereBetween('c.created_at', [$start, $end])
            ->where('c.deleted_at','=', null)
            ->get();
        return view('reports.cash-sale-reports', compact('cashsales', 'start', 'end'));
    }

    public function invoicesForm(){
        $clients = Client::all();

        return view('reports.invoices-report', compact('clients'));
    }

    public function getInvoices(){
        $daterange = $_POST['date_range'];
        $split = explode('/', $daterange);
        $clients = Client::all();

        $count = count($split);

        if ($count <> 2) {
            return redirect('allocations-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $client = $_POST['customer'];

        $userInfo = Client::where('id', $client )->firstOrFail();

        $invoices = Invoice::where('invoices.client', $client)
            ->leftJoin('containers', 'invoices.container' , '=', 'containers.conname')
            ->whereBetween('invoices.created_at', [$start, $end])
            ->where('invoices.deleted_at','=', null)
            ->get();

        return view('reports.invoices-report', compact('invoices', 'clients', 'client', 'userInfo'));
    }

    public function containersForm(){
        $clients = Client::all();

        return view('reports.containers-report', compact('clients'));
    }

    public function getContainers(){
        $daterange = $_POST['date_range'];
        $split = explode('/', $daterange);
        $clients = Client::all();

        $count = count($split);

        if ($count <> 2) {
            return redirect('allocations-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $client = $_POST['customer'];
        $container = $_POST['container'];

        $userInfo = Client::where('id', $client )->firstOrFail();
        $containerInfo = Container::where('conname', $container )->firstOrFail();

        $invoices = Invoice::where('invoices.client', $client)
            ->leftJoin('containers', 'invoices.container' , '=', 'containers.conname')
            ->where('containers.conname', $container)
            ->whereBetween('invoices.created_at', [$start, $end])
            ->where('invoices.deleted_at','=', null)
            ->get();

        return view('reports.containers-report', compact('invoices', 'clients', 'client', 'userInfo','containerInfo'));
    }

    public function stockIssuesForm(){
        return view('reports.stock-issue-reports');
    }

    public function getStockIssues (){
        $daterange = $_POST['date_range'];
        $split = explode('/', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('cash-sale-reports')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $stockissues = DB::table('stock_issues as s')
            ->join('users as u', function($join) {
                $join->on('u.paynumber', '=', 's.employee');
            })
            ->select('u.first_name','u.last_name','u.paynumber','s.ftype','s.narration','s.reg_num','s.quantity')
            ->whereBetween('s.created_at', [$start, $end])
            ->where('s.deleted_at','=', null)
            ->get();

        return view('reports.stock-issue-reports', compact('stockissues', 'start', 'end'));
    }

    public function containerTransactionForm()
    {
        $clients = Client::all();

        return view('reports.container-transaction', compact('clients'));
    }

    public function containertransactionPost(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'customer' => 'required',
            'container' => 'required',
            'date_range' => 'required'
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            try {

                $daterange = $_POST['date_range'];
                $split = explode('/', $daterange);
                $clients = Client::all();

                $count = count($split);

                if ($count <> 2) {
                    return redirect('container-transaction-report')->with('error', 'Ooops, something went wrong with the date picker.');
                }

                $start = date('Y-m-d h:m:s', strtotime($split[0]));
                $end = date('Y-m-d h:m:s', strtotime($split[1]));

                $client = $request->input('customer');
                $container = $request->input('container');

                $userInfo = Client::where('id', $client )->firstOrFail();
                $containerInfo = Container::where('conname', $container )->firstOrFail();
                $openingBalance = ContainerTransaction::where('client',$client)->orderBy('created_at', 'asc')->first();
                $closingBalance = Container::where('client',$client)->first();

                $transactions = ContainerTransaction::where('client',$client)
                                ->whereBetween('created_at', [$start, $end])
                                ->where('deleted_at','=', null)
                                ->get();

                return view('reports.container-transaction',compact('transactions','clients','userInfo','containerInfo','openingBalance','closingBalance'));

            } catch(\Exception $e) {
                echo "Error - ".$e;
            }
        }

    }

    public function getUserContainer($client){
        $container = DB::table('containers')
        ->where('client',$client)
        ->pluck('conname');

        return response()->json($container);
    }
}
