<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class SoftDeleteFuelBatch extends Controller
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
     * Get Soft Deleted Batch.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedFuelBatch($id)
    {
        $batch = Batch::onlyTrashed()->where('id', $id)->get();
        if (count($batch) !== 1) {
            return redirect('/batches/deleted/')->with('error', 'No fuel batches trashed so far.');
        }

        return $batch[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $batches = Batch::onlyTrashed()->get();

        return View('batches.deleted-batches', compact('batches'));
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
        $batch = self::getDeletedFuelBatch($id);

        return view('batches.show-deleted-user')->with($batch);
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
        $batch = self::getDeletedFuelBatch($id);
        $batch->restore();

        return redirect('/batches/')->with('success', 'Batch restored successfully');
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
        $batch = self::getDeletedFuelBatch($id);
        $batch->forceDelete();

        return redirect('/batches/deleted/')->with('success', 'Batch completely destroyed.');
    }
}
