<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Client;
use App\Models\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ContainerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $containers = Container::join('clients','containers.client' , '=', 'clients.id')
        ->get();

        return view('containers.containers', compact('containers'));
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
        return view('containers.add-container', compact('clients', 'batches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $selectedBatch = Batch::where('code', $request->input('batchname'))->first();

        $selectedClient = Client::where('id', $request->input('client'))->first();

        if ($request->input('concapacity') <= $selectedBatch->remaining ) {
            $remain = $selectedBatch->remaining - $request->input('concapacity');

            $date = date('Ymd');
            $letter = chr(64+rand(0,26));
            $time = date('His');

            $letters = substr(preg_replace('/\s+/', '', $selectedClient->cli_name),0,3);

            $string = (strtoupper(preg_replace('/\s+/', '', $letters))).$date.'.'.$letter.$time;

            $request->merge([
                'conname' => $string,
                'conbalance' => $request->input('concapacity')
            ]);

        } else {
            return redirect()->back()->with('error', 'The fuel you requested is above the remaining balance in that batch. Please choose another one or request a lesser amount.');
        }

        $validator = Validator::make($request->all(),
            [
                'client'                  => 'required',
                'conname'                  => 'required|max:255|unique:containers',
                'batchname'                  => 'required|max:255',
                'concapacity'                  => 'required|max:255',
                'conbalance'                  => 'required|max:255',
                'conrate'                  => 'required|max:255',
            ],
            [
                'client.required'       => 'Who is purchased this container?',
                'conname.unique'       => 'This container has to be unique, please try again later.',
                'conname.required'       => 'You will need to provide a name for the container.',
                'batchname.required'       => 'Where is this fuel being deducted from?',
                'concapacity.required'       => 'How much fuel was purchased?',
                'conbalance.required'       => 'How much fuel does this container have?',
                'conrate.required'       => 'At what price will this be sold at?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $container = Container::create([
            'client'             => $request->input('client'),
            'conname'             => $request->input('conname'),
            'batchname'             => $request->input('batchname'),
            'concapacity'             => $request->input('concapacity'),
            'conbalance'             => $request->input('conbalance'),
            'conrate'             => $request->input('conrate'),
            'con_status'             => true,
        ]);

        $container->save();

        if ($container->save()){
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
        }

        return redirect('containers')->with('success', $request->input('conname').' has been added successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Http\Response
     */
    public function show(Container $container)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Http\Response
     */
    public function edit(Container $container)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Container $container)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Http\Response
     */
    public function destroy(Container $container)
    {
        $container->delete();
        if ($container->delete()) {
            $batch = Batch::where('code',$container->batchname)->firstOrFail();
            $remainder = $container->conbalance + $batch->remaining;
            DB::table("batches")->where("code", $container->batchname)->update(['remaining'=>$remainder]);
        }

        return redirect('containers')->with('success', 'Container deleted.');
    }

    public function activeContainers(){
        $containers = Container::where('con_status', true)->join('clients','containers.client' , '=', 'clients.id')
            ->get();

        return view('containers.active-containers', compact('containers'));
    }

    public function emptyContainers(){
        $containers = Container::where('con_status', false)->join('clients','containers.client' , '=', 'clients.id')
            ->get();

        return view('containers.empty-containers', compact('containers'));
    }
}
