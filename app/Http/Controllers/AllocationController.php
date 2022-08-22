<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\DirectorAllocations;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class AllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allocations = Allocation::all();
        return view('allocations.allocations', compact('allocations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all()->where('allocation', '=', 'Allocation');

        return view('allocations.create-allocation', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_id'                  => 'required|max:255',
                'paynumber'                  => 'required|max:255',
                'allocation'                  => 'required|max:255|unique:allocations',
                'alloc_size'                  => 'required|max:255',
            ],
            [
                'user_id.required'       => 'Whose allocation is this?',
                'paynumber.required'       => 'Whose allocation is this? Paynumber?',
                'allocation.required'       => 'This allocation is meant for which month?',
                'alloc_size.required'       => 'What is the size of the allocation?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $customer = User::where('paynumber', '=', $request->input('paynumber'))->firstOrFail();

        if ($customer->allocation != 'Allocation') {
            return redirect('allocations')->with('error', 'This user does not have a fuel allocation.');
        } else {
            if ($customer->alloc_size != $request->input('alloc_size')) {
                return redirect('allocations')->with('error', 'This user does not have this amount of allocation.');
            } else {
                $allocation = Allocation::create([
                    'user_id'             => $request->input('user_id'),
                    'paynumber'             => $request->input('paynumber'),
                    'allocation'             => $request->input('allocation'),
                    'alloc_size'             => $request->input('alloc_size'),
                ]);

                $allocation->save();
            }
        }


        return redirect('allocations')->with('success', 'Successfully created allocation.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Allocation  $allocation
     * @return \Illuminate\Http\Response
     */
    public function show(Allocation $allocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Allocation  $allocation
     * @return \Illuminate\Http\Response
     */
    public function edit(Allocation $allocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Allocation  $allocation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Allocation $allocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Allocation  $allocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Allocation $allocation)
    {
        $allocation->delete();

        return redirect('allocations')->with('success', 'Successfully deleted allocation.');
    }

    public function getAllocations($paynumber)
    {
        /*$directors = DB::table("users")
            ->where('allocation','=','Director')
            ->get();*/

        $allocations = DB::table("allocations")
            ->where("paynumber", $paynumber)
            ->where("deleted_at", '=', null)
            ->pluck("allocation");

        if ($allocations == null) {
            $allocations = DB::table("users")
                ->where("paynumber", $paynumber)
                ->pluck("alloc_size");
        }

        return response()->json($allocations);
    }

    public function myAllocations()
    {
        $allocations = Allocation::where('paynumber', '=', Auth::user()->paynumber)
            ->withTrashed()
            ->get();
        return view('allocations.myallocations', compact('allocations'));
    }

    public function bulkCreateAllocations()
    {
        $users = User::where('allocation', '=', 'Allocation')
            ->orWhere('allocation', '=', 'Director')
            ->get();

        return view('allocations.create-bulk-allocations', compact('users'));
    }

    public function allocationsBatcher()
    {
        Allocation::query()->delete();

        $users = User::where('allocation', '=', 'Allocation')
            ->get();

        $month = date('FY');

        foreach ($users as $user) {
            $alloc = $user->paynumber . $month;

            $allocation = Allocation::create([
                'user_id' => $user->id,
                'paynumber' => $user->paynumber,
                'allocation' => $alloc,
                'alloc_size' => $user->alloc_size,
                'balance' => $user->alloc_size,
            ]);

            $allocation->save();
        }

        // $directors = User::where('allocation', '=', 'Director')
        //     ->get();

        // foreach ($directors as $director) {
        //     $newAlloc = $director->alloc_size + 250;

        //     if ($director->alloc_size < 0) {
        //         DB::table("users")
        //             ->where("allocation", '=', 'Director')
        //             ->where("id", '=', $director->id)
        //             ->update(['alloc_size' => '250.00', 'updated_at' => now()]);
        //     } else {
        //         DB::table("users")
        //             ->where("allocation", '=', 'Director')
        //             ->where("id", '=', $director->id)
        //             ->update(['alloc_size' => $newAlloc, 'updated_at' => now()]);
        //     }
        // }

        if ($allocation->save()) {
            $directors = User::where('allocation', '=', 'Director')
                ->get();

            foreach ($directors as $director) {
                $dalloc = new DirectorAllocations();
                $newAlloc = $director->alloc_size + 250;

                if ($director->alloc_size < 0) {
                    DB::table("users")
                        ->where("allocation", '=', 'Director')
                        ->where("id", '=', $director->id)
                        ->update(['alloc_size' => '250.00', 'updated_at' => now()]);
                } else {
                    DB::table("users")
                        ->where("allocation", '=', 'Director')
                        ->where("id", '=', $director->id)
                        ->update(['alloc_size' => $newAlloc, 'updated_at' => now()]);
                }

                $dalloc->paynumber = $director->paynumber;
                $dalloc->allocation = $director->paynumber . $director->allocation  . $month;
                $dalloc->quantity = $newAlloc;
                $dalloc->balance = $newAlloc;

                $dalloc->save();
            }
        }

        return redirect('allocations')->with('success', 'Fuel Allocations for ' . date('F Y') . ' have been created successfully');
    }

    public function execAllocations()
    {
        $allocations = User::where('allocation', '=', 'Director')->get();
        return view('allocations.exec-allocations', compact('allocations'));
    }
}
