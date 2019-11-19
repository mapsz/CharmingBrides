<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class Migra extends Model
{
  
  // Girls

// SELECT DISTINCT p.*, g.*, TIMESTAMPDIFF(HOUR,`online`, now()) as since, TIMESTAMPDIFF(day,p.products_date_added,Now()) as newg 
// FROM girls g
// INNER JOIN products p ON g.girls_id = p.products_id
// INNER JOIN products_to_categories p2c ON p.products_id = p2c.products_id
// WHERE (g.branch_id=0 OR (g.branch_id>0 and g.branch_confirm=1))
// and p.products_status=1 and hidden<>'y' 


//   "

    // SELECT 
    //   o.orders_id, 
    //   o.gift_photo, 
    //   o.customers_name, 
    //   o.customers_id, 
    //   o.payment_method, 
    //   o.date_purchased, 
    //   o.last_modified, 
    //   o.currency, 
    //   o.currency_value, 
    //   s.orders_status_name, 
    //   ot.text AS order_total 
    // FROM orders o left join orders_total ot 
    // ON (o.orders_id = ot.orders_id), orders_status s, orders_products op
    // WHERE o.orders_status = s.orders_status_id 
    // AND ot.class = 'ot_total' 
    // ORDER BY o.orders_id DESC


//     SELECT 
//       o.orders_id,
//       o.customers_id,
//       o.date_purchased,
//       ot.value,
//       os.orders_status_id,
//       os.orders_status_name,
//       CONVERT(
//         GROUP_CONCAT( op.products_id SEPARATOR ', ' ),
//         CHAR(8)
//       ) 
//       AS 'products_id',
//       GROUP_CONCAT( op.products_name SEPARATOR ', ' ) as 'desc'
//     FROM orders AS o

//     INNER JOIN `orders_total` AS `ot` 
//     ON `ot`.orders_id = o.orders_id
//     INNER JOIN orders_status AS os 
//     ON os.orders_status_id = o.orders_status
//     INNER JOIN orders_products AS op
//     ON `op`.orders_id = o.orders_id 

//     WHERE `ot`.class = 'ot_total'
//     AND `ot`.`value` > 0

//     GROUP BY o.orders_id


// SELECT 
//   customers_id,
//   customers_firstname,
//   customers_lastname,
//   country,
//   customers_city,
//   customers_body_type,
//   customers_dob,
//   customers_email_address,
//   customers_telephone,
//   customers_password,
//   customers_weight,
//   customers_height,
//   customers_education,
//   customers_profession,
//   customers_children,
//   customers_smoking,
//   customers_drink,
//   customers_marital,
//   `customers_info`,
//   customers_girl_age_from,
//   customers_girl_age_to,
//   customers_girl_hair,
//   customers_girl_body,
//   customers_girl_height_from_cm,
//   customers_girl_height_to_cm,
//   customers_girl_smoking,
//   customers_girl_drink,
//   customers_girl_education,
//   customers_girl_profession,
//   customers_girl_marital,
//   customers_girl_children,
//   customers_girl_description,
//   customers_wife,
//   customers_pass,
//   membership_amount,
//   ci.customers_info_date_of_last_logon,
//   ci.customers_info_date_account_created,
//   ci.customers_info_date_account_last_modified
// FROM customers c 
// INNER JOIN charmin_b2.customers_info ci
// ON c.customers_id = ci.customers_info_id 
// WHERE `deleted` = 0

// SELECT
//   *
// FROM orders_status_history b
// INNER JOIN (SELECT 
//               MAX(orders_status_history_id) as MaxValue
//             FROM orders_status_history) a 
// ON a.MaxValue = b.orders_status_history_id
//     , 


//   ";

  //delete same gender letters
  // Letter::whereHas('user.girl')->whereHas('toUser.girl')->delete();
  // Letter::whereHas('user.man')->whereHas('toUser.man')->delete();

// 2008-03-30 03:22:00
  // 2008-03-30 03:49:05

//   SELECT subject, COUNT(*)
// FROM letters
// GROUP BY subject
// HAVING COUNT(*) > 1


  public static function mi(){
      try {
        DB::beginTransaction();

        echo "del special_ladies -- ";        
        DB::table('special_ladies')->delete();
        echo "del mailers -- ";
        DB::table('mailers')->delete();
        echo "del orders -- ";
        DB::table('orders')->delete();
        echo "del letter_pays -- ";
        DB::table('letter_pays')->delete();
        echo "del signs -- ";
        DB::table('signs')->delete();
        echo "del letters -- ";
        DB::table('letters')->delete();
        echo "del agent_girl -- ";
        DB::table('agent_girl')->delete();
        echo "del girls -- ";
        DB::table('girls')->delete();
        echo "del agents -- ";
        DB::table('agents')->delete();
        echo "del men -- ";
        DB::table('men')->delete();
        echo "del users -- ";
        DB::table('users')->delete();
        

        // Set data
        self::girlsGo();
        self::agentsGo();
        self::girl_agent();
        self::men();
        self::l();
        self::gp();
        self::lpays();
        self::memc();
        self::mfav();
        self::s();

        DB::commit();        
      } catch (Exception $e) {   
        DB::rollback();
        dd($e);
      }

      echo '

      ----------------------------
      ';
      dd('done!');
  }



  //Men favorites
  public static function mfav(){

    DB::table('man_favorites')->delete();

    $ms = Man::get();
    $mt = Man::count();

    foreach ($ms as $i => $m) {
        
      $fs = DB::select( DB::raw("
            SELECT * FROM `charmin_b2`.`favorites`
            WHERE customer_id = {$m->user_id}
          "));

      foreach ($fs as $j => $s) {
        echo "
        man - ".(intval($mt)-intval($i));

        // if(count($s) < 1) continue;

        if (Girl::where('user_id',$s->girls_id)->count() == 0) {
          echo '  -- ! no girl '.$s->girls_id;
          continue;
        }

        ManFavorite::create(['man_user_id' => $m->user_id, 'girl_user_id' => $s->girls_id, ]); 

        echo '  ! add {$s->girls_id}';


      }

           
    }

  }

  //Men country
  public static function memc(){

    $men = Man::get();
    $c = Man::count();

    foreach ($men as $i => $value) {
      echo "
      man - ".(intval($c)-intval($i));

      $countryCode = $value->country;

      if($countryCode == NULL) continue;
      if($countryCode == 'United States') continue;

      $c = DB::select( DB::raw("
            SELECT countries_name AS `name` FROM charmin_b2.countries
            WHERE countries_id = {$countryCode}
          "))[0]->name;


      $value->country = $c;

      $value->save();

    }
  }

  //Letter pays
  public static function lpays(){

    DB::table('letter_pays')->delete();


    $ms = Man::get();
    $mt = Man::count();

    foreach ($ms as $i => $m) {
      $ls = letter::where('to_user_id',$m->user_id)->get();
      $lt = letter::where('to_user_id',$m->user_id)->count();

      foreach ($ls as $j => $l) {
        echo "
        man - ".(intval($mt)-intval($i)) ." letter - ".(intval($lt)-intval($j));
        
        $lo = DB::select( DB::raw("
              SELECT `mail_id`,`mail_read` FROM charmin_b2.mail2customers
              WHERE mail_from = {$l->user_id}
              AND mail_to = {$l->to_user_id}
            "));

        if(count($lo) < 1) continue;

        if($lo[0]->mail_read == 1){
          LetterPay::create(['letter_id' => $l->id, 'price' => '0.02']); echo '  ! add {$l->id}';
        } 
      }

      

    }
  }

  //Girl photo
  public static function gp(){

    $girl = Girl::get();

    foreach ($girl as $key => $v) {
      if($key % 10 == 0) {echo "
          $key";}

      $q = DB::select( DB::raw(
                   "SELECT * FROM charmin_b2.girls
                    WHERE girls_id = ".$v->id
                  ))[0];



      if($q->girls_photo){
         \File::copy(
                    base_path('public/girls/'.$q->girls_photo),
                    base_path('public/media/gallery/'.$v->id.'_0.jpg')
                  );
      }else{
        echo "
          ! no photo 1 ".$v->id;
      }
      if($q->girls_photo2){
         \File::copy(
                    base_path('public/girls/'.$q->girls_photo2),
                    base_path('public/media/gallery/'.$v->id.'_2.jpg')
                  );
      }else{
        echo "
          ! no photo 2 ".$v->id;
      }
      if($q->girls_photo3){
         \File::copy(
                    base_path('public/girls/'.$q->girls_photo3),
                    base_path('public/media/gallery/'.$v->id.'_3.jpg')
                  );
      }else{
        echo "
          ! no photo 3 ".$v->id;
      }
      if($q->girls_photo4){
         \File::copy(
                    base_path('public/girls/'.$q->girls_photo4),
                    base_path('public/media/gallery/'.$v->id.'_4.jpg')
                  );
      }else{
        echo "
          ! no photo 4 ".$v->id;
      }

    }
  }

  public static function mp(){

    $girl = Man::get();

    foreach ($girl as $key => $v) {
      if($key % 10 == 0) {echo "
          $key";}

      $q = DB::select( DB::raw(
                   "SELECT * FROM charmin_b2.customers
                    WHERE customers_id = ".$v->id
                  ))[0];



      if($q->customers_photo){
         \File::copy(
                    base_path('public/old/man/'.$q->customers_photo),
                    base_path('public/media/gallery/'.$v->id.'_0.jpg')
                  );
      }else{
        echo "
          ! no photo 1 ".$v->id;
      }
      if($q->customers_photo2){
         \File::copy(
                    base_path('public/old/man/'.$q->customers_photo2),
                    base_path('public/media/gallery/'.$v->id.'_2.jpg')
                  );
      }else{
        echo "
          ! no photo 2 ".$v->id;
      }
      if($q->customers_photo3){
         \File::copy(
                    base_path('public/old/man/'.$q->customers_photo3),
                    base_path('public/media/gallery/'.$v->id.'_3.jpg')
                  );
      }else{
        echo "
          ! no photo 3 ".$v->id;
      }

    }
  }

  public static function l(){
    echo '
       '.'--------letter -----';
    $user = User::get();


    DB::table('letters')->delete();

    foreach ($user as $key => $value) {
      
      echo '
       '.$value->id;
        $q = DB::select( DB::raw(
             'SELECT * FROM charmin_b2.mail2customers
              WHERE 
              (
                    subject NOT LIKE "%has sent you a Kiss%"
                AND subject NOT LIKE "%Send a Sign of Interest.%"
                AND subject NOT LIKE "%You have got a kiss from%"
                AND subject NOT LIKE "%has sent you Sign of Interest%"
                AND subject NOT LIKE "%You\'ve got a sign of interest from%"
                AND subject NOT LIKE "%Send a Kiss.%"
                AND subject NOT LIKE "%Thank you, I\'m not interested.%"
                AND subject NOT LIKE "%isn\'t sure. from%"
                AND subject NOT LIKE "%Send a Reply Sign of Interest.%"
                AND subject NOT LIKE "%I\'m Send Sign of Interest.%"
                AND subject NOT LIKE "%has sent you Mutual Kiss from%"
                AND subject NOT LIKE "%has sent you Reply Sign of Interest%"
                AND subject NOT LIKE "%has declined your Sign of Interest%"
              )              
              AND mail_from = ' . $value->id
            ) );

      foreach ($q as $k => $v) {

        echo '
         to - '.$v->mail_to;

        if(!User::find($v->mail_to)){
          echo '  ! user not found';
          continue;
        } 

        $pre = [];

        $pre['id']            = $v->id;
        $pre['subject']       = $v->subject;
        $pre['body']          = $v->body;
        $pre['user_id']       = $v->mail_from;
        $pre['to_user_id']    = $v->mail_to;
        $pre['`read`']        = Carbon::now();
        $pre['created_at']    = $v->date;
        $pre['updated_at']    = $v->date;

        self::dbInsert('letters', $pre);

      }


    }
    //
  }

  public static function men(){
    echo "
      MeN-------------
    ";

    $q = include("App\migrate\men.php");
    foreach ($q as $key => $val) {$v = (object) $val;

      if($key % 100 == 0) {echo "
          $key";}

      $user = user::find($v->customers_id);
      if($user){
        echo '
        dublicate - ' .$user->id;        
        continue;
      }

      if($v->customers_email_address == ""){
        echo '
        empty email - ' .$v->customers_id;               
        continue;
      }

      if($v->customers_email_address == "0"){
        echo '
        zero email - ' .$v->customers_id;      
        continue;
      }      

        if($v->customers_smoking = 0)

        //education
        $aaa = null;
        switch ($v->customers_education) {
          case 'associate degree':
            $aaa = 1;
            break;
          case 'college':
          case 'collage':
          case 'College':
          case 'COLLEGE':
            $aaa = 2;
            break;  
          case 'high school':
            $aaa = 2;
            break;  
          case 'student':
            $aaa = 2;
            break; 
          case 'university':
          case 'University':
          case 'Bachelor':
          case 'University degree':
            $aaa = 2;
            break;            
          case 'other':
            $aaa = 0;
            break;          
          default:
            if($v->customers_education != ""){
              // echo $v->customers_education .' - education
              // ';
            }
            $aaa = null;
            break;
        }        
        $v->customers_education = $aaa ;   

        //maritial
        $aaa = null;
        switch ($v->customers_marital) {
          case 'divorced':
          case 'Divorced':
            $aaa = 2;
            break;
          case 'never married':
            $aaa = 1;
            break;  
          case 'Widowed':
          case 'widowed':
          case 'Widow':
          case 'widow':
            $aaa = 3;
            break;        
          case 'other':
            $aaa = 0;
            break;       
          case 'single':
          case 'Single':
            $aaa = null;
            break;          
          default:
            if($v->customers_marital != ""){
              echo $v->customers_marital .' - marital_status
              ';
            }
            $aaa = null;
            break;
        }        
        $v->customers_marital = $aaa ;   

        //children
        $aaa = null;
        switch ($v->customers_children) {
          case 'no':
            $aaa = 0;
            break;
          case 0:
            $aaa = 0;
            break;            
          case 1:
            $aaa = 1;
            break;  
          case 2:
            $aaa = 2;
            break;        
          case 3:
            $aaa = 3;
            break;        
          case 4:
            $aaa = 4;
            break;        
          case 5:
            $aaa = 5;
            break;          
          default:
            if($v->customers_children > 5){
              $v->customers_children = 5;
              break;
            }

             
            if($v->customers_children != ""){
              echo $v->customers_children .' - children
              ';
            }
            $aaa = null;
            break;
        }        
        $v->customers_children = $aaa ;   

        //smoking
        $aaa = null;
        switch ($v->customers_smoking) {
          case 'yes':
          case 1:
            $aaa = 1;
            break;
          case 'no':
          case '-':
          case 0:
            $aaa = 0;
            break;                
          default:
            if($v->customers_smoking != ""){
              echo $v->customers_smoking .' - smoking
              ';
            }
            $aaa = null;
            break;
        }        
        $v->customers_smoking = $aaa ;   

        //alcohol
        $aaa = null;
        switch ($v->customers_drink) {
          case 'Social':
          case 'Socially':
          case 'socially':
          case 'some':
          case 'rarely':
          case 'sometimes':
          case 'y':
          case 'yes':
          case 1:
          case 2:
            $aaa = 1;
            break;
          case 'no':
          case 'No':
          case 'never':
          case 0:
            $aaa = 0;
            break;         
          default:
            if($v->customers_drink != ""){
              echo $v->customers_drink .' - alcohol
              ';
            }
            $aaa = null;
            break;
        }        
        $v->customers_drink = $aaa ;   
   
      
      //USER
      $table = 'users';
      $u = [];
      $u[$table] = [];
      $u[$table]['id']            = $v->customers_id;
      $u[$table]['email']         = $v->customers_email_address;
      $u[$table]['role']          = 2;  
      $u[$table]['password']      = Hash::make($v->customers_pass);  
      $u[$table]['updated_at']    = $v->customers_info_date_account_last_modified;
      $u[$table]['created_at']    = $v->customers_info_date_account_created;
      if("2011-03-27 03:05:16" == $v->customers_info_date_of_last_logon) $v->customers_info_date_of_last_logon = null;
      $u[$table]['last_login']    = $v->customers_info_date_of_last_logon;

      DB::insert('INSERT INTO ' . $table . ' ('.implode(',',array_keys($u[$table])).
          ') values (?'.str_repeat(',?',count($u[$table]) - 1).')',array_values($u[$table]));


      $table = 'men';
      $u[$table]['id']            = $v->customers_id;
      $u[$table]['user_id']       = $v->customers_id;
      $u[$table]['balance']       = $v->membership_amount;
      $u[$table]['name']          = $v->customers_firstname;
      $u[$table]['surname']       = $v->customers_lastname;
      if($v->customers_dob == 0) $v->customers_dob = null;
      $u[$table]['birth']         = $v->customers_dob;
      $u[$table]['created_at']    = $v->customers_info_date_account_created;
      $u[$table]['updated_at']    = $v->customers_info_date_account_last_modified;
      $u[$table]['country']        = $v->country;
      if(strlen($v->customers_city) > 35) $v->customers_city =null;
      $u[$table]['city']          = $v->customers_city;
      $u[$table]['education']   = $v->customers_education;
      $u[$table]['phoneNumber']   = $v->customers_telephone;
      if(strlen($v->customers_profession) > 55) $v->customers_profession =null;
      $u[$table]['proffesion']    = $v->customers_profession;
      $u[$table]['children']    = $v->customers_children;
      $u[$table]['smoking']    = $v->customers_smoking;
      $u[$table]['alcohol']    = $v->customers_drink;
      $u[$table]['maritial']    = $v->customers_marital;
      if($v->customers_weight > 254) $v->customers_weight =null;
      $u[$table]['weight']    = $v->customers_weight;
      if($v->customers_height > 254) $v->customers_height =null;
      $u[$table]['height']    = $v->customers_height;
      $u[$table]['info']    = $v->customers_info;

      //Girls
      if(!is_int($v->customers_girl_age_from)) $v->customers_girl_age_from = null;
      $u[$table]['prefferFrom']  = $v->customers_girl_age_from;
      if(!is_int($v->customers_girl_age_to)) $v->customers_girl_age_to = null;
      $u[$table]['prefferTo']  = $v->customers_girl_age_to;
      if(!is_int($v->customers_girl_height_from_cm)) $v->customers_girl_height_from_cm = null;
      $u[$table]['girlHeightFrom']  = $v->customers_girl_height_from_cm;
      if(!is_int($v->customers_girl_height_to_cm)) $v->customers_girl_height_to_cm = null;
      $u[$table]['girlHeightTo']  = $v->customers_girl_height_to_cm;
      $u[$table]['girlProffesion']  = $v->customers_girl_profession;
      if($v->customers_girl_description == "") 
        $u[$table]['girlInfo'] = $v->customers_wife;
      else
        $u[$table]['girlInfo'] =$v->customers_girl_description;


      DB::insert('INSERT INTO ' . $table . ' ('.implode(',',array_keys($u[$table])).
          ') values (?'.str_repeat(',?',count($u[$table]) - 1).')',array_values($u[$table]));
    }     
  }

  public static function girl_agent(){

    $q = include("App\migrate\girls.php");

    echo "-------------Girls to Agents-------------
    ";
    foreach ($q as $key => $val) {$v = (object) $val;

      if($v->branch_id == 0)continue;

      if(!Agent::find($v->branch_id)){
        echo '
        girl_id '.$v->girls_id .'  agent - '. $v->branch_id . ' no found!';
        continue;
      }

      $table = 'agent_girl';
      $u = [];
      $u[$table] = [];
      $u[$table]['girl_id']   = $v->girls_id;  
      $u[$table]['agent_id']  = $v->branch_id;


      echo '
      '.
       "$table - ".$v->branch_id.' - '.
          DB::insert('INSERT INTO ' . $table . ' ('.implode(',',array_keys($u[$table])).
            ') values (?'.str_repeat(',?',count($u[$table]) - 1).')',array_values($u[$table]))

      ;   
    }
  }

  public static function girlsGo(){

    echo "-------------Girls-------------
    ";

    $q = include("App\migrate\girls.php");
    
      foreach ($q as $key => $val) {

        $v = (object) $val;
        //USER

        $table = 'users';
        $u['id']                      = $v->girls_id;
        $u['email']                   = $v->email != '' ? $v->email : $v->girls_id;
        $u['role']                    = $v->products_status;  
        $u['updated_at']              = $v->products_last_modified;  
        $u['created_at']              = $v->products_date_added;  

        self::dbInsert($table, $u);

        //GIRL
        $table = 'girls';
        $p['id']                      = $v->girls_id;
        $p['user_id']                 = $v->girls_id;
        $p['name']                    = $v->girls_name;
        $p['birth']                   = $v->girls_dob != '0000-00-00' ? $v->girls_dob : null;
        $p['location']                = $v->girls_location;
        $p['weight']                  = $v->girls_weight;
        $p['height']                  = $v->girls_height;
        $p['profession']              = $v->girls_profession;  
        $p['updated_at']              = $v->products_last_modified;  
        $p['created_at']              = $v->products_date_added;  
        

        //hair     
        $hair = null;
        switch ($v->girls_hair) {
          case 'black':
            $hair = 1;
            break;
          case 'blond':
            $hair = 2;
            break;
          case 'brown' || 'brunette' || 'chestnut':
            $hair = 3;
            break;
          case 'fair':
            $hair = 5;
            break;
          case 'red':
            $hair = 5;
            break;          
          default:
            if($v->girls_hair != ""){
              echo $v->girls_hair .' - hair
              ';            
            }
            $hair = null;
            break;
        }
        $p['hair'] = $hair;

        //eyes        
        $eyes = null;
        switch ($v->girls_eyes) {
          case 'blue':
            $eyes = 1;
            break;
          case 'green':
            $eyes = 2;
            break;
          case 'grey':
            $eyes = 3;
            break;
          case 'hazel':
            $eyes = 4;
            break;
          case 'olive':
            $eyes = 0;
            break;          
          default:
            if($v->girls_eyes != ""){
              echo $v->girls_eyes .' - eyes
              ';
            }
            $eyes = null;
            break;
        }        
        $p['eyes'] = $eyes;

        //religion        
        $aaa = null;
        switch ($v->girls_religion) {
          case 'christian':
            $aaa = 1;
            break;
          case 'other':
            $aaa = 0;
            break;          
          default:
            if($v->girls_religion != ""){
              echo $v->girls_religion .' - religion
              ';
            }
            $aaa = null;
            break;
        }        
        $p['religion'] = $aaa ;       


        //education
        $aaa = null;
        switch ($v->girls_education) {
          case 'associate degree':
            $aaa = 1;
            break;
          case 'college':
            $aaa = 2;
            break;  
          case 'high school':
            $aaa = 2;
            break;  
          case 'student':
            $aaa = 2;
            break; 
          case 'university':
            $aaa = 2;
            break;            
          case 'other':
            $aaa = 0;
            break;          
          default:
            if($v->girls_education != ""){
              echo $v->girls_education .' - education
              ';
            }
            $aaa = null;
            break;
        }        
        $p['education'] = $aaa ;   


        //maritial
        $aaa = null;
        switch ($v->girls_marital_status) {
          case 'divorced':
            $aaa = 2;
            break;
          case 'never married':
            $aaa = 1;
            break;  
          case 'widowed':
            $aaa = 3;
            break;        
          case 'other':
            $aaa = 0;
            break;          
          default:
            if($v->girls_marital_status != ""){
              echo $v->girls_marital_status .' - marital_status
              ';
            }
            $aaa = null;
            break;
        }        
        $p['maritial'] = $aaa ;   

        //children
        $aaa = null;
        switch ($v->girls_children) {
          case 'no':
            $aaa = 0;
            break;
          case 0:
            $aaa = 0;
            break;            
          case 1:
            $aaa = 1;
            break;  
          case 2:
            $aaa = 2;
            break;        
          case 3:
            $aaa = 3;
            break;          
          default:
            if($v->girls_children != ""){
              echo $v->girls_children .' - children
              ';
            }
            $aaa = null;
            break;
        }        
        $p['children'] = $aaa ;   

        //smoking      
        $p['smoking'] = 0 ;   

        //alcohol
        $aaa = null;
        switch ($v->girls_alcohol) {
          case 'socially':
            $aaa = 1;
            break;
          case 'no':
            $aaa = 0;
            break;         
          default:
            if($v->girls_alcohol != ""){
              echo $v->girls_alcohol .' - alcohol
              ';
            }
            $aaa = null;
            break;
        }        
        $p['alcohol'] = $aaa ;   

        //english
        $aaa = null;
        switch ($v->girls_english_proficiency) {
          case 'fluent':
            $aaa = 1;
            break;
          case 'good':
            $aaa = 2;
            break;
          case 'medium':
            $aaa = 3;
            break;
          case 'poor':
            $aaa = 4;
            break;
          case 'some':
            $aaa = 5;
            break;     
          default:
            if($v->girls_english_proficiency != ""){
              echo $v->girls_english_proficiency .' - english
              ';
            }
            $aaa = null;
            break;
        }        
        $p['english'] = $aaa ;  

        $p['languages']               = $v->girls_other_languages;
        $p['prefferFrom']             = $v->mens_age_from;
        $p['prefferTo']               = $v->mens_age_to;
        $p['info']                    = $v->girls_info;
        $p['firstLetterSubject']      = $v->girls_1st_letter_subj;
        $p['firstLetter']             = $v->girls_1st_letter;
        $p['forAdminInfo']            = $v->girls_admininfo;  

        DB::insert('INSERT IGNORE INTO ' . $table . ' ('.implode(',',array_keys($p)).
            ') values (?'.str_repeat(',?',count($p) - 1).')',array_values($p));

      }
  }

  public static function agentsGo(){

    $q = include("App\migrate\agents.php");

    foreach ($q as $key => $val) {      $v = (object) $val;
      $table = 'users';
      $u = [];
      $u[$table] = [];
      $u[$table]['email'] = $v->user_name . '@agent.com';
      $u[$table]['role']  = 3;
      $u[$table]['id']  = DB::select("SHOW TABLE STATUS LIKE '".$table."'")[0]->Auto_increment;

      echo '
      '.'user '.$u[$table]['id'];

      self::dbInsert($table, $u[$table]);

      $table = 'agents';
      $u[$table]['user_id'] = $u['users']['id'];
      $u[$table]['name'] = $v->user_name;
      $u[$table]['id'] = $v->id;
    
      echo '  agent '.$u[$table]['id'].' '.$v->user_name;

      self::dbInsert($table, $u[$table]);
    }
  }

  public static function s(){

    DB::table('signs')->delete();

    echo 'hi';

    $query ='SELECT *
          FROM charmin_b2.mail2customers
          WHERE 
          (
               subject LIKE "%has sent you a Kiss%"
            OR subject LIKE "%Send a Sign of Interest.%"
            OR subject LIKE "%You have got a kiss from%"
            OR subject LIKE "%has sent you Sign of Interest%"
            OR subject LIKE "%You\'ve got a sign of interest from%"
            OR subject LIKE "%Send a Kiss.%"
            OR subject LIKE "%Thank you, I\'m not interested.%"
            OR subject LIKE "%isn\'t sure. from%"
            OR subject LIKE "%Send a Reply Sign of Interest.%"
            OR subject LIKE "%I\'m Send Sign of Interest.%"
            OR subject LIKE "%has sent you Mutual Kiss from%"
            OR subject LIKE "%has sent you Reply Sign of Interest%"
            OR subject LIKE "%has declined your Sign of Interest%"
          )

          AND mail_to <> 0
          AND mail_from <> 0

          order by mail_to';

    $i = 0;

    while (1){  

      $q = DB::select( DB::raw(
            $query.
            ' limit 100000 OFFSET '.$i
          ) );


      if (count($q) == 0 )dd('hi');


      $i+=100000;

      echo '
      '.$i;

      foreach ($q as $k => $v) {

        if(!User::find($v->mail_from)){
          // echo '  ! user not found';
          continue;
        } 

        if(!User::find($v->mail_to)){
          // echo '  ! user not found';
          continue;
        }


        $type = 0;

        // from >> to 1
        if(
          (strpos($v->subject, 'has sent you a Kiss') > -1) ||
          (strpos($v->subject, 'Send a Sign of Interest.') > -1)  ||
          (strpos($v->subject, 'You have got a kiss from') > -1)  ||
          (strpos($v->subject, 'has sent you Sign of Interest') > -1)  ||
          (strpos($v->subject, 'You\'ve got a sign of interest from') > -1)  ||
          (strpos($v->subject, 'Send a Kiss.') > -1)  ||
          (strpos($v->subject, 'I\'m Send Sign of Interest.') > -1) 
        )
        $type = 1;
        // from >x to 2
        if(
          (strpos($v->subject, 'Thank you, I\'m not interested.') > -1)  ||
          (strpos($v->subject, 'isn\'t sure. from') > -1)  ||
          (strpos($v->subject, 'isn\'t sure. from') > -1) 
        )  
        $type = 2;
        // from == to 3
        if(
          (strpos($v->subject, 'Send a Reply Sign of Interest.') > -1)  ||
          (strpos($v->subject, 'Send a Reply Sign of Interest.') > -1)  ||
          (strpos($v->subject, 'has sent you Mutual Kiss from') > -1) 
        )
        $type = 3;

        // check exists
        $q = Sign::where('from_id' , $v->mail_from)->where('to_id', $v->mail_to);

        $q = Sign::where(function($q)use($v) {
                    $q->where('from_id' , $v->mail_from)
                      ->where('to_id', $v->mail_to);
                  })
            ->orWhere(function($q)use($v) {
                    $q->where('to_id' , $v->mail_from)
                      ->where('from_id', $v->mail_to);
                  });

        $e = false;
        if(count($q->get()) > 0){
          $exists = $q->first()->toArray();
          if(!isset($exists['to_id'])) dd($v,$exists);
          if($v->mail_to != $exists['to_id']){
            $e = true;
          }
        }

        $table = 'signs';
        $rows = [
          'from_id' => $v->mail_from,
          'to_id' => $v->mail_to,
          'created_at' => $v->date,
        ];

        if($type == 1){          
          if($e) $rows['to_confirmed'] = 1;
          else $rows['from_confirmed'] = 1;
        };

        if($type == 2){
          if($e) $rows['to_confirmed'] = -1;
          else $rows['from_confirmed'] = -1;
        };

        if($type == 3){
          $rows['from_confirmed'] = 1;
          $rows['to_confirmed'] = 1;
        };    
        
        if(count($q->get()) > 0){
          //Update
          $q->update($rows);

          echo '
          update ' . $v->mail_from . ' - '. $v->mail_to . ' -- ' . $i;
        }else{
          //Insert          
          $rows['`read`'] = '2019-11-19 00:00:00';
          self::dbInsert($table, $rows);
          echo '
          insert ' . $v->mail_from . ' - '. $v->mail_to . ' -- ' . $i;
        }

      }


    }
  }

  public static function dbInsert($table, $rows){
      
        DB::insert('INSERT INTO ' . $table . ' ('.implode(',',array_keys($rows)).
          ') values (?'.str_repeat(',?',count($rows) - 1).')',array_values($rows));
  }

}
