<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class SoftDeleteTransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('manadmin');
    }

    /**
     * Get Soft Deleted Transaction.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedTransaction($id)
    {
        $transaction = Transaction::onlyTrashed()->where('id', $id)->get();
        if (count($transaction) !== 1) {
            return redirect('/transactions/deleted/')->with('error', 'No transactions trashed so far.');
        }

        return $transaction[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::onlyTrashed()->get();

        return View('transactions.show-deleted-transactions', compact('transactions'));
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
        $transaction = self::getDeletedTransaction($id);
        $transaction->restore();

        return redirect('/transactions/')->with('success', 'Transaction restored successfully');
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
        $transaction = self::getDeletedTransaction($id);
        $transaction->forceDelete();

        return redirect('/transactions/deleted/')->with('success', 'Transaction completely destroyed.');
    }
}
