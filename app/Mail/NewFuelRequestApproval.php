<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewFuelRequestApproval extends Mailable
{
    use Queueable, SerializesModels;
    public $details;
    public $approveUrl;
    public $reviewUrl;
    public $rejectUrl;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.fuelrequest')->with([
            'details' => $this->details
        ]);
    }
}
