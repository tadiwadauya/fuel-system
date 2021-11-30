<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Container;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Validator;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::join('clients','invoices.client' , '=', 'clients.id')
            ->select('invoices.id', 'invoices.trans_code','clients.cli_name', 'invoices.voucher', 'invoices.container', 'invoices.quantity', 'invoices.reg_num', 'invoices.driver', 'invoices.done_by', 'invoices.deleted_at')
            ->get();

        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        $containers = Container::where('con_status', true)->get();
        return view('invoices.create-invoice', compact('clients', 'containers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tdate = date('Ymd');
        $tletter = chr(64+rand(0,26));
        $ttime = date('His');

        $trans_code = 'I'.$tdate.'.'.$tletter.$ttime;
        $request->merge([
            'trans_code' => $trans_code,
            'created_at' => $request->input('created_at')
        ]);

        $validator = Validator::make($request->all(),
            [
                'trans_code'                  => 'required|max:255|unique:invoices',
                'client'                  => 'required|max:255',
                'voucher'                  => 'required|max:255|unique:invoices',
                'container'                  => 'required|max:255',
                'quantity'                  => 'required|max:255',
                'reg_num'                  => 'required|max:25',
                'driver'                  => 'required|max:255',
                'done_by'                  => 'required|max:255',
                'created_at'                  => 'required',
            ],
            [
                'trans_code.unique'       => 'Transaction code has to be unique. Please wait a bit before trying again.',
                'trans_code.required'       => 'Somehow the transaction code was not generated',
                'client.required'       => 'This invoice belongs to who?',
                'voucher.unique'       => 'We need a unique voucher number for this invoice',
                'voucher.required'       => 'We need a voucher number for this invoice',
                'container.required'       => 'Where was this fuel deducted from?',
                'quantity.required'       => 'How much fuel was issued out?',
                'reg_num.required'       => 'What was the reg number of the vehicle issued into?',
                'driver.required'       => 'Who represented the client?',
                'done_by.required'       => 'Please ensure that you\'re logged in',
                'created_at.required'       => 'When was this transaction done?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $container = Container::where('conname','=',$request->input('container'))->firstOrFail();

        if ($request->input('quantity') <= $container->conbalance) {
            $remaining = $container->conbalance - $request->input('quantity');
            $container->conbalance = $remaining;
            $container->updated_at = now();
            $container->save();

            if ($container->save()){
                if ($remaining<=1.00){
                    DB::table("containers")
                        ->where("conname", $request->input('container'))
                        ->update([
                            'con_status'=>false,
                        ]);
                }
            }
        } else {
            return back()->with('error', 'Fuel requested is more than the container balance.');
        }

        $invoice = Invoice::create([
            'trans_code'             => $request->input('trans_code'),
            'client'             => $request->input('client'),
            'voucher'             => $request->input('voucher'),
            'container'             => $request->input('container'),
            'quantity'             => $request->input('quantity'),
            'reg_num'             => $request->input('reg_num'),
            'driver'             => $request->input('driver'),
            'done_by'             => $request->input('done_by'),
            'created_at'             => $request->input('created_at'),
        ]);

        $invoice->save();

        return redirect('showform/'.$invoice->id)->with('success', 'Invoice recorded.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        $client = Client::where('id',$invoice->client)->first();

        return view('invoices.edit-invoice', compact('invoice', 'client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(),
            [
                'reg_num'                  => 'required',
                'driver'                  => 'required',
            ],
            [
                'reg_num.required'       => 'Where was the fuel issued into?',
                'driver.required'       => 'Who represented the client?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $invoice->reg_num = $request->input('reg_num');
        $invoice->driver = $request->input('driver');

        $invoice->save();

        return redirect()->back()->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect('invoices')->with('success','Invoice deleted successfully.');
    }



    public function showform($id){
        $invoice = Invoice::findOrFail($id);

        return view('invoices.preview', compact('invoice'));
    }

    public function getContainers($id) {

        $containers = DB::table("containers")
                ->where("client",$id)
                ->where("deleted_at",'=',null)
                ->select("id","conname", "conbalance")
                ->orderBy("id", 'desc')
                ->get();

        /*$containers = DB::table("containers")
            ->where("client", $id)
            ->pluck("conname");*/

        return response()->json($containers);
    }

    public function generateVoucher(){
        $tletter = chr(64+rand(0,26));
        $pin = mt_rand(1000000, 9999999).mt_rand(1000000, 9999999);

        $voucher = $tletter.$pin;

        $voucherNum = Invoice::where('voucher', '=', $voucher)->first();
        while ($voucherNum === null) {
            return response()->json($voucher);
        }

    }

    public function emailInvoice($id){

        $invoice = Invoice::join('clients','invoices.client' , '=', 'clients.id')
            ->join('containers','invoices.container' , '=', 'containers.conname')
            ->select('invoices.id', 'invoices.trans_code','clients.cli_name','clients.cli_email','clients.cli_email_cc', 'invoices.voucher', 'containers.conname','containers.conrate', 'invoices.quantity', 'invoices.reg_num', 'invoices.driver', 'invoices.done_by', 'invoices.created_at', 'invoices.deleted_at')
            ->where('invoices.id', $id)
            ->firstOrFail();

        if ($invoice->cli_email == null){
            return redirect()->back()->with('error', 'The client\'s email address is not configured for this client.');
        }

        try {
            $amount = number_format($invoice->conrate*$invoice->quantity,2);
            $details = [
                'greeting' => 'Good day, ' . $invoice->cli_name,
                'id' => $invoice->id,
                'trans_code' => $invoice->trans_code,
                'cli_name' => $invoice->cli_name,
                'cli_email' => $invoice->cli_email,
                'cli_email_cc' => $invoice->cli_email_cc,
                'voucher' => $invoice->voucher,
                'container' => $invoice->container,
                'conname' => $invoice->conname,
                'conrate' => $invoice->conrate,
                'quantity' => $invoice->quantity,
                'amount' => $amount,
                'reg_num' => $invoice->reg_num,
                'driver' => $invoice->driver,
                'done_by' => $invoice->done_by,
                'created_at' => $invoice->created_at
            ];

            if ($invoice->cli_email_cc == null){
                Mail::to($invoice->cli_email)->send(new FuelQuotation($details));
            } elseif ($invoice->cli_email_cc != null){
                Mail::to($invoice->cli_email)->cc($invoice->email_cc)->send(new FuelQuotation($details));
            }

        } catch (\Exception $exception){
            echo 'Error - '.$exception;
        }

        return redirect()->back()->with('success', 'Invoice has been sent successfully.');
    }
    public function generateInvoicePdf($id){

        $invoice = Invoice::findorFail($id);

        try {
            $mpdf = new \Mpdf\Mpdf([
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 48, //48
                'margin_bottom' => 25,
                'margin_header' => 10, //10
                'margin_footer' => 10,
            ]);

            $html = \View::make('invoices.forms' )->with('invoice', $invoice);
            $html = $html->render();

            $mpdf->SetProtection(array('print'));
            $mpdf->SetTitle("Whelson Fuel Qoutation");
            $mpdf->SetAuthor("Tadiwanashe Dauya");
            $mpdf->showWatermarkImage = true;
            $mpdf->watermark_font = 'DejaVuSansCondensed';
            $mpdf->watermarkTextAlpha = 0.1;
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML($html);
            $mpdf->Output("WhelsonInvoice".$invoice->paynumber.'.pdf', 'I');
        } catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
            // Process the exception, log, print etc.
            echo $e->getMessage();

        }
    }
}
