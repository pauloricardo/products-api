<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CsvImportedMail extends Mailable
{
    use Queueable, SerializesModels;
    private $path;
    private $json;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($arrayData, $filepath)
    {
        $this->json = json_encode($arrayData);
        $this->path = $filepath;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('paulorangel16@gmail.com')
            ->view('emails.csvimported')
            ->attach($this->path)
            ->with('json', $this->json);
    }

}
