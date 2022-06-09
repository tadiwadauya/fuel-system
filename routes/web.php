<?php

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| Middleware options can be located in `app/Http/Kernel.php`
|
*/

// Homepage Route
Route::group(['middleware' => ['web', 'checkblocked']], function () {
    Route::get('/', 'WelcomeController@welcome')->name('welcome');
    Route::get('/terms', 'TermsController@terms')->name('terms');
    Route::get('/getTitles/{department}', 'JobTitleController@getTitles')->name('jobtitles.fetch');
    Route::get('/getAllocations/{paynumber}', 'AllocationController@getAllocations')->name('allocations.fetch');
    Route::get('/getAllocationss/{paynumber}', 'NonAllocationController@getAllocationss')->name('allocations.fetch');
    Route::get('/getContainers/{id}', 'InvoiceController@getContainers')->name('containers.fetch');
    Route::get('/getClientContainer/{id}', 'ContainerTransactionController@getContainer');
    Route::get('/markNotifsAsRead', function () {
        auth()->user()->unreadNotifications->markAsRead();
    });
    // Route to show user avatar
    Route::get('images/profile/{id}/avatar/{image}', [
        'uses' => 'ProfilesController@userProfileAvatar',
    ]);
});

// Authentication Routes
Auth::routes();

// Public Routes
Route::group(['middleware' => ['web', 'activity', 'checkblocked']], function () {

    // Activation Routes
    Route::get('/activate', ['as' => 'activate', 'uses' => 'Auth\ActivateController@initial']);

    Route::get('/activate/{token}', ['as' => 'authenticated.activate', 'uses' => 'Auth\ActivateController@activate']);
    Route::get('/activation', ['as' => 'authenticated.activation-resend', 'uses' => 'Auth\ActivateController@resend']);
    Route::get('/exceeded', ['as' => 'exceeded', 'uses' => 'Auth\ActivateController@exceeded']);

    // Socialite Register Routes
    Route::get('/social/redirect/{provider}', ['as' => 'social.redirect', 'uses' => 'Auth\SocialController@getSocialRedirect']);
    Route::get('/social/handle/{provider}', ['as' => 'social.handle', 'uses' => 'Auth\SocialController@getSocialHandle']);

    // Route to for user to reactivate their user deleted account.
    Route::get('/re-activate/{token}', ['as' => 'user.reactivate', 'uses' => 'RestoreUserController@userReActivate']);
    Route::put('/updateFirstPwd/{id}', 'UsersManagementController@updateUserPassword')->name('forcePasswordChange');
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'checkpass', 'activated', 'activity', 'checkblocked']], function () {

    // Activation Routes
    Route::get('/activation-required', ['uses' => 'Auth\ActivateController@activationRequired'])->name('activation-required');
    Route::get('/logout', ['uses' => 'Auth\LoginController@logout'])->name('logout');
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'activity', 'twostep', 'checkblocked']], function () {

    //  Homepage Route - Redirect based on user role is in controller.
    Route::get('/home', ['as' => 'public.home',   'uses' => 'UserController@index']);

    // Show users profile - viewable by other users.
    Route::get('profile/{username}', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@show',
    ]);
});

// Registered, activated, and is current user routes.
Route::group(['middleware' => ['auth', 'checkpass', 'activated', 'currentUser', 'activity', 'twostep', 'checkblocked']], function () {

    // User Profile and Account Routes
    Route::resource(
        'profile',
        'ProfilesController',
        [
            'only' => [
                'show',
                'edit',
                'update',
                'create',
            ],
        ]
    );
    Route::put('profile/{username}/updateUserAccount', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@updateUserAccount',
    ]);
    Route::put('profile/{username}/updateUserPassword', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@updateUserPassword',
    ]);
    Route::delete('profile/{username}/deleteUserAccount', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@deleteUserAccount',
    ]);

    // Route to upload user avatar.
    Route::post('avatar/upload', ['as' => 'avatar.upload', 'uses' => 'ProfilesController@upload']);
});

