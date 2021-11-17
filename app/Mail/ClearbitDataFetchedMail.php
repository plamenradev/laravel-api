<?php

namespace App\Mail;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClearbitDataFetchedMail extends Mailable {

    use Queueable,
        SerializesModels;

    /**
     * 
     * @var Company
     */
    public $company;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Company $company) {
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->markdown('emails.clearbit-data-fetched')->with('company', $this->company);
    }

}
