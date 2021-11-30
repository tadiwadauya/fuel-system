<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use Illuminate\Http\Request;

class SoftDeleteAllocationController extends Controller
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
     * Get Soft Deleted Allocation.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedAllocation($id)
    {
        $allocation = Allocation::onlyTrashed()->where('id', $id)->get();
        if (count($allocation) !== 1) {
            return redirect('/allocations/deleted/')->with('error', 'No allocations trashed so far.');
        }

        return $allocation[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allocations = Allocation::onlyTrashed()->get();

        return View('allocations.show-deleted-allocations', compact('allocations'));
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
        $allocation = self::getDeletedAllocation($id);

        return view('allocations.show-deleted-user')->with($allocation);
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
        $allocation = self::getDeletedAllocation($id);
        $allocation->restore();

        return redirect('/allocations/')->with('success', 'Allocation restored successfully');
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
        $allocation = self::getDeletedAllocation($id);
        $allocation->forceDelete();

        return redirect('/allocations/deleted/')->with('success', 'Allocation completely destroyed.');
    }
}
