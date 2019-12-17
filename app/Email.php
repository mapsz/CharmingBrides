<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\EmailSendJob;

class Email extends Model
{

  public static function sendEmailNotification($from_id,$to_id,$type){

    //Get to
    $to = User::where('id',$to_id)->with('man')->first();
    if(!$to->man) return false;

    //Get from
    $from = User::where('id',$from_id)->with('girl')->first();
    if(!$from->girl) return false;

    $link = ($type == 'letter') ? 'https://charmingbrides.com/letters' : 'https://charmingbrides.com/likedyou';

    //Set data
    $data = [];
    $data['email']    = $to->email;
    $data['name']    = $to->man->name;
    $data['subject']  = "You've got a $type from {$from->girl->name}";
    $data['content']  = "Hello {$to->man->name}! You've got $type from {$from->girl->name} <br> To open it please login to your account on Charming Brides or follow the <a href='$link'>link</a><br>
      <img src='https://charmingbrides.com/media/gallery/{$from->id}_0.jpg' alt='{$from->girl->name}'>
      
    ";

    //Send job
    dispatch((new EmailSendJob($data))->onQueue('low'));
  }

}
