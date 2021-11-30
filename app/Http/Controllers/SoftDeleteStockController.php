<?php

namespace App\Http\Controllers;

use App\Models\StockIssue;
use Illuminate\Http\Request;

class SoftDeleteStockController extends Controller
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
     * Get Soft Deleted StockIssue.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedStockIssue($id)
    {
        $stockissue = StockIssue::onlyTrashed()->where('id', $id)->get();
        if (count($stockissue) !== 1) {
            return redirect('/stockissues/deleted/')->with('error', 'No stock issues trashed so far.');
        }

        return $stockissue[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stockissues = StockIssue::onlyTrashed()->get();

        return View('stockissues.show-deleted-stockissues', compact('stockissues'));
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
        $stockissue = self::getDeletedStockIssue($id);
        $stockissue->restore();

        return redirect('/stockissues/')->with('success', 'Stock Issue restored successfully');
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
        $stockissue = self::getDeletedStockIssue($id);
        $stockissue->forceDelete();

        return redirect('/stockissues/deleted/')->with('success', 'Stock Issue completely destroyed.');
    }
}
