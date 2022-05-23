<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NonAllocation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class NonAllocationController extends Controller
{
    public function index() {
        $non_allocations = NonAllocation::all();
        return view('non_allocations.non_allocations', compact('non_allocations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $users = User::all()->where('allocation','=','Non-allocation');

        return view('non_allocations.create-non_allocation', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
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

        $customer = User::where('paynumber','=',$request->input('paynumber'))->firstOrFail();

        if ($customer->non_allocation != 'NonAllocation') {
            return redirect('non_allocations')->with('error', 'This user can not buy fuel.');
        } else {
            if ($customer->alloc_size != $request->input('alloc_size')) {
                return redirect('non_allocations')->with('error', 'This user does not have this amount of allocation.');
            } else {
                $non_allocation = NonAllocation::create([
                    'user_id'             => $request->input('user_id'),
                    'paynumber'             => $request->input('paynumber'),
                    'allocation'             => $request->input('allocation'),
                    'alloc_size'             => $request->input('alloc_size'),
                ]);

                $non_allocation->save();
            }
        }


        return redirect('non_allocations')->with('success', 'Successfully created Nonallocation.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NonAllocation  $non_allocation
     * @return \Illuminate\Http\Response
     */
    public function show(NonAllocation $non_allocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NonAllocation  $non_allocation
     * @return \Illuminate\Http\Response
     */
    public function edit(NonAllocation $non_allocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NonAllocation  $non_allocation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NonAllocation $non_allocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NonAllocation  $non_allocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(NonAllocation $non_allocation)
    {
        $non_allocation->delete();

        return redirect('non_allocations')->with('success', 'Successfully deleted non_allocation.');
    }

    public function getAllocationss($paynumber) {
        /*$directors = DB::table("users")
            ->where('allocation','=','Director')
            ->get();*/

        $allocations = DB::table("non_allocations")
            ->where("paynumber",$paynumber)
            ->where("deleted_at",'=',null)
            ->pluck("allocation");

        if($allocations == null){
            $allocations = DB::table("users")
                ->where("paynumber",$paynumber)
                ->pluck("alloc_size");
        }

        return response()->json($allocations);
    }

    public function myAllocations(){
        $non_allocations = NonAllocation::where('paynumber', '=', Auth::user()->paynumber)
            ->withTrashed()
            ->get();
        return view('non_allocations.mynon_allocations', compact('non_allocations'));
    }

    public function bulkCreateNonAllocations(){
        $users = User::where('allocation','=','Non-allocation')
            ->get();

        return view('non_allocations.create-bulk-non_allocations', compact('users'));
    }

    public function non_allocationsBatchers(){
        NonAllocation::query()->delete();

        $users = User::where('allocation', '=', 'Non-Allocation')
            ->get();

        $month = date('FY');

        foreach ($users as $user) {

            $alloc = $user->paynumber . $month;

            if($user->paynumber == '791') {
                $alloc_size = 120;
            }else {
                $alloc_size = 60;
            }

            $allocation = NonAllocation::create([
                'user_id' => $user->id,
                'paynumber' => $user->paynumber,
                'allocation' => $alloc,
                'alloc_size' => $alloc_size,
                'balance' => $alloc_size,
            ]);

            $allocation->save();

        }

        $directors = User::where('allocation', '=', 'Director')
            ->get();

        foreach ($directors as $director) {
            $newAlloc = $director->alloc_size + 250;

            if ($director->alloc_size<0){
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
        }

        return redirect('non_allocations')->with('success', 'Fuel NonAllocations for '.date('F Y'). ' have been created successfully');

    }

    public function execAllocations(){
        $allocations = User::where('allocation', '=', 'Director')->get();
        return view('non_allocations.exec-allocations', compact('allocations'));
    }
}
