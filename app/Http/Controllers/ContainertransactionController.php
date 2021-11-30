<?php

namespace App\Http\Controllers;

use App\Models\ContainerTransaction;
use App\Models\Batch;
use App\Models\Client;
use App\Models\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ContainerTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = ContainerTransaction::latest()->get();
        return view('ctransactions.index',compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        $batches = Batch::where('status', 1)->get();
        return view('ctransactions.create', compact('clients', 'batches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'client'                  => 'required',
                'container'                  => 'required|max:255',
                'batchname'                  => 'required|max:255',
                'concapacity'                  => 'required|max:255',
                'conrate'                  => 'required|max:255',
                'created_at'                  => 'required|max:255',
            ],
            [
                'client.required'       => 'Who is purchased this container?',
                'container.required'       => 'You will need to provide a name for the container.',
                'batchname.required'       => 'Where is this fuel being deducted from?',
                'concapacity.required'       => 'How much fuel was purchased?',
                'conrate.required'       => 'At what price will this be sold at?',
                'created_at.required'       => 'Please select the date of transaction',
            ]
        );

        $selectedBatch = Batch::where('code', $request->input('batchname'))->first();

        $selectedClient = Client::where('id', $request->input('client'))->first();

        $clientContainer = Container::where('conname',$request->input('container'))->first();

        if ($request->input('concapacity') <= $selectedBatch->remaining ) {

            $remain = $selectedBatch->remaining - $request->input('concapacity');

            // check for container status
            if ($clientContainer->con_status == 0)
            {
                $clientContainer->con_status = 1;
            }

            $balance_after = $clientContainer->conbalance + $request->input('concapacity');

            $transaction = ContainerTransaction::create([
                'client' => $request->input('client'),
                'container' => $request->input('container'),
                'batch' => $request->input('batchname'),
                'concapacity' => $request->input('concapacity'),
                'conrate' => $request->input('conrate'),
                'b_before' => $clientContainer->conbalance,
                'b_after' => $balance_after,
                'created_at' => $request->input('created_at')
            ]);

            $transaction->save();

            if ($transaction->save())
            {
                $clientContainer->concapacity += $request->input('concapacity');
                $clientContainer->conbalance += $request->input('concapacity');
                $clientContainer->con_status = 1;
                $clientContainer->conrate = $request->input('conrate');
                $clientContainer->save();

                DB::table("batches")
                    ->where("code", $request->input('batchname'))
                    ->update([
                        'remaining'=>$remain,
                        'updated_at'=>now(),
                        ]);

                if ($remain<=1.00){
                    DB::table("batches")
                        ->where("code", $request->input('batchname'))
                        ->update([
                            'status'=>false,
                        ]);
                }

                return redirect('container_transactions')->with('success','Container transaction has been created successfully');
            }

        } else {
            return redirect()->back()->with('error', 'The fuel you requested is above the remaining balance in that batch. Please choose another one or request a lesser amount.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContainerTransaction  $containerTransaction
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContainerTransaction  $containerTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ContainerTransaction  $containerTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ContainerTransaction  $containerTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = ContainerTransaction::findOrFail($id);
        $transaction->delete();

        if ($transaction->delete()) {
            try{

                $batch = Batch::where('code',$transaction->batch)->firstOrFail();
                $remainder = $transaction->concapacity + $batch->remaining;
                DB::table("batches")->where("code", $transaction->batchname)->update(['remaining'=>$remainder]);

                $container = Container::where('conname',$transaction->container)->first();
                $container->concapacity -= $transaction->concapacity;
                $container->conbalance -= $transaction->concapacity;
                $container->save();

                return redirect('container_transactions')->with('success','Container transaction has been deleted successfully');

            } catch(\Exception $e){
                echo "Error - ".$e;
            }

        }
    }


    public function getContainer($id)
    {

        $container = DB::table("containers")
          ->where("client",$id)
          ->pluck("conname");

        return response()->json($container);
    }

    public function getTransactionsReport()
    {
        $clients = Client::all();
        return view('ctransactions.transaction-report',compact('clients'));
    }

    public function postTransactionReport(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'client' => 'required',
            'container' => 'required'
        ]);

        if ($validator->fails())
        {
            return back()->withErrors($validator)->withInput();
        }

        $clients = Client::all();
        $transactions = ContainerTransaction::where('container',$request->input('container'))->get();
        $cont_number = $request->input('container');

        return view('ctransactions.transaction-report',compact('clients','transactions','cont_number'));
    }
}
