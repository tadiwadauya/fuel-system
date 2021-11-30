<?php

namespace App\Http\Controllers;

use App\Models\Fsetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FsettingController extends Controller
{

    public function fuelSettings(){

        $fsetting = Fsetting::findOrFail(1)->first();

        //dd($fsetting->id);
        return view('fuelsettings.fuelsettings', compact('fsetting'));
    }

    public function updateMaSettingsEFuel(Request $request){

        $fsetting = Fsetting::findOrFail(1);
        $validator = Validator::make($request->all(),
            [
                'petrol_available'                  => 'required|boolean',
                'diesel_available'                  => 'required|boolean',
                'petrol_price'                  => 'required_if:petrol_available,1',
                'diesel_price'                  => 'required_if:diesel_available,1',
                'oil_price'                  => 'nullable',
                'pay_method'                  => 'required',
                'last_changed_by'                  => 'required',
            ],
            [
                'petrol_available.required'         => 'The system needs to know if there is petrol or not.',
                'diesel_available.required'         => 'The system needs to know if there is diesel or not.',
                'petrol_price.required'       => 'You have to set the Petrol price, provided its available',
                'diesel_price.required'       => 'You have to set the Diesel price, provided its available',
                'pay_method.required'       => 'You have to set the payment method to be used.',
                'last_changed_by.required'       => 'Please make sure you are logged in',

            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $fsetting->petrol_available = $request->input('petrol_available');
        $fsetting->diesel_available = $request->input('diesel_available');
        $fsetting->petrol_price = $request->input('petrol_price');
        $fsetting->diesel_price = $request->input('diesel_price');
        $fsetting->oil_price = $request->input('oil_price');
        $fsetting->pay_method = $request->input('pay_method');
        $fsetting->last_changed_by = $request->input('last_changed_by');

        $fsetting->save();

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fsetting  $fsetting
     * @return \Illuminate\Http\Response
     */
    public function show(Fsetting $fsetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fsetting  $fsetting
     * @return \Illuminate\Http\Response
     */
    public function edit(Fsetting $fsetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fsetting  $fsetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fsetting $fsetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fsetting  $fsetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fsetting $fsetting)
    {
        //
    }
}
