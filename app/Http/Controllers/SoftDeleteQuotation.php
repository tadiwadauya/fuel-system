<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\Request;

class SoftDeleteQuotation extends Controller
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
     * Get Soft Deleted Quotation.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedQuotation($id)
    {
        $quotation = Quotation::onlyTrashed()->where('id', $id)->get();
        if (count($quotation) !== 1) {
            return redirect('/quotations/deleted/')->with('error', 'No quotations trashed so far.');
        }

        return $quotation[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quotations = Quotation::onlyTrashed()->get();

        return View('quotations.deleted-quotations', compact('quotations'));
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
        $quotation = self::getDeletedQuotation($id);

        return view('quotations.show-deleted-quotation', compact('quotation'));
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
        $quotation = self::getDeletedQuotation($id);
        $quotation->restore();

        return redirect('/quotations/')->with('success', 'Quotation restored successfully');
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
        $quotation = self::getDeletedQuotation($id);
        $quotation->forceDelete();

        return redirect('/quotations/deleted/')->with('success', 'Quotation completely destroyed.');
    }
}
