<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\CashSale;
use App\Models\Frequest;
use App\Models\Fsetting;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\AlertFinanceDirector;
use App\Notifications\AlertFinanceManager;
use App\Notifications\AlertFuelManager;
use App\Notifications\AlertManagingDirector;
use App\Notifications\AlertTechnicalDirector;
use App\Mail\NewFuelRequestApproval;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;
use Illuminate\Support\Facades\Validator;

class FrequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $frequests = Frequest::all();
        return view('fuelrequests.frequests', compact('frequests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $balance = Allocation::all()
            ->where('paynumber','=',Auth::user()->paynumber)
            ->where('deleted_at','=',null)
            ->sortByDesc('id')
            ->first();

        if (Auth::user()-> department !='Diesel') {
            if ($balance == null) {
                $balance = 0;
            }
        } else {
            $balance = 0;
        }

        $currentMonth = date('m');
        if (Auth::user()->department == 'Diesel'){
            $applicableCash = 0;
        } elseif (Auth::user()->allocation == 'Allocation'){
            $currentCashsales = DB::table('cash_sales')
                ->select(DB::raw('sum(quantity) as currentfuel'))
                ->where('employee','=',Auth::user()->paynumber)
                ->where('deleted_at','=',null)
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->first();

            $applicableCash = (($balance->alloc_size)*0.5) - $currentCashsales->currentfuel;
        } else {
            $applicableCash = 0;
        }

        //dd($applicableCash);

        $fsetting = Fsetting::findOrFail(1);

        /*if (strpos($balance->allocation, date('FY'))) {
            echo "Found!";
        } else {
            echo "Hapana Hapana!!!";
        }*/

        //dd($balance);
        $users = User::all();
        return view('fuelrequests.create-request', compact('users','balance', 'fsetting', 'applicableCash'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fsetting = Fsetting::findOrFail(1);
        $yuza = User::where('paynumber','=',$request->input('employee'))->first();

        if ($request->input('request_type') == 'Cash Sale') {
            if(auth()->user()->allocation == 'Allocation' OR $yuza->allocation == 'Allocation'){
                if ($request->input('applicable') < $request->input('allowed')) {
                    if ($request->input('applicable') >= 0) {
                        if ($request->input('ftype') == 'Petrol') {
                            if ($fsetting->petrol_available == 0) {
                                return redirect()->back()->with('error', 'Sorry, our petrol reserves are currently low at the moment. We will not be doing any cash sales today.');
                            }
                        } elseif ($request->input('ftype') == 'Diesel') {
                            if ($fsetting->diesel_available == 0) {
                                return redirect()->back()->with('error', 'Sorry, our diesel reserves are currently low at the moment. We will not be doing any cash sales today.');
                            }
                        }
                    } else {
                        return redirect()->back()->with('error', 'Sorry, this is beyond your prescribed limit for this month. We\'re just going to ignore that request.');
                    }
                }
            }
            elseif (auth()->user()->allocation == 'Non-allocation' OR $yuza->allocation == 'Non-allocation'){
                if ($request->input('ftype') == 'Petrol') {
                    if ($fsetting->petrol_available == 0) {
                        return redirect()->back()->with('error', 'Sorry, our petrol reserves are currently low at the moment. We will not be doing any cash sales today.');
                    }
                } elseif ($request->input('ftype') == 'Diesel') {
                    if ($fsetting->diesel_available == 0) {
                        return redirect()->back()->with('error', 'Sorry, our diesel reserves are currently low at the moment. We will not be doing any cash sales today.');
                    }
                }
            }
            else {
                return redirect()->back()->with('error', 'Sorry, this is beyond your prescribed limit for this month. We\'re just going to ignore that request.');
            }
        } elseif ($request->input('request_type') == 'Allocation') {
            $yuza = User::where('paynumber', '=', $request->input('employee'))->first();

            $balance = Allocation::where('paynumber', '=', $yuza->paynumber)
                ->where('deleted_at', '=', null)
                ->orderby('id', 'DESC')
                ->first();

            if ($yuza->allocation != 'Allocation') {
                return redirect()->back()->with('error', 'This employee is currently not on fuel allocation');
            }

            if (is_null($balance->balance)) {
                $balance->balance = $balance->alloc_size;

                if ($balance->balance <= 0) {
                    return redirect()->back()->with('error', 'Sorry2, this is beyond your prescribed limit for this month. We\'re just going to ignore that request.');
                }
            } elseif ($balance->balance == 0){
                    return redirect()->back()->with('error', 'Sorry, this is beyond your prescribed limit for this month. We\'re just going to ignore that request.');
            }else {
                if ($balance->balance <= 0) {
                    return redirect()->back()->with('error', 'Sorry, this is beyond your prescribed limit for this month. We\'re just going to ignore that request.');
                }
            }
        }

        $validator = Validator::make($request->all(),
            [
                'request_type'                  => 'required',
                'employee'                  => 'required',
                'quantity'                  => 'required',
                'ftype'                  => 'required',
            ],
            [
                'request_type.required'         => 'Please select the type of fuel allocation this is',
                'employee.required'       => 'Who is this fuel request meant for?',
                'quantity.required'       => 'Is this request for a topup or for a specific quantity?',
                'ftype.required'       => 'What is the type of fuel requested for?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $frequest = Frequest::create([
            'request_type'             => $request->input('request_type'),
            'employee'             => $request->input('employee'),
            'quantity'             => $request->input('quantity'),
            'ftype'             => $request->input('ftype'),
            'status'             => 0,
        ]);

        $frequest->save();

        $fmuser = DB::table('users')
            ->where('position','=','Finance Director')
            ->orWhere('position','=','Technical Director')
            ->get(); //Systems Applications Administrator Finance Manager
        /*$fmuser = User::where('position','=','Systems Applications Administrator')
            ->orWhere('position','=','Systems Administrator')
            ->get(); //Systems Applications Administrator Finance Manager*/

        if ($fmuser == null){
            return redirect()->back()->with('error', 'Please check if the Finance Director and Technical Director Job position is assigned to anyone, because I could not find anyone.');
        } else{
            try {
                foreach ($fmuser as $authorizer){
                    $applicant = User::where('paynumber','=',$request->input('employee'))->first(); //Systems Applications Administrator Finance Manager

                    $details = [
                        'greeting' => 'Good day, ' . $authorizer->first_name,
                        'body' => $applicant->first_name . ' ' . $applicant->last_name . ' has submitted a fuel request which needs your approval. ',
                        'body1'=> $frequest->request_type,
                        'body2' => $frequest->quantity.' of '.$frequest->ftype,
                        'body3' => 'You can approve this request by clicking Approve : ',
                        'approveURL' => 'http://192.168.1.242:8080/whelsonfuel/frequests/emailapprove/'.$authorizer->name.'/'.$frequest->id,
                        'previewURL' => 'http://192.168.1.242:8080/whelsonfuel/frequests/preview/'.$frequest->id,
                        'rejectURL' => 'http://192.168.1.242:8080/whelsonfuel/frequests/emailreject/'.$authorizer->name.'/'.$frequest->id,
                        'body4'=> 'To review the request please login, but you can reject this request straightaway:',
                        'id' => $frequest->id
                    ];

                    Mail::to($authorizer->email)->send(new NewFuelRequestApproval($details));
                }

            } catch (\Exception $exception){
                echo 'Error - '.$exception;
            }
            //$fmuser->notify(new NewFuelRequestApproval($details));
        }

        return redirect()->back()->with('success', 'Your '.$request->input('ftype').' request has been processed.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Frequest  $frequest
     * @return \Illuminate\Http\Response
     */
    public function show(Frequest $frequest)
    {
        $employee = \App\Models\User::where('paynumber', $frequest->employee)->firstOrFail();

        return view('fuelrequests.frequest-info', compact('frequest', 'employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Frequest  $frequest
     * @return \Illuminate\Http\Response
     */
    public function edit(Frequest $frequest)
    {
        $balance = Allocation::all()
            ->where('paynumber','=',Auth::user()->paynumber)
            ->where('deleted_at','=',null)
            ->sortByDesc('id')
            ->first();

        $fsetting = Fsetting::findOrFail(1);

        /*if (strpos($balance->allocation, date('FY'))) {
            echo "Found!";
        } else {
            echo "Hapana Hapana!!!";
        }*/

        $users = User::all();

        $yuser = \App\Models\User::all()->where('paynumber', $frequest->employee )->first();

        return view('fuelrequests.edit-request', compact('frequest','users','balance', 'fsetting', 'yuser'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Frequest  $frequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Frequest $frequest)
    {
        $fsetting = Fsetting::findOrFail(1);

        if ($request->input('request_type') == 'Cash Sale'){
            if ($request->input('ftype') == 'Petrol'){
                if ($fsetting->petrol_available == 0){
                    return redirect()->back()->with('error','Sorry, our petrol reserves are currently low at the moment. We will not be doing any cash sales today.');
                }
            } elseif ($request->input('ftype') == 'Diesel'){
                if ($fsetting->diesel_available == 0){
                    return redirect()->back()->with('error','Sorry, our diesel reserves are currently low at the moment. We will not be doing any cash sales today.');
                }
            }
        } elseif ($request->input('request_type') == 'Allocation'){
            $yuza = User::where('paynumber','=',$request->input('employee'))->first();

            if ($yuza->allocation != 'Allocation'){
                return redirect()->back()->with('error','This employee is currently not on fuel allocation');
            }
        }

        $validator = Validator::make($request->all(),
            [
                'request_type'                  => 'required',
                'quantity'                  => 'required',
                'ftype'                  => 'required',
            ],
            [
                'request_type.required'         => 'Please select the type of fuel allocation this is',
                'employee.required'       => 'Who is this fuel request meant for?',
                'quantity.required'       => 'Is this request for a topup or for a specific quantity?',
                'ftype.required'       => 'What is the type of fuel requested for?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $processorDept = Auth::user()->department;

        if ($processorDept == 'Diesel'){
            $frequest->employee = $request->input('employee');
        }

        $frequest->request_type = $request->input('request_type');
        $frequest->quantity = $request->input('quantity');
        $frequest->ftype = $request->input('ftype');

        $frequest->save();

        return redirect()->back()->with('success', 'Your '.$request->input('ftype').' request has been modified successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Frequest  $frequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Frequest $frequest)
    {
        if ($frequest->status == 0){
            $frequest->delete();

            return redirect('frequests')->with('success', 'Request deleted successfully.');
        } else {
            return redirect('frequests')->with('error', 'This request has been approved and therefore cannot be deleted.');
        }
    }

    public function manageFuelRequests(){
        $frequests = Frequest::where('status',0)
            ->get();
        return view('fuelrequests.manage-requests', compact('frequests'));
    }

    public function preview($id){
        $frequest = Frequest::findOrFail($id);
        $user = User::all()
            ->where('paynumber','=',$frequest->employee)
            ->first();

        $previousTrans = Transaction::all()
            ->where('employee','=',$frequest->employee)
            ->sortByDesc('created_at')
            ->take(10);

        $previousCash = CashSale::all()
            ->where('employee','=',$frequest->employee)
            ->sortByDesc('created_at')
            ->take(10);

        return view('fuelrequests.preview-request', compact('frequest', 'user', 'previousTrans', 'previousCash'));
    }


    public function approve($frequest_id) {
        $current_date_time = Carbon::now()->toDateTimeString();

        $frequest = Frequest::findOrFail($frequest_id);
        $frequest->approved_by = Auth::user()->name;
        $frequest->approved_when = $current_date_time;
        $frequest->status = '1';
        $frequest->save();

        return redirect()->route('manage.requests')->with('success','Request approved successfully');
    }

    public function mailApprove($name, $frequest_id) {
        $current_date_time = Carbon::now()->toDateTimeString();

        $frequest = Frequest::findOrFail($frequest_id);
        if ($frequest->status == '0'){
            $frequest->approved_by = $name;
            $frequest->approved_when = $current_date_time;
            $frequest->status = '1';
            $frequest->save();
        } else{
            return redirect('/blade-response')->with('error','This request has been processed already by '.$frequest->approved_by);
        }

        return redirect('/blade-response')->with('success','Request approved successfully');
    }

    public function reject($frequest_id) {
        $current_date_time = Carbon::now()->toDateTimeString();
        $frequest = Frequest::findOrFail($frequest_id);
        $frequest->approved_by = Auth::user()->name;
        $frequest->approved_when = $current_date_time;
        $frequest->status = '2';
        $frequest->save();

        return redirect()->route('manage.requests')->with('success','Request has been rejected successfully.');
    }

    public function mailReject($name, $frequest_id) {
        $current_date_time = Carbon::now()->toDateTimeString();
        $frequest = Frequest::findOrFail($frequest_id);
        if ($frequest->status == '0'){
            $frequest->approved_by = $name;
            $frequest->approved_when = $current_date_time;
            $frequest->status = '2';
            $frequest->save();
        } else{
            return redirect('/blade-response')->with('error','This request has been processed already by '.$frequest->approved_by);
        }

        return redirect('/blade-response')->with('success','Request has been rejected successfully.');
    }

    public function getApprovedRequests(){
        $frequests = Frequest::where('status',1)
            ->whereDate('created_at', Carbon::today())
            ->get();
        return view('fuelrequests.approved-requests', compact('frequests'));
    }

    public function getPendingRequests(){
        $frequests = Frequest::where('status',0)
            ->whereDate('created_at', Carbon::today())
            ->get();
        return view('fuelrequests.pending-requests', compact('frequests'));
    }

    public function notifyFm($count){

        $fmuser = User::where('position','=','Finance Manager')->first(); //Systems Applications Administrator Finance Manager
        if ($fmuser == null){
           return redirect()->back()->with('error', 'Please check if the Finance Manager Job position is assigned to anyone, because I could not find anyone.');
        } else{
            $fmuser->notify(new AlertFinanceManager($count));
        }

        return redirect('/pending-requests')->with('success', 'Your reminder has been sent to the Finance Manager.');
    }

    public function notifyFman($count){

        $fmuser = User::where('position','=','Fuel Manager')->first(); //Systems Applications Administrator Finance Manager
        if ($fmuser == null){
           return redirect()->back()->with('error', 'Please check if the Fuel Manager Job position is assigned to anyone, because I could not find anyone.');
        } else{
            $fmuser->notify(new AlertFuelManager($count));
        }

        return redirect('/pending-requests')->with('success', 'Your reminder has been sent to the Fuel Manager.');
    }

    public function notifyFd($count){

        $fmuser = User::where('position','=','Finance Director')->first(); //Systems Applications Administrator Finance Manager
        if ($fmuser == null){
           return redirect()->back()->with('error', 'Please check if the Finance Director Job position is assigned to anyone, because I could not find anyone.');
        } else{
            $fmuser->notify(new AlertFinanceDirector($count));
        }

        return redirect('/pending-requests')->with('success', 'Your reminder has been sent to the Finance Director.');
    }

    public function notifyTd($count){

        $fmuser = User::where('position','=','Technical Director')->first(); //Systems Applications Administrator Finance Manager
        if ($fmuser == null){
           return redirect()->back()->with('error', 'Please check if the Technical Director Job position is assigned to anyone, because I could not find anyone.');
        } else{
            $fmuser->notify(new AlertTechnicalDirector($count));
        }

        return redirect('/pending-requests')->with('success', 'Your reminder has been sent to the Technical Director.');
    }

    public function notifyMd($count){

        $fmuser = User::where('position','=','Managing Director')->first(); //Systems Applications Administrator Finance Manager
        if ($fmuser == null){
           return redirect()->back()->with('error', 'Please check if the Managing Director Job position is assigned to anyone, because I could not find anyone.');
        } else{
            $fmuser->notify(new AlertManagingDirector($count));
        }

        return redirect('/pending-requests')->with('success', 'Your reminder has been sent to the Managing Director.');
    }

    public function myRequests(){
        $frequests = Frequest::where('employee','=',Auth::user()->paynumber)->get();
        return view('fuelrequests.myfuelrequests', compact('frequests'));
    }

    public function currentRequests(){
        $frequests = Frequest::whereMonth('created_at', date('m'))
                            ->whereYear('created_at', date('Y'))
                            ->get();
        return view('fuelrequests.current-requests', compact('frequests'));
    }
}
