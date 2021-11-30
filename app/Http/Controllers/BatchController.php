<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;
use Validator;

class BatchController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $batches = Batch::all();

        return view('batches.batches', compact('batches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('batches.add-batch');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            'remaining' => $request->input('quantity')
        ]);

        $validator = Validator::make($request->all(),
            [
                'code'                  => 'required|max:255|unique:batches',
                'price'                  => 'required',
                'quantity'                  => 'required',
                'remaining'                  => 'required',
            ],
            [
                'code.unique'       => 'We already have this batch code in the system, try another name for the batch.',
                'code.required'       => 'We need a batch code for this fuel batch.',
                'price.required'       => 'What will be the price for this batch?',
                'quantity.required'       => 'How much fuel is in this batch?',
                'remaining.required'       => 'How much fuel is in this batch?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $batch = Batch::create([
            'code'             => $request->input('code'),
            'price'             => $request->input('price'),
            'quantity'             => $request->input('quantity'),
            'remaining'             => $request->input('remaining'),
        ]);

        $batch->save();

        return redirect('batches')->with('success', 'Batch added successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function show(Batch $batch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function edit(Batch $batch)
    {
        return view('batches.edit-batch', compact('batch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Batch $batch)
    {
        $validator = Validator::make($request->all(),
            [
                'code'                  => 'required|max:255|unique:batches,code,'.$batch->id,
                'price'                  => 'required',
                'quantity'                  => 'required',
                'remaining'                  => 'required',
            ],
            [
                'code.unique'       => 'We already have this batch code in the system, try another name for the batch.',
                'code.required'       => 'We need a batch code for this fuel batch.',
                'price.required'       => 'What will be the price for this batch?',
                'quantity.required'       => 'How much fuel is in this batch?',
                'remaining.required'       => 'How much fuel is in this batch?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $batch->code = $request->input('code');
        $batch->price = $request->input('price');
        $batch->quantity = $request->input('quantity');

        $batch->save();

        return redirect()->back()->with('success', 'Batch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batch $batch)
    {
        $batch->delete();

        return redirect('batches')->with('success', 'Batch deleted successfully');
    }
}
