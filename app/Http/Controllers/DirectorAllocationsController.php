<?php

namespace App\Http\Controllers;

use App\Models\DirectorAllocations;
use Illuminate\Http\Request;

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

    public function new()
    {
        $p = "2A";

        $da = DirectorAllocations::where('paynumber', '=', $p)
            ->whereMonth('created_at', date('m'))
            ->firstOrFail();

        if ($da) {
            $da->balance = $da->balance - 10;
            $da->used = $da->used + 10;

            $da->save();

            dd($da);
        }
    }
}
