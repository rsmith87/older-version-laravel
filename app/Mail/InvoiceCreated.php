<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Invoice;

class InvoiceCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $firm;
    public $client;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice, $firm, $client)
    {
        $this->invoice = $invoice;
        $this->firm = $firm;
        $this->client = $client;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.invoice', [
            'invoice' => $this->invoice,
            'firm' => $this->firm,
            'client' => $this->client,
        ]);
    }
}
