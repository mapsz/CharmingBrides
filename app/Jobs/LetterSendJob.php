<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Exception;
use App\Letter;
use App\Mailer;
use App\Girl;

class LetterSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 3;

    protected $from;
    protected $to;
    protected $mailer;

    public function __construct($from,$to,$mailer)
    {
      $this->from       = $from;
      $this->to         = $to;
      $this->mailer     = $mailer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

      $girl = Girl::where('user_id',$this->from)->first();
      if(!$girl) throw new Exception("girl error", 1);   

      if($girl->firstLetterSubject == "") throw new Exception("no subject {$this->from}", 2);      
      if($girl->firstLetter == "") throw new Exception("no body {$this->from}", 2);   

      $l = new Letter;
      $l->subject = $girl->firstLetterSubject;
      $l->body = $girl->firstLetter;
      $l->user_id = $girl->user_id;
      $l->to_user_id = $this->to;

      if(!$l->save()) throw new Exception("save error", 3);           

      $m = Mailer::find($this->mailer);
      $m->progress -= 1;
      $m->save();
    }
}