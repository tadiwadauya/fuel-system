<?php

namespace App\Http\Controllers;

use App\Models\CashSale;
use Illuminate\Http\Request;

class SoftDeleteSaleController extends Controller
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
     * Get Soft Deleted CashSale.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedCashSale($id)
    {
        $cashsale = CashSale::onlyTrashed()->where('id', $id)->get();
        if (count($cashsale) !== 1) {
            return redirect('/cashsales/deleted/')->with('error', 'No cash sales trashed so far.');
        }

        return $cashsale[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cashsales = CashSale::onlyTrashed()->get();

        return View('cashsales.show-deleted-cashsales', compact('cashsales'));
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
        $cashsale = self::getDeletedCashSale($id);
        $cashsale->restore();

        return redirect('/cashsales/')->with('success', 'Cash Sale restored successfully');
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
        $cashsale = self::getDeletedCashSale($id);
        $cashsale->forceDelete();

        return redirect('/cashsales/deleted/')->with('success', 'Cash Sale completely destroyed.');
    }
}
