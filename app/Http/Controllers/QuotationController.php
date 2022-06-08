<?php

namespace App\Http\Controllers;

use App\Mail\FuelQuotation;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Mail;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quotations = Quotation::all();
        return view('quotations.quotations', compact('quotations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('quotations.create-quote');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $quoteNum = DB::table('quotations')->latest('id')->first();

        if ($quoteNum == null){
            $quoteNum = 'WHEQ1';
        } else{
            $quoteNum = 'WHEQ'.($quoteNum->id+1);
        }

        $request->merge([
            'quote_num' => $quoteNum,
        ]);

        $validator = Validator::make($request->all(),
            [
                'quote_num'                  => 'required|max:255|unique:quotations',
                'client'                  => 'required|max:255',
                'email'                  => 'nullable|email',
                'email_cc'                  => 'nullable|max:255',
                'price'                  => 'required|max:255',
                'quantity'                  => 'required|max:255',
                'currency'                  => 'required|max:255',
                'amount'                  => 'required|max:25',
                'notes'                  => 'nullable|max:255',
                'done_by'                  => 'required|max:255',
            ],
            [
                'quote_num.unique'       => 'Quotation reference has to be unique. Please wait a bit before trying again.',
                'quote_num.required'       => 'Somehow the quotation code was not generated',
                'client.required'       => 'This quotation is to who?',
                'email.email'       => 'The email address has to be a valid one.',
                'price.required'       => 'At what price are we selling?',
                'quantity.required'       => 'How much fuel is needed?',
                'currency.required'       => 'In what currency is this sold in?',
                'amount.required'       => 'There should be a total amount here?',
                'notes.nullable'       => 'Is there any additional info?',
                'done_by.required'       => 'Please ensure that you\'re logged in',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $quotation = Quotation::create([
            'quote_num'             => $request->input('quote_num'),
            'client'             => $request->input('client'),
            'email'             => $request->input('email'),
            'email_cc'             => $request->input('email_cc'),
            'price'             => $request->input('price'),
            'quantity'             => $request->input('quantity'),
            'currency'             => $request->input('currency'),
            'amount'             => $request->input('amount'),
            'notes'             => $request->input('notes'),
            'done_by'             => $request->input('done_by'),
        ]);

        $quotation->save();

        return redirect('showquote/'.$quotation->id)->with('success', 'Quotation generated.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function show(Quotation $quotation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function edit(Quotation $quotation)
    {
        return view('quotations.edit-quotation', compact('quotation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quotation $quotation)
    {
        $validator = Validator::make($request->all(),
            [
                'client'                  => 'required|max:255',
                'email'                  => 'nullable|email',
                'email_cc'                  => 'nullable|max:255',
                'price'                  => 'required|max:255',
                'quantity'                  => 'required|max:255',
                'currency'                  => 'required|max:255',
                'amount'                  => 'required|max:25',
                'notes'                  => 'nullable|max:255',
            ],
            [

                'client.required'       => 'This quotation is to who?',
                'email.email'       => 'The email address has to be a valid one.',
                'price.required'       => 'At what price are we selling?',
                'quantity.required'       => 'How much fuel is needed?',
                'currency.required'       => 'In what currency is this sold in?',
                'amount.required'       => 'There should be a total amount here?',
                'notes.nullable'       => 'Is there any additional info?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $quotation->client = $request->input('client');
        $quotation->email = $request->input('email');
        $quotation->email_cc = $request->input('email_cc');
        $quotation->price = $request->input('price');
        $quotation->quantity = $request->input('quantity');
        $quotation->currency = $request->input('currency');
        $quotation->amount = $request->input('amount');
        $quotation->notes = $request->input('notes');

        $quotation->save();

        return redirect()->back()->with('success', 'Quotation updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quotation $quotation)
    {
        $quotation->delete();
        return redirect('quotations')->with('success','Quotation deleted successfully.');
    }

    public function generateQuotePdf($id){
        $quotation = Quotation::findOrFail($id);

        $pdf = \PDF::loadView('quotations.form', compact('quotation'));
        return $pdf->stream($quotation->quote_num.".pdf");
    }

    public function showQuoteForm($id){
        $quotation = Quotation::findOrFail($id);

        return view('quotations.preview', compact('quotation'));
    }

    public function emailQuotation($id){
        $quotation = Quotation::findOrFail($id);

        if ($quotation->email == null){
            return redirect()->back()->with('error', 'You did not provide an email address when you added this quote.');
        }

        try {
            $details = [
                'greeting' => 'Good day, ' . $quotation->client,
                'id' => $quotation->id,
                'quote_num' => $quotation->quote_num,
                'client' => $quotation->client,
                'email' => $quotation->email,
                'email_cc' => $quotation->email_cc,
                'price' => $quotation->price,
                'quantity' => $quotation->quantity,
                'currency' => $quotation->currency,
                'amount' => $quotation->amount,
                'notes' => $quotation->notes,
                'done_by' => $quotation->done_by,
                'created_at' => $quotation->created_at
            ];

            Mail::to($quotation->email)->send(new FuelQuotation($details));

            // if ($quotation->email_cc == null){
            //     Mail::to($quotation->email)->send(new FuelQuotation($details));
            // } elseif ($quotation->email_cc != null){
            //     Mail::to($quotation->email)->cc($quotation->email_cc)->send(new FuelQuotation($details));
            // }

        } catch (\Exception $exception){
            echo 'Error - '.$exception;
        }

        return redirect()->back()->with('success', 'Quotation has been sent successfully.');
    }

}
