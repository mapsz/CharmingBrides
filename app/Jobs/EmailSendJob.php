<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class EmailSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    protected $d;

    public function __construct($d)
    {
      $this->d = $d;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

      $d = $this->d;

      Mail::send([], [], function ($m)use($d) {
        $m->from('no-reply@charmingbrides.com', 'Charming Brides')
          ->to($d['email'], $d['name'])
          ->subject($d['subject'])
          ->setBody($d['content'], 'text/html');
      });
    }
}
