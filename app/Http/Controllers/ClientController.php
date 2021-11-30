<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Container;
use Illuminate\Http\Request;
use Validator;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all();

        return view('clients.clients', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clients.add-client');
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
                'cli_name'        => 'required|max:255|unique:clients',
                'cli_phone'       => 'required',
                'cli_email'       => 'required',
                'cli_email_cc'    => 'nullable',
                'cli_contact'     => 'required',
            ],
            [
                'cli_name.unique'          => 'We already have this client in the system.',
                'cli_name.required'        => 'We obviously need a client name.',
                'cli_phone.required'       => 'The system needs a Phone number to save this client',
                'cli_email.required'       => 'Client email address or contact person email address.',
                'cli_email_cc.required'    => 'Client cc email address or contact person email address.',
                'cli_contact.required'     => 'Who is the point person?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $client = Client::create([
            'cli_name'             => $request->input('cli_name'),
            'cli_phone'             => $request->input('cli_phone'),
            'cli_email'             => $request->input('cli_email'),
            'cli_email_cc'             => $request->input('cli_email_cc'),
            'cli_contact'             => $request->input('cli_contact'),
        ]);

        $client->save();

        if ($client->save())
        {
            try{
                $date = date('Ymd');
                $letter = chr(64+rand(0,26));
                $time = date('His');

                $letters = substr(preg_replace('/\s+/', '', $client->cli_name),0,3);

                $string = (strtoupper(preg_replace('/\s+/', '', $letters))).$date.'.'.$letter.$time;
                $container = Container::create([
                    'client' => $client->id,
                    'conname' => $string,
                    'concapacity' => 0,
                    'conbalance' => 0,
                    'conrate' => 0,
                    'con_status' => 0,
                ]);
                $container->save();

                return redirect('clients')->with('success', 'Client added successfully.');

            }catch(\Exception $e){
                echo "Error - ".$e;
            }

        }

        return redirect('clients')->with('success', 'Client added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        return view('clients.edit-client', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $validator = Validator::make($request->all(),
            [
                'cli_name'                  => 'required|max:255|unique:clients,cli_name,'.$client->id,
                'cli_phone'                  => 'required',
                'cli_email'                  => 'required',
                'cli_email_cc'                  => 'nullable',
                'cli_contact'                  => 'required',
            ],
            [
                'cli_name.unique'          => 'We already have this client in the system.',
                'cli_name.required'        => 'We obviously need a client name.',
                'cli_phone.required'       => 'The system needs a Phone number to save this client',
                'cli_email.required'       => 'Client email address or contact person email address.',
                'cli_email_cc.required'    => 'Client cc email address or contact person email address.',
                'cli_contact.required'     => 'Who is the point person?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $client->cli_name = $request->input('cli_name');
        $client->cli_phone = $request->input('cli_phone');
        $client->cli_email = $request->input('cli_email');
        $client->cli_email_cc = $request->input('cli_email_cc');
        $client->cli_contact = $request->input('cli_contact');

        $client->save();

        return redirect()->back()->with('success', 'Client info updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect('clients')->with('success','Client deleted successfully.');
    }
}
