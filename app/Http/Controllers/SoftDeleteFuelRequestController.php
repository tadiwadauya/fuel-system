<?php

namespace App\Http\Controllers;

use App\Models\Frequest;
use Illuminate\Http\Request;

class SoftDeleteFuelRequestController extends Controller
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
     * Get Soft Deleted Frequest.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedFuelRequest($id)
    {
        $frequest = Frequest::onlyTrashed()->where('id', $id)->get();
        if (count($frequest) !== 1) {
            return redirect('/fuelrequests/deleted/')->with('error', 'No fuel requests trashed so far.');
        }

        return $frequest[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $frequests = Frequest::onlyTrashed()->get();

        return View('fuelrequests.show-deleted-frequests', compact('frequests'));
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
        $frequest = self::getDeletedFuelRequest($id);

        return view('fuelrequests.show-deleted-user')->with($frequest);
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
        $frequest = self::getDeletedFuelRequest($id);
        $frequest->restore();

        return redirect('/frequests/')->with('success', 'Fuel request restored successfully');
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
        $frequest = self::getDeletedFuelRequest($id);
        $frequest->forceDelete();

        return redirect('/frequests/deleted/')->with('success', 'Fuel request completely destroyed.');
    }
}
