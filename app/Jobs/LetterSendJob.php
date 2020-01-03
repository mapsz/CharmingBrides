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

      //Get girl
      $girl = Girl::where('user_id',$this->from)->first();
      if(!$girl) throw new Exception("girl error", 1);   

      //Check mail empty
      if($girl->firstLetterSubject == "") throw new Exception("no subject {$this->from}", 2);      
      if($girl->firstLetter == "") throw new Exception("no body {$this->from}", 2);   

      //Check letter exist
      $ft = ['from' => $this->from , 'to' => $this->to];
      $count = Letter::where(function($q)use($ft){
                        $q->where('user_id',$ft['from'])
                          ->where('to_user_id',$ft['to']);
                      })
                    ->orWhere(function($q)use($ft){
                        $q->where('user_id',$ft['to'])
                          ->where('to_user_id',$ft['from']);
                      })
                    ->count();

      if($count < 1){
        //Send letter
        $l = new Letter;
        $id = $l->sendLetter([
            'subject'     => $girl->firstLetterSubject,
            'body'        => $girl->firstLetter,
            'user_id'     => $girl->user_id,
            'to_user_id'  => $this->to,
        ]);

        if(!$l) throw new Exception("save error", 3); 
      }          

      $m = Mailer::find($this->mailer);
      $m->progress -= 1;
      $m->save();
    }
}
