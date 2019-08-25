<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Man;
use App\Girl;
use App\Membership;
use Auth;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getWithInfo($id){
      
        // Add data
        $r['id'] = $id; 
        $r['man'] = false; 
        $r['role']    = 0;
        $r['name']    = "";
        $r['surname'] = "";
        $r['birth']   = "";

        // Get user
        $user = self::with('man')->with('girl')->with('agent')->get()->find($id); 

        if(!$user) return false;
        
        // Check admin
        if($user->role >= 3){
            $r['man'] = $user->role; 
            return $r;
        }

        // Check girl
        if($user->girl){
            $r['man']         = false; 
            $r['name']        = $user->girl['name'];
            $r['role']        = $user['role'];
            $r['location']    = $user->girl['location'];
            $r['birth']       = $user->girl['birth']; 
            $r['age']         = Carbon::parse($r['birth'])->age;
            return $r; 
        }

        // Check man
        if($user->man){
            $r['man']     = 1; 
            $r['name']    = $user->man['name'];
            $r['role']    = $user['role'];
            $r['surname'] = $user->man['surname'];
            $r['birth']   = $user->man['birth']; 
            return $r; 
        }

        // Check agent
        if($user->agent){
            $r['man']     = 3; 
            return $r; 
        }

        return false;
    }

    public static function getHardOnline(){
        //@@@@midleware

        $admin_id = auth::user()->id;

        $online = ChatHardOnline::where('admin_id', '=', $admin_id)->get();

        return $online;
    }  

    public static function payChat($userId, $time){
        //Get Chat price
        $chatPrice = Membership::getChatPrice($userId);
        
        //Count price
        $amount = round($chatPrice * $time, 2);

        //Update balance
        $user = User::with('man')->find($userId);
        $user->man->balance -= $amount;
        
        if(!$user->man->save())
            return false;
        else
            return $amount;
    }


    //Relations
    public function man(){

        return $this->hasOne('App\Man');
    }
    public function girl(){

        return $this->hasOne('App\Girl');
    }    
    public function agent(){

        return $this->hasOne('App\Agent');
    }
    public function message(){

        return $this->hasMany('App\Message');
    }
    public function letter(){

        return $this->hasMany('App\Letter');
    }
    public function inLetter(){

        return $this->hasMany('App\Letter', 'to_user_id');
    }    
    public function room(){

        return $this->belongsToMany('App\Room');
    }  
    public function membership(){

        return $this->belongsToMany('App\Membership')->withPivot('created_at');
    }   


}
