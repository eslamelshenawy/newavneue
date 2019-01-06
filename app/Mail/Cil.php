<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Cil extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public function __construct($dataArray)
    {
        $this->data = $dataArray;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $lead=$this->data['lead'];
        $project = $this->data['project'];
        $file = $this->data['file'];
        return $this->view('admin.leads.cil',compact('lead','project','file'));
    }
}
