<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class SoftDeleteInvoice extends Controller
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
     * Get Soft Deleted Invoice.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedInvoice($id)
    {
        $invoice = Invoice::onlyTrashed()->where('id', $id)->get();
        if (count($invoice) !== 1) {
            return redirect('/invoices/deleted/')->with('error', 'No invoices trashed so far.');
        }

        return $invoice[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::onlyTrashed()->join('clients','invoices.client' , '=', 'clients.id')
            ->select('invoices.id', 'invoices.trans_code','clients.cli_name', 'invoices.voucher', 'invoices.container', 'invoices.quantity', 'invoices.reg_num', 'invoices.driver', 'invoices.done_by', 'invoices.deleted_at')
            ->get();;

        return View('invoices.deleted-invoices', compact('invoices'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = self::getDeletedInvoice($id);

        return view('invoices.show-deleted-invoice', compact('invoice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $invoice = self::getDeletedInvoice($id);
        $invoice->restore();

        return redirect('/invoices/')->with('success', 'Invoice restored successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = self::getDeletedInvoice($id);
        $invoice->forceDelete();

        return redirect('/invoices/deleted/')->with('success', 'Invoice completely destroyed.');
    }
}
