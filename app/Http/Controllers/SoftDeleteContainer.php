<?php

namespace App\Http\Controllers;

use App\Models\Container;
use Illuminate\Http\Request;

class SoftDeleteContainer extends Controller
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
     * Get Soft Deleted Container.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedContainer($id)
    {
        $container = Container::onlyTrashed()->where('id', $id)->get();
        if (count($container) !== 1) {
            return redirect('/containers/deleted/')->with('error', 'No containers trashed so far.');
        }

        return $container[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $containers = Container::onlyTrashed()->join('clients','containers.client' , '=', 'clients.id')
            ->get();;

        return View('containers.deleted-containers', compact('containers'));
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
        $container = self::getDeletedContainer($id);

        return view('containers.show-deleted-user')->with($container);
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
        $container = self::getDeletedContainer($id);
        $container->restore();

        return redirect('/containers/')->with('success', 'Container restored successfully');
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
        $container = self::getDeletedContainer($id);
        $container->forceDelete();

        return redirect('/containers/deleted/')->with('success', 'Container completely destroyed.');
    }
}
