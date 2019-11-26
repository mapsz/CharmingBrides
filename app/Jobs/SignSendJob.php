<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Exception;
use App\Sign;
use App\Letter;
use App\Mailer;

class SignSendJob implements ShouldQueue
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
// php artisan queue:work --queue=high,default,low
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
        if(!Sign::sendSign($this->from,$this->to))
          throw new Exception("sign error", 2);   
      }    

      $m = Mailer::find($this->mailer);
      $m->progress -= 1;
      $m->save();

    }
}
