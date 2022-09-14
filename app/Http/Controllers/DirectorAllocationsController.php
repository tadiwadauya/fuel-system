<?php

namespace App\Http\Controllers;

use App\Models\DirectorAllocations;
use Illuminate\Http\Request;
use App\Models\User;
class DirectorAllocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dallocs = DirectorAllocations::all();

        return view('dallocs.index', compact('dallocs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DirectorAllocations  $directorAllocations
     * @return \Illuminate\Http\Response
     */
    public function show(DirectorAllocations $directorAllocations)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DirectorAllocations  $directorAllocations
     * @return \Illuminate\Http\Response
     */
    public function edit(DirectorAllocations $directorAllocations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DirectorAllocations  $directorAllocations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DirectorAllocations $directorAllocations)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DirectorAllocations  $directorAllocations
     * @return \Illuminate\Http\Response
     */
    public function destroy(DirectorAllocations $directorAllocations)
    {
        //
    }

    public function execAllocationsPrev()
    {
        $users = User::all()->where('allocation','=','Director');
        $allocations = DirectorAllocations::all();
        return view('allocations.exec-allocations-prev', compact('allocations', 'users'));
    }
}
