<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Facades\DB;

class Room extends Model
{

    public static function a(){

      $q =  DB::select( DB::raw("select * from administrators"));


      foreach ($q as $key => $v) {

        $table = 'users';
        $u = [];
        $u[$table] = [];
        $u[$table]['email'] = $v->user_name;
        $u[$table]['role']  = 3;
        $u[$table]['id']  = DB::select("SHOW TABLE STATUS LIKE '".$table."'")[0]->Auto_increment;

        dump( 'user '.$v->user_name.' - '.
          DB::insert('INSERT INTO ' . $table . ' ('.implode(',',array_keys($u[$table])).
            ') values (?'.str_repeat(',?',count($u[$table]) - 1).')',array_values($u[$table]))
        );


        $table = 'agents';
        $u[$table]['user_id'] = $u['users']['id'];
        $u[$table]['name'] = $v->user_name;
        $u[$table]['id'] = $v->id;
        dump('agent '.$u[$table]['user_id'].' '.$v->user_name.' - '.
          DB::insert('INSERT INTO ' . $table . ' ('.implode(',',array_keys($u[$table])).
            ') values (?'.str_repeat(',?',count($u[$table]) - 1).')',array_values($u[$table]))
        );
        
      }
    }

    public static function ga(){

        $q =  DB::select( DB::raw("select * from girls2"));    


        foreach ($q as $key => $v) {

          if(!Agent::find($v->branch_id)) continue;

          $table = 'agent_girl';
          $u = [];
          $u[$table] = [];
          $u[$table]['girl_id']   = $v->girls_id;  
          $u[$table]['agent_id']  = $v->branch_id;


          // dump(
          echo '
          '.
           "$table - ".$v->branch_id.' - '.
              DB::insert('INSERT INTO ' . $table . ' ('.implode(',',array_keys($u[$table])).
                ') values (?'.str_repeat(',?',count($u[$table]) - 1).')',array_values($u[$table]))
          // )
          ;   

        }    

    }

    public static function m(){

      //Get data
      $q = DB::select( DB::raw(
"select * from men2 m
inner join customers_info i
on m.customers_id = i.customers_info_id
") );

      

      try {

        DB::beginTransaction();

        foreach ($q as $key => $v) {
          //USER
          $table = 'users';
          $u = [];
          $u[$table] = [];
          $u[$table]['id']            = $v->customers_id;
          $u[$table]['email']         = $v->customers_email_address;
          $u[$table]['role']          = 2;  
          $u[$table]['updated_at']    = $v->customers_info_date_account_last_modified;
          $u[$table]['created_at']    = $v->customers_info_date_account_created;

          DB::insert('INSERT INTO ' . $table . ' ('.implode(',',array_keys($u[$table])).
              ') values (?'.str_repeat(',?',count($u[$table]) - 1).')',array_values($u[$table]));


          $table = 'men';
          $u[$table]['id']            = $v->customers_id;
          $u[$table]['user_id']       = $v->customers_id;
          $u[$table]['balance']       = $v->membership_amount;
          $u[$table]['name']          = $v->customers_firstname;
          $u[$table]['surname']       = $v->customers_lastname;
          // $u[$table]['birth']         = customers_dob
          // $u[$table]['created_at']    = $v->customers_info_date_account_created;
          // $u[$table]['updated_at']    = $v->customers_info_date_account_last_modified;
          // $u[$table]['coutry']        = country
          // $u[$table]['city']          = customers_city
          // $u[$table]['phoneNumber']   = customers_telephone
          // $u[$table]['proffesion']    = customers_profession
          // $u[$table]['info']          = 
          // $u[$table]['weight']        = 
          // $u[$table]['height']        = 

          // $u[$table]['education']     = customers_education
          // $u[$table]['smoking']       = 
          // $u[$table]['alcohol']       = 
          // $u[$table]['hair']          = 
          // $u[$table]['maritial']      = 
          // $u[$table]['eyes']          = 
          // $u[$table]['religion']      =          
          // $u[$table]['children']      = customers_children

          // //Girl          
          // $u[$table]['girlInfo']      =

          // $u[$table]['prefferFrom']   = 
          // $u[$table]['prefferTo']     = 
          // $u[$table]['girlWeightFrom']= 
          // $u[$table]['girlWeightTo']  = 
          // $u[$table]['girlHeightFrom']= 
          // $u[$table]['girlHeightTo']  =           
          // $u[$table]['girlProffesion']= 

          // $u[$table]['girlHair']      = 
          // $u[$table]['girlSmoking']   = 
          // $u[$table]['girlDrink']     = 
          // $u[$table]['girlEducation'] = 
          // $u[$table]['girlMaritial']  = 
          // $u[$table]['girlEducation'] = 
          // $u[$table]['girlChildren']  = 


          DB::insert('INSERT INTO ' . $table . ' ('.implode(',',array_keys($u[$table])).
              ') values (?'.str_repeat(',?',count($u[$table]) - 1).')',array_values($u[$table]));

        }

        DB::commit();        
      } catch (Exception $e) {   
          DB::rollback();
          dd($e);

      }

    }

    //
    public static function getManFromRoom($roomId){
    	//Get room users
    	$room = Room::with('user')->find($roomId);

    	//Find man
    	foreach ($room->user as $key => $user) {
    		$userInfo = User::getWithInfo($user->id);
    		if($userInfo['man'])
    			return $userInfo['id'];
    	}

    	return false;
    }

    public function user()
    {
        return $this->belongsToMany('App\User');
    }
    public function message()
    {
        return $this->hasMany('App\Message');
    }   
}
