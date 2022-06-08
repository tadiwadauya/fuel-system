<?php

namespace App\Http\Controllers;

use App\Mail\fuelrequestapproval;
use App\Models\Allocation;
use App\Models\NonAllocation;
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
use DateInterval;
use DatePeriod;
use DateTime;
use Qlick\LaravelFullcalendar\Facades\Calendar;

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
    public function create()
    {

        if (auth()->user()->allocation == 'Allocation') {
            $balance = Allocation::all()

                ->where('paynumber', '=', Auth::user()->paynumber)
                ->where('deleted_at', '=', null)
                ->sortByDesc('id')
                ->first();
            // dd($balance);

            // if (Auth::user()-> department != 'Diesel') {
            //     if ($balance == null) {
            //         $balance = 0;
            //     }
            // } else {
            //     $balance = 0;
            // }


            $currentMonth = Carbon::now()->month('m');

            // $currentMonth = "0".Carbon::now()->month;
            // dd($currentMonth);
            if (Auth::user()->department == 'Diesel') {
                if (Auth::user()->allocation == 'Allocation') {
                    $currentCashsales = DB::table('cash_sales')
                        ->select(DB::raw('sum(quantity) as currentfuel'))
                        ->where('employee', '=', Auth::user()->paynumber)
                        ->where('deleted_at', '=', null)
                        ->whereMonth('created_at', date('m'))
                        ->whereYear('created_at', date('Y'))
                        ->first();
                    // dd($currentCashsales);

                    $applicableCash = (($balance->alloc_size) * 0.5) - $currentCashsales->currentfuel;
                } else {
                    $applicableCash = 0;
                }
            } elseif (Auth::user()->allocation == 'Allocation') {
                $currentCashsales = DB::table('cash_sales')
                    ->select(DB::raw('sum(quantity) as currentfuel'))
                    ->where('employee', '=', Auth::user()->paynumber)
                    ->where('deleted_at', '=', null)
                    ->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'))
                    ->first();

                $applicableCash = (($balance->alloc_size) * 0.5) - $currentCashsales->currentfuel;
            } else {
                $applicableCash = 0;
            }
        } else {
            $balance = NonAllocation::all()

                ->where('paynumber', '=', Auth::user()->paynumber)
                ->where('deleted_at', '=', null)
                ->sortByDesc('id')
                ->first();
            // dd($balance->balance);

            if (Auth::user()->department == 'Diesel') {
                $applicableCash = 0;
            } elseif (Auth::user()->allocation == 'Allocation') {
                $currentMonth = date('m');
                $currentCashsales = DB::table('cash_sales')
                    ->select(DB::raw('sum(quantity) as currentfuel'))
                    ->where('employee', '=', Auth::user()->paynumber)
                    ->where('deleted_at', '=', null)
                    ->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'))
                    ->first();

                $applicableCash = $balance->alloc_size - $currentCashsales->currentfuel;
            } else {
                $applicableCash = 0;
            }
        }

        // dd($applicableCash);

        $fsetting = Fsetting::findOrFail(1);

        /*if (strpos($balance->allocation, date('FY'))) {
            echo "Found!";
        } else {
            echo "Hapana Hapana!!!";
        }*/

        //dd($balance);
        $users = User::all();
        return view('fuelrequests.create-request', compact('users', 'balance', 'fsetting', 'applicableCash'));
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
        $yuza = User::where('paynumber', '=', $request->input('employee'))->first();
        $user = Auth::user();

        if ($request->input('request_type') == 'Cash Sale') {
            if (auth()->user()->allocation == 'Allocation' or $yuza->allocation == 'Allocation') {
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
            } elseif (auth()->user()->allocation == 'Non-allocation' or $yuza->allocation == 'Non-allocation') {
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
            } elseif ($balance->balance == 0) {
                return redirect()->back()->with('error', 'Sorry, this is beyond your prescribed limit for this month. We\'re just going to ignore that request.');
            } else {
                if ($balance->balance <= 0) {
                    return redirect()->back()->with('error', 'Sorry, this is beyond your prescribed limit for this month. We\'re just going to ignore that request.');
                }
            }
        }

        $validator = Validator::make(
            $request->all(),
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

        $frequest = new Frequest();
        $frequest->request_type = $request->input('request_type');
        $frequest->employee = $request->input('employee');
        $frequest->quantity = $request->input('quantity');
        $frequest->ftype = $request->input('ftype');
        $frequest->status = 0;

        if ($frequest->request_type == "Cash Sale") {

            if (is_numeric($request->input('amount'))) {
                if ($frequest->ftype == 'Petrol') {
                    $frequest->amount = $fsetting->petrol_price *  $frequest->quantity;
                } else {
                    $frequest->amount = $fsetting->diesel_price *  $frequest->quantity;
                }
            } else {
                $frequest->amount = $request->input('amount');
            }

            if ($user->allocation == "Allocation") {
                $currentCashsales = DB::table('cash_sales')
                    ->select(DB::raw('sum(quantity) as currentfuel'))
                    ->where('employee', '=', $user->paynumber)
                    ->where('deleted_at', '=', null)
                    ->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'))
                    ->first();

                $applicableFuel = (($user->alloc_size) * 0.5) - $currentCashsales->currentfuel;

                if ($user->user_role == 'Diesel') {
                    $empPaynumber = $frequest->employee;

                    $selectedUser = User::where('paynumber', '=', $empPaynumber)
                        ->first();

                    if ($selectedUser->allocation == "Allocation") {
                        $cash = DB::table('cash_sales')
                            ->select(DB::raw('sum(quantity) as currentfuel'))
                            ->where('employee', '=', $selectedUser->employee)
                            ->where('deleted_at', '=', null)
                            ->whereMonth('created_at', date('m'))
                            ->whereYear('created_at', date('Y'))
                            // ->whereRaw('created_at', '=', $currentMonth)
                            ->first();

                        $applicable = (($selectedUser->alloc_size) * 0.5) - $cash->currentfuel;

                        if ($frequest->quantity <= $applicable) {
                            $frequest->save();
                        } else {
                            return redirect()->back()->with('error', 'Sorry, this is beyond selected user\'s prescribed limit for this month. We\'re just going to ignore that request.');
                        }
                    } else {
                        $balance = NonAllocation::all()
                            ->where('paynumber', '=', $selectedUser->employee)
                            ->where('deleted_at', '=', null)
                            ->sortByDesc('id')
                            ->first();

                        $applicableCash = $balance->balance;

                        if ($frequest->quantity <= $applicableCash) {
                            $frequest->save();
                        } else {
                            return redirect()->back()->with('error', 'Sorry, this is beyond selected user\'s prescribed limit for this month. We\'re just going to ignore that request.');
                        }
                    }
                } else {
                    if ($frequest->quantity <= $applicableFuel) {
                        $frequest->save();
                    } else {
                        return redirect()->back()->with('error', 'Sorry, this is beyond your prescribed limit for this month. We\'re just going to ignore that request.');
                    }
                }
            } else {
                if ($user->user_role == 'Diesel') {
                    $empPaynumber = $frequest->employee;

                    $selectedUser = User::where('paynumber', '=', $empPaynumber)
                        ->first();

                    if ($selectedUser->allocation == "Allocation") {
                        $balance = NonAllocation::all()
                            ->where('paynumber', '=', $selectedUser->paynumber)
                            ->where('deleted_at', '=', null)
                            ->sortByDesc('id')
                            ->first();

                        $applicableCash = $balance->balance;

                        if ($frequest->quantity <= $applicableCash) {
                            $frequest->save();
                        } else {
                            return redirect()->back()->with('error', 'Sorry, this is beyond selected user\'s prescribed limit for this month. We\'re just going to ignore that request.');
                        }
                    }
                } else {
                    $balance = NonAllocation::all()
                        ->where('paynumber', '=', Auth::user()->paynumber)
                        ->where('deleted_at', '=', null)
                        ->sortByDesc('id')
                        ->first();

                    $applicableCash = $balance->balance;

                    if ($frequest->quantity <= $applicableCash) {
                        $frequest->save();
                    } else {
                        return redirect()->back()->with('error', 'Sorry, this is beyond your prescribed limit for this month. We\'re just going to ignore that request.');
                    }
                }
            }
        }

        if ($frequest->save()) {
            $fmuser = DB::table('users')
                ->where('position', '=', 'Finance Director')
                ->orWhere('position', '=', 'Technical Director')
                ->get(); //Systems Applications Administrator Finance Manager
            /*$fmuser = User::where('position','=','Systems Applications Administrator')
                ->orWhere('position','=','Systems Administrator')
                ->get(); //Systems Applications Administrator Finance Manager*/

            if ($fmuser == null) {
                return redirect()->back()->with('error', 'Please check if the Finance Director and Technical Director Job position is assigned to anyone, because I could not find anyone.');
            } else {
                try {
                    foreach ($fmuser as $authorizer) {
                        $applicant = User::where('paynumber', '=', $request->input('employee'))->first(); //Systems Applications Administrator Finance Manager

                        $details = [
                            'greeting' => 'Good day, ' . $authorizer->first_name,
                            'body' => $applicant->first_name . ' ' . $applicant->last_name . ' has submitted a fuel request which needs your approval. ',
                            'body1' => $frequest->request_type,
                            'body2' => $frequest->quantity . ' of ' . $frequest->ftype,
                            'body3' => $frequest->amount,
                            'body4' => 'You can approve this request by clicking Approve : ',
                            'approveURL' => 'http://127.0.0.1:8000/whelsonfuel/frequests/emailapprove/' . $authorizer->name . '/' . $frequest->id,
                            'previewURL' => 'http://127.0.0.1:8000/whelsonfuel/frequests/preview/' . $frequest->id,
                            'rejectURL' => 'http://127.0.0.1:8000/whelsonfuel/frequests/emailreject/' . $authorizer->name . '/' . $frequest->id,
                            'body5' => 'To review the request please login, but you can reject this request straightaway:',
                            'id' => $frequest->id
                        ];

                        Mail::to($authorizer->email)->send(new NewFuelRequestApproval($details));
                    }
                } catch (\Exception $exception) {
                    echo 'Error - ' . $exception;
                }
                //$fmuser->notify(new NewFuelRequestApproval($details));
            }

            return redirect()->back()->with('success', 'Your ' . $request->input('ftype') . ' request has been processed.');
        } else {
            return redirect()->back()->with('error', 'Sorry, Failed.');
        }
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
            ->where('paynumber', '=', Auth::user()->paynumber)
            ->where('deleted_at', '=', null)
            ->sortByDesc('id')
            ->first();

        $fsetting = Fsetting::findOrFail(1);

        /*if (strpos($balance->allocation, date('FY'))) {
            echo "Found!";
        } else {
            echo "Hapana Hapana!!!";
        }*/

        $users = User::all();

        $yuser = \App\Models\User::all()->where('paynumber', $frequest->employee)->first();

        return view('fuelrequests.edit-request', compact('frequest', 'users', 'balance', 'fsetting', 'yuser'));
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

        if ($request->input('request_type') == 'Cash Sale') {
            if ($request->input('ftype') == 'Petrol') {
                if ($fsetting->petrol_available == 0) {
                    return redirect()->back()->with('error', 'Sorry, our petrol reserves are currently low at the moment. We will not be doing any cash sales today.');
                }
            } elseif ($request->input('ftype') == 'Diesel') {
                if ($fsetting->diesel_available == 0) {
                    return redirect()->back()->with('error', 'Sorry, our diesel reserves are currently low at the moment. We will not be doing any cash sales today.');
                }
            }
        } elseif ($request->input('request_type') == 'Allocation') {
            $yuza = User::where('paynumber', '=', $request->input('employee'))->first();

            if ($yuza->allocation != 'Allocation') {
                return redirect()->back()->with('error', 'This employee is currently not on fuel allocation');
            }
        }

        $validator = Validator::make(
            $request->all(),
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

        if ($processorDept == 'Diesel') {
            $frequest->employee = $request->input('employee');
        }

        $frequest->request_type = $request->input('request_type');
        $frequest->quantity = $request->input('quantity');
        $frequest->ftype = $request->input('ftype');

        $frequest->save();

        return redirect()->back()->with('success', 'Your ' . $request->input('ftype') . ' request has been modified successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Frequest  $frequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Frequest $frequest)
    {
        if ($frequest->status == 0) {
            $frequest->delete();

            return redirect()->back()->with('success', 'Request deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'This request has been approved and therefore cannot be deleted.');
        }
    }

    public function manageFuelRequests()
    {
        $frequests = Frequest::where('status', 0)
            ->get();
        return view('fuelrequests.manage-requests', compact('frequests'));
    }

    public function preview($id)
    {
        $frequest = Frequest::findOrFail($id);
        $user = User::all()
            ->where('paynumber', '=', $frequest->employee)
            ->first();

        $previousTrans = Transaction::all()
            ->where('employee', '=', $frequest->employee)
            ->sortByDesc('created_at')
            ->take(10);

        $previousCash = CashSale::all()
            ->where('employee', '=', $frequest->employee)
            ->sortByDesc('created_at')
            ->take(10);

        return view('fuelrequests.preview-request', compact('frequest', 'user', 'previousTrans', 'previousCash'));
    }


    public function approve($frequest_id)
    {
        $current_date_time = Carbon::now()->toDateTimeString();

        $frequest = Frequest::findOrFail($frequest_id);
        $frequest->approved_by = Auth::user()->name;
        $frequest->approved_when = $current_date_time;
        $frequest->status = '1';
        $frequest->save();

        if ($frequest->save()) {
            try {
                $user_approve = User::where('paynumber', $frequest->employee)->first();

                $details = [
                    'greeting' => 'Good day, ' . $user_approve->first_name,
                    'body' => $user_approve->first_name . ' ' . $user_approve->last_name . ' Your fuel Request has been approved. ',
                    'ftype' => $frequest->request_type,
                    'quantity' => $frequest->quantity . ' of ' . $frequest->ftype,
                    'amount' => $frequest->amount,
                    'link' => 'You can print this email as your Quotation:',
                    'downloadUrl' => 'https://fuel.whelson.net.za/frequests/' . $frequest->id
                ];

                if ($frequest->request_type == 'Cash Sale') {
                    Mail::to($user_approve->email)->cc(["cashbooks@whelson.co.zw"])->send(new fuelrequestapproval($details));

                    return redirect()->route('manage.requests')->with('success', 'Request approved successfully');
                } else {
                    Mail::to($user_approve->email)->send(new fuelrequestapproval($details));

                    return redirect()->route('manage.requests')->with('success', 'Request approved successfully');
                }
            } catch (\Exception $e) {
                echo "Error" . $e;
            }
        }

        //     $fmuser = DB::table('users')
        //     ->where('paynumber',$frequest->paynumber)
        //     ->get(); //Systems Applications Administrator Finance Manager
        // /*$fmuser = User::where('position','=','Systems Applications Administrator')
        //     ->orWhere('position','=','Systems Administrator')
        //     ->get(); //Systems Applications Administrator Finance Manager*/

        // if ($fmuser == null){
        //     return redirect()->back()->with('error', 'Please check if the Finance Director and Technical Director Job position is assigned to anyone, because I could not find anyone.');
        // } else{
        //     try {
        //         foreach ($fmuser as $authorizer){

        //             $applicant = User::where('paynumber','=',$authorizer->paynumber)->first();//Systems Applications Administrator Finance Manager

        //             $details = [
        //                 'greeting' => 'Good day, ' . $authorizer->first_name,
        //                 'body' => $applicant->first_name . ' ' . $applicant->last_name . ' has submitted a fuel request which needs your approval. ',
        //                 'body1'=> $frequest->request_type,
        //                 'body2' => $frequest->quantity.' of '.$frequest->ftype,
        //                 'body3' => $frequest->amount.' of '.$frequest->amount,
        //                 'body4'=> 'You can now go download your quatation:',
        //                 'id' => $frequest->id
        //             ];

        //             Mail::to($authorizer->email)->cc(["dauya1994@gmail.com"])->send(new NewFuelRequestApproval($details));
        //         }

        //     } catch (\Exception $exception){
        //         echo 'Error - '.$exception;
        //     }
        // }

    }

    public function mailApprove($name, $frequest_id)
    {
        $current_date_time = Carbon::now()->toDateTimeString();

        $frequest = Frequest::findOrFail($frequest_id);
        if ($frequest->status == '0') {
            $frequest->approved_by = $name;
            $frequest->approved_when = $current_date_time;
            $frequest->status = '1';
            $frequest->save();
        } else {
            return redirect('/blade-response')->with('error', 'This request has been processed already by ' . $frequest->approved_by);
        }
        if ($frequest->save()) {
            try {
                $user_approve = User::where('paynumber', $frequest->employee)->first();

                $details = [
                    'greeting' => 'Good day, ' . $user_approve->first_name,
                    'body' => $user_approve->first_name . ' ' . $user_approve->last_name . ' Your fuel Request has been approved. ',
                    'ftype' => $frequest->request_type,
                    'quantity' => $frequest->quantity . ' of ' . $frequest->ftype,
                    'amount' => $frequest->amount,
                    'link' => 'You can print this email as your Quotation:',
                    'downloadUrl' => 'https://fuel.whelson.net.za/frequests/' . $frequest->id
                ];

                if ($frequest->request_type == 'Cash Sale') {
                    Mail::to($user_approve->email)->cc(["masayakudakwashe@gmail.com"])->send(new fuelrequestapproval($details));

                    return redirect()->route('manage.requests')->with('success', 'Request approved successfully');
                } else {
                    Mail::to($user_approve->email)->send(new fuelrequestapproval($details));

                    return redirect()->route('manage.requests')->with('success', 'Request approved successfully');
                }
            } catch (\Exception $e) {
                echo "Error" . $e;
            }
        }
    }

    public function reject($frequest_id)
    {
        $current_date_time = Carbon::now()->toDateTimeString();
        $frequest = Frequest::findOrFail($frequest_id);
        $frequest->approved_by = Auth::user()->name;
        $frequest->approved_when = $current_date_time;
        $frequest->status = '2';
        $frequest->save();

        return redirect()->route('manage.requests')->with('success', 'Request has been rejected successfully.');
    }

    public function mailReject($name, $frequest_id)
    {
        $current_date_time = Carbon::now()->toDateTimeString();
        $frequest = Frequest::findOrFail($frequest_id);
        if ($frequest->status == '0') {
            $frequest->approved_by = $name;
            $frequest->approved_when = $current_date_time;
            $frequest->status = '2';
            $frequest->save();
        } else {
            return redirect('/blade-response')->with('error', 'This request has been processed already by ' . $frequest->approved_by);
        }

        return redirect('/blade-response')->with('success', 'Request has been rejected successfully.');
    }

    public function getApprovedRequests()
    {
        $frequests = Frequest::where('status', 1)
            ->whereDate('created_at', Carbon::today())
            ->get();
        return view('fuelrequests.approved-requests', compact('frequests'));
    }

    public function getPendingRequests()
    {
        $frequests = Frequest::where('status', 0)
            ->whereDate('created_at', Carbon::today())
            ->get();
        return view('fuelrequests.pending-requests', compact('frequests'));
    }

    public function notifyFm($count)
    {

        $fmuser = User::where('position', '=', 'Systems Applications Administrator')->first(); //Systems Applications Administrator Finance Manager
        if ($fmuser == null) {
            return redirect()->back()->with('error', 'Please check if the Finance Manager Job position is assigned to anyone, because I could not find anyone.');
        } else {
            $fmuser->notify(new AlertFinanceManager($count));
        }

        return redirect('/pending-requests')->with('success', 'Your reminder has been sent to the Finance Manager.');
    }

    public function notifyFman($count)
    {

        $fmuser = User::where('position', '=', 'Fuel Manager')->first(); //Systems Applications Administrator Finance Manager
        if ($fmuser == null) {
            return redirect()->back()->with('error', 'Please check if the Fuel Manager Job position is assigned to anyone, because I could not find anyone.');
        } else {
            $fmuser->notify(new AlertFuelManager($count));
        }

        return redirect('/pending-requests')->with('success', 'Your reminder has been sent to the Fuel Manager.');
    }

    public function notifyFd($count)
    {

        $fmuser = User::where('position', '=', 'Finance Director')->first(); //Systems Applications Administrator Finance Manager
        if ($fmuser == null) {
            return redirect()->back()->with('error', 'Please check if the Finance Director Job position is assigned to anyone, because I could not find anyone.');
        } else {
            $fmuser->notify(new AlertFinanceDirector($count));
        }

        return redirect('/pending-requests')->with('success', 'Your reminder has been sent to the Finance Director.');
    }

    public function notifyTd($count)
    {

        $fmuser = User::where('position', '=', 'Technical Director')->first(); //Systems Applications Administrator Finance Manager
        if ($fmuser == null) {
            return redirect()->back()->with('error', 'Please check if the Technical Director Job position is assigned to anyone, because I could not find anyone.');
        } else {
            $fmuser->notify(new AlertTechnicalDirector($count));
        }

        return redirect('/pending-requests')->with('success', 'Your reminder has been sent to the Technical Director.');
    }

    public function notifyMd($count)
    {

        $fmuser = User::where('position', '=', 'Managing Director')->first(); //Systems Applications Administrator Finance Manager
        if ($fmuser == null) {
            return redirect()->back()->with('error', 'Please check if the Managing Director Job position is assigned to anyone, because I could not find anyone.');
        } else {
            $fmuser->notify(new AlertManagingDirector($count));
        }

        return redirect('/pending-requests')->with('success', 'Your reminder has been sent to the Managing Director.');
    }

    public function myRequests()
    {
        $frequests = Frequest::where('employee', '=', Auth::user()->paynumber)->get();
        return view('fuelrequests.myfuelrequests', compact('frequests'));
    }

    public function currentRequests()
    {
        $frequests = Frequest::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->get();
        return view('fuelrequests.current-requests', compact('frequests'));
    }

    public function generatePdf($id)
    {

        $frequest = Frequest::findorFail($id);

        try {
            $mpdf = new \Mpdf\Mpdf([
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 48, //48
                'margin_bottom' => 25,
                'margin_header' => 10, //10
                'margin_footer' => 10,
            ]);

            $html = \View::make('fuelrequests.form')->with('frequest', $frequest);
            $html = $html->render();

            $mpdf->SetProtection(array('print'));
            $mpdf->SetTitle("Whelson Fuel Qoutation");
            $mpdf->SetAuthor("Tadiwanashe Dauya");
            $mpdf->showWatermarkImage = true;
            $mpdf->watermark_font = 'DejaVuSansCondensed';
            $mpdf->watermarkTextAlpha = 0.1;
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML($html);
            $mpdf->Output("WhelsonForm" . $frequest->paynumber . '.pdf', 'I');
        } catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
            // Process the exception, log, print etc.
            echo $e->getMessage();
        }
    }

    public function test()
    {
        return view('fuelrequests.test');
    }
}
