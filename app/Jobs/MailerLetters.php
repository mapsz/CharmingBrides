<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Exception;

use App\Mailer;
use App\Jobs\LetterSendJob;

class MailerLetters implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    protected $mailerId;

    public function __construct($mailerId)
    {
      $this->mailerId = $mailerId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      // Get mailer
      $m = Mailer::find($this->mailerId);
      if(!$m) throw new Exception("bad mailer", 3);      
        
      //Get users
      $girls = json_decode($m->from_user_ids);
      $men = json_decode($m->to_user_ids);
      if(!$girls) throw new Exception("bad from", 1);    
      if(!$men) throw new Exception("bad to", 2);   

      foreach ($girls as $girl) {
        foreach ($men as $man) {
          if(!is_int($man)){
            $man = $man->id;
          }
          dispatch((new LetterSendJob($girl->id, $man,$this->mailerId))->onQueue('low'));
        }        
      }
    }
}