// Registered, activated, and is admin routes.
Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'activity', 'twostep', 'checkblocked']], function () {
    Route::resource('/users/deleted', 'SoftDeletesController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware(['manadmin']);

    Route::resource('users', 'UsersManagementController', [
        'names' => [
            'index'   => 'users',
            'destroy' => 'user.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::post('search-users', 'UsersManagementController@search')->name('search-users');

    Route::resource('themes', 'ThemesManagementController', [
        'names' => [
            'index'   => 'themes',
            'destroy' => 'themes.destroy',
        ],
    ]);

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('routes', 'AdminDetailsController@listRoutes');
    Route::get('active-users', 'AdminDetailsController@activeUsers');
});

Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'activity', 'twostep', 'checkblocked']], function () {

    Route::resource('departments', 'DepartmentController', [
        'names' => [
            'index'   => 'departments',
            'destroy' => 'department.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
});

Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'checkpass',  'activity', 'twostep', 'checkblocked']], function () {

    Route::resource('jobtitles', 'JobTitleController', [
        'names' => [
            'index'   => 'jobtitles',
            'destroy' => 'jobtitle.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
});

Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'activity', 'twostep', 'checkblocked']], function () {
    Route::resource('/allocations/deleted', 'SoftDeleteAllocationController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware(['manadmin']);

    Route::resource('allocations', 'AllocationController', [
        'names' => [
            'index'   => 'allocations',
            'destroy' => 'allocation.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('/myallocations', 'AllocationController@myAllocations');
    Route::get('/bulkallocations', 'AllocationController@bulkCreateAllocations');
    Route::get('/bulkprocess', 'AllocationController@allocationsBatcher');
    Route::get('/executive-allocations', 'AllocationController@execAllocations');
});


Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'activity', 'twostep', 'checkblocked']], function () {
    Route::resource('/non_allocations/deleted', 'SoftDeleteNonAllocationController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware(['manadmin']);

    Route::resource('non_allocations', 'NonAllocationController', [
        'names' => [
            'index'   => 'non_allocations',
            'destroy' => 'non_allocation.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('/mynon_allocations', 'NonAllocationController@myNonAllocations');
    Route::get('/bulknon_allocations', 'NonAllocationController@bulkCreateNonAllocations');
    Route::get('/bulkprocesss', 'NonAllocationController@non_allocationsBatchers');
    Route::get('/executive-non_allocations', 'NonAllocationController@execNonAllocations');
});


Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'activity', 'twostep', 'checkblocked']], function () {
    Route::resource('/transactions/deleted', 'SoftDeleteTransactionController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware(['manadmin']);

    Route::resource('transactions', 'TransactionController', [
        'names' => [
            'index'   => 'transactions',
            'destroy' => 'transaction.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('/transaction-pdf/{id}', 'TransactionController@generatePdf')->name('generate.transpdf');

    Route::get('/mytransactions', 'TransactionController@myTransactions');

    Route::get('/current-trans', 'TransactionController@currentTransactions');
});

Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'activity', 'twostep', 'checkblocked']], function () {
    Route::resource('/cashsales/deleted', 'SoftDeleteSaleController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware(['manadmin']);

    Route::resource('cashsales', 'CashSaleController', [
        'names' => [
            'index'   => 'cashsales',
            'destroy' => 'cashsale.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('/cashsale-pdf/{id}', 'CashSaleController@generatePdf')->name('generate.salepdf');

    Route::get('/mycashsales', 'CashSaleController@myCashSales');

    Route::get('/current-cash', 'CashSaleController@currentCashSales');
});

Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'activity', 'twostep', 'checkblocked']], function () {

    Route::get('/allocations-report', 'ReportsController@allocationsForm')->middleware(['mandieadmin']);
    Route::post('/allocations-report', 'ReportsController@getAllocations')->name('allocations.report')->middleware(['mandieadmin']);

    Route::get('/all-allocations-report', 'ReportsController@allAllocationsForm')->middleware(['mandieadmin']);
    Route::post('/all-allocations-report', 'ReportsController@getAllAllocations')->name('allallocations.report')->middleware(['mandieadmin']);

    Route::get('/allocations-balances', 'ReportsController@allocationsBalancesForm')->middleware(['mandieadmin']);
    Route::post('/allocations-balances', 'ReportsController@getAllocationsBalances')->name('allocations.balance')->middleware(['mandieadmin']);

    Route::get('/cash-sale-report', 'ReportsController@cashSalesForm')->middleware(['mandieadmin']);
    Route::post('/cash-sale-report', 'ReportsController@getCashSales')->name('cashsales.report')->middleware(['mandieadmin']);

    Route::get('/cashsale-pdf/{id}', 'CashSaleController@generatePdf')->name('generate.salepdf');

    Route::get('/invoices-report', 'ReportsController@invoicesForm')->middleware(['manadmin']);
    Route::post('/invoices-report', 'ReportsController@getInvoices')->name('invoices.report')->middleware(['manadmin']);

    Route::get('/containers-report', 'ReportsController@containersForm')->middleware(['manadmin']);
    Route::post('/containers-report', 'ReportsController@getContainers')->name('containers.report')->middleware(['manadmin']);

    Route::get('/container-transaction-report', 'ReportsController@containerTransactionForm')->middleware(['manadmin']);
    Route::post('/container-transaction-report', 'ReportsController@containertransactionPost')->name('containers.transactactor')->middleware(['manadmin']);
    Route::get('/get-user-container-name/{client}', 'ReportsController@getUserContainer');

    Route::get('/stock-issue-report', 'ReportsController@stockIssuesForm')->middleware(['mandieadmin']);
    Route::post('/stock-issue-report', 'ReportsController@getStockIssues')->name('stockissues.report')->middleware(['mandieadmin']);
});

Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'activity', 'twostep', 'checkblocked']], function () {
    Route::resource('/frequests/deleted', 'SoftDeleteFuelRequestController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware(['manadmin']);

    Route::resource('frequests', 'FrequestController', [
        'names' => [
            'index'   => 'frequests',
            'destroy' => 'frequest.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('/manage-requests', 'FrequestController@manageFuelRequests')->name('manage.requests')->middleware(['manadmin']);
    Route::get('/approved-requests', 'FrequestController@getApprovedRequests')->name('approved.requests')->middleware(['mandieadmin']);
    Route::get('/pending-requests', 'FrequestController@getPendingRequests')->name('pending.requests');
    Route::get('/frequests/approve/{id}', ['as' => 'approve.fuelrequest', 'uses' => 'FrequestController@approve'])->middleware(['manadmin']);
    Route::get('/frequests/reject/{id}', ['as' => 'reject.fuelrequest', 'uses' => 'FrequestController@reject'])->middleware(['manadmin']);
    Route::get('/frequests/preview/{id}', ['as' => 'preview.fuelrequest', 'uses' => 'FrequestController@preview'])->middleware(['manadmin']);

    Route::get('/notifyFinanceManager/{id}', 'FrequestController@notifyFm')->middleware(['mandieadmin']);
    Route::get('/notifyFuelManager/{id}', 'FrequestController@notifyFman')->middleware(['mandieadmin']);
    Route::get('/notifyFinanceDirector/{id}', 'FrequestController@notifyFd')->middleware(['mandieadmin']);
    Route::get('/notifyTechnicalDirector/{id}', 'FrequestController@notifyTd')->middleware(['mandieadmin']);
    Route::get('/notifyManagingDirector/{id}', 'FrequestController@notifyMd')->middleware(['mandieadmin']);

    Route::get('/myfuelrequests', 'FrequestController@myRequests');
    Route::get('/currentrequests', 'FrequestController@currentRequests');
    Route::get('/frequest-pdf/{id}', 'FrequestController@generatePdf')->name('generate.pdf');
});

Route::get('/frequests/emailapprove/{name}/{id}', ['as' => 'mailapprove.fuelrequest', 'uses' => 'FrequestController@mailApprove']);
Route::get('/frequests/emailreject/{name}/{id}', ['as' => 'mailreject.fuelrequest', 'uses' => 'FrequestController@mailReject']);
Route::get('/frequests/emailpreview/{name}/{id}', ['as' => 'mailpreview.fuelrequest', 'uses' => 'FrequestController@mailPreview']);

Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'activity', 'twostep', 'checkblocked']], function () {

    Route::get('/fuelsettings', 'FsettingController@fuelSettings')->name('get.fsetting')->middleware(['manadmin']);

    Route::put('/fuelsettings', 'FsettingController@updateMaSettingsEFuel')->name('fsetting.update')->middleware(['manadmin']);
});

Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'manadmin', 'activity', 'twostep', 'checkblocked']], function () {

    Route::resource('/deleted-containers', 'SoftDeleteContainerTransaction', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('container_transactions', 'ContainerTransactionController', [
        'names' => [
            'index'   => 'container_transactions',
            'destroy' => 'container_transaction.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('/transactions-report', 'ContainerTransactionController@getTransactionsReport');

    Route::post('/transaction-report-post', 'ContainerTransactionController@postTransactionReport');
});


/*Route::get('testmail', function () {
    $frequest = \App\Models\Frequest::find(25);

    $fmuser = User::where('position','=','Systems Applications Administrator')->orWhere('position','=','Systems Administrator')->first(); //Systems Applications Administrator Finance Manager

    $applicant = User::where('paynumber','=',713)->first(); //Systems Applications Administrator Finance Manager

    $details = [
        'greeting' => 'Good day, ' . $fmuser->first_name,
        'body' => $applicant->first_name . ' ' . $applicant->last_name . ' (' . $applicant->paynumber . ') has submitted a fuel request which needs your approval. ',
        'body1'=> $frequest->request_type,
        'body2' => $frequest->quantity.' of '.$frequest->ftype,
        'body3' => 'You can approve this request by clicking Approve : ',
        'approveURL' => 'http://localhost:8000/frequests/emailapprove/'.$fmuser->name.'/'.$frequest->id,
        'previewURL' => 'http://localhost:8000/frequests/preview/'.$frequest->id,
        'rejectURL' => 'http://localhost:8000/frequests/emailreject/'.$fmuser->name.'/'.$frequest->id,
        'body4'=> 'To review the request please login, but you can reject this request straightaway:',
        'id' => $frequest->id
    ];

    return new App\Mail\NewFuelRequestApproval($details);
});*/

Route::get('/blade-response', 'WelcomeController@requestResponse');
Route::get('/unauthorized', 'WelcomeController@unauthorized');
Route::get('/changepassword', 'WelcomeController@changepassword');
Route::redirect('/php', '/phpinfo', 301);

Route::group(['prefix' => 'activity', 'middleware' => ['web', 'auth', 'activity']], function () {

    // Dashboards
    Route::get('/', 'LaravelLoggerController@showAccessLog')->name('activity');
    Route::get('/cleared', ['uses' => 'LaravelLoggerController@showClearedActivityLog'])->name('cleared');

    // Drill Downs
    Route::get('/log/{id}', 'LaravelLoggerController@showAccessLogEntry');
    Route::get('/cleared/log/{id}', 'LaravelLoggerController@showClearedAccessLogEntry');

    // Forms
    Route::delete('/clear-activity', ['uses' => 'LaravelLoggerController@clearActivityLog'])->name('clear-activity');
    Route::delete('/destroy-activity', ['uses' => 'LaravelLoggerController@destroyActivityLog'])->name('destroy-activity');
    Route::post('/restore-log', ['uses' => 'LaravelLoggerController@rPTbFPZzFiLwA17kBfsyJF3Fm1kuFsVtSc'])->name('restore-activity');
});

Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'manadmin', 'activity', 'twostep', 'checkblocked']], function () {

    Route::resource('/batches/deleted', 'SoftDeleteFuelBatch', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('batches', 'BatchController', [
        'names' => [
            'index'   => 'batches',
            'destroy' => 'batch.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
});

Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'manadmin', 'activity', 'twostep', 'checkblocked']], function () {

    Route::resource('clients', 'ClientController', [
        'names' => [
            'index'   => 'clients',
            'destroy' => 'client.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
});

Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'manadmin', 'activity', 'twostep', 'checkblocked']], function () {

    Route::resource('/containers/deleted', 'SoftDeleteContainer', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('containers', 'ContainerController', [
        'names' => [
            'index'   => 'containers',
            'destroy' => 'container.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('/active-containers', 'ContainerController@activeContainers');
    Route::get('/empty-containers', 'ContainerController@emptyContainers');
});

Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'manadmin', 'activity', 'twostep', 'checkblocked']], function () {

    Route::resource('/invoices/deleted', 'SoftDeleteInvoice', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('invoices', 'InvoiceController', [
        'names' => [
            'index'   => 'invoices',
            'destroy' => 'invoice.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::get('/invoice-pdf/{id}', 'InvoiceController@generateInvoicePdf')->name('generate.pdfinvoice');
    Route::get('/showform/{id}', 'InvoiceController@showform')->name('trans.summary');
    Route::get('/getVoucherNum/', 'InvoiceController@generateVoucher')->name('generate.voucher');
    Route::get('/mail-invoice/{id}', 'InvoiceController@emailInvoice')->name('mail.invoice');
});

/*Route::get('testmailinv', function () {
    $invoice = Invoice::join('clients','invoices.client' , '=', 'clients.id')
        ->join('containers','invoices.container' , '=', 'containers.conname')
        ->select('invoices.id', 'invoices.trans_code','clients.cli_name','clients.cli_email','clients.cli_email_cc', 'invoices.voucher', 'containers.conname','containers.conrate', 'invoices.quantity', 'invoices.reg_num', 'invoices.driver', 'invoices.done_by', 'invoices.created_at', 'invoices.deleted_at')
        ->where('invoices.id', 1)
        ->firstOrFail();

    $amount = number_format($invoice->conrate*$invoice->quantity,2);
    $details = [
        'greeting' => 'Good day, ' . $invoice->cli_name,
        'id' => $invoice->id,
        'trans_code' => $invoice->trans_code,
        'cli_name' => $invoice->cli_name,
        'cli_email' => $invoice->cli_email,
        'cli_email_cc' => $invoice->cli_email_cc,
        'voucher' => $invoice->voucher,
        'container' => $invoice->container,
        'conname' => $invoice->conname,
        'conrate' => $invoice->conrate,
        'quantity' => $invoice->quantity,
        'amount' => $amount,
        'reg_num' => $invoice->reg_num,
        'driver' => $invoice->driver,
        'done_by' => $invoice->done_by,
        'created_at' => $invoice->created_at
    ];

    return new App\Mail\FuelInvoice($details);
});*/

Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'activity', 'twostep', 'checkblocked']], function () {
    Route::resource('/stockissues/deleted', 'SoftDeleteStockController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ])->middleware(['manadmin']);

    Route::resource('stockissues', 'StockIssueController', [
        'names' => [
            'index'   => 'stockissues',
            'destroy' => 'stockissue.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('/cashsale-pdf/{id}', 'StockIssueController@generatePdf')->name('generate.salepdf');

    Route::get('/mystockissues', 'StockIssueController@myStockIssues');
    Route::get('/current-stock', 'StockIssueController@currentStockIssues');
});

Route::group(['middleware' => ['auth', 'activated', 'checkpass', 'manadmin', 'activity', 'twostep', 'checkblocked']], function () {

    Route::resource('/quotations/deleted', 'SoftDeleteQuotation', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('quotations', 'QuotationController', [
        'names' => [
            'index'   => 'quotations',
            'destroy' => 'quotation.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('/quote-pdf/{id}', 'QuotationController@generateQuotePdf')->name('generate.pdfquote');
    Route::get('/quotationPdf/{id}', 'QuotationController@generateQuotationPdf');
    Route::get('/showquote/{id}', 'QuotationController@showQuoteForm')->name('quote.summary');
    Route::get('/mail-quote/{id}', 'QuotationController@emailQuotation')->name('mail.quote');
});

/*Route::get('testmailquote', function () {
    $quotation = \App\Models\Quotation::find(1);

    $details = [
        'greeting' => 'Good day, ' . $quotation->client,
        'body' => 'Please find attached your quotation request. ',
        'body1' => 'For any further queries reply to this email or send your requests to servicestation@whelson.co.zw.',
        'id' => $quotation->id,
        'quote_num' => $quotation->quote_num,
        'client' => $quotation->client,
        'email' => $quotation->email,
        'email_cc' => $quotation->email_cc,
        'price' => $quotation->price,
        'quantity' => $quotation->quantity,
        'currency' => $quotation->currency,
        'amount' => $quotation->amount,
        'notes' => $quotation->notes,
        'done_by' => $quotation->done_by,
        'created_at' => $quotation->created_at
    ];

    return new App\Mail\FuelQuotation($details);
});*/
