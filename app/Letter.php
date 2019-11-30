<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Membership;
use App\User;
use App\Settings;
use App\Email;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;



class Letter extends _adminPanel
{

    public $guarded = [];

    //Data

    protected $single    = 'letter';
    protected $multi     = 'letters';    
    protected $route     = [ 'prefix' => 'admin/' ];
    
    protected $edit      = false; 
    protected $delete    = false; 
    protected $add       = false; 

    protected $order       = ['row' => 'created_at','order' => 'DESC'];

    protected $columns  = [
        [
          'name' => 'id',
        ],
        [
          'name' => 'subject',
        ],
        [
          'name' => 'user',
          'caption' => 'From',
          'component' => 'admin-letter-user',
          'attr' => 'from',
        ],
        [
          'name' => 'toUser',
          'caption' => 'To',
          'component' => 'admin-letter-user',
          'attr' => 'to',
        ],
        [
          'name' => 'agent',
          'component' => 'admin-letter-agent',
        ],        
        [
          'name' => 'created_at',
          'caption' => 'date',
          'timeFormat'  => 'j M Y G:i' 
        ],
        [
          'name' => 'read',
          'caption' => 'read',
        ],      
        [
          'name' => 'more',
          'component' => 'admin-letters-link',
        ]
    ];    
    protected $inputs    = [
    ];

    public function __construct(){
        parent::__construct($this->single, $this->multi, $this->page, $this->inputs);
    }

    public function validate($request){

        $val = $request->validate([
            'body'                   => 'required|min:3',
            'to_user_id'             => 'required',
        ]);

        return $val;
    }

    public static function sendLetter($data){
      //Send letter        
      $l = new self;
      $l->subject      = $data['subject'];
      $l->body         = $data['body'];
      $l->user_id      = $data['user_id'];
      $l->to_user_id   = $data['to_user_id'];
      if(!$l->save()) return false;   

      //Send email notification
      Email::sendEmailNotification($data['user_id'],$data['to_user_id'],'letter');

      return $l->id;
    }    



    public static function getLetterType($id){
      
      return (strlen(Letter::where('id',$id)->first()->body) < self::getLongLetterLength()) ? 'Short Letter' : 'Letter';
    } 

    public static function getCompanions($userId){


      $t = time();

        // $id = 58649;
        $id = $userId;

        // DB::enableQueryLog();
        $pages = 20;
        $page  = (isset($_GET['companion_page'])) ? $_GET['companion_page'] : 1;
        $offset = ($pages * $page) - $pages;
        //Get ids
        //@@@ binds
        $ids= DB::select( DB::raw( 
        "
          SELECT `user` ,MAX(`date`) as `date`, sum(`read`) as `read`,sum(`id`) as `id`
          FROM(
            SELECT `to_user_id` as `user`, MAX(`created_at`) as `date`,0 as `read`,0 as `id` FROM `letters`
            WHERE `user_id` = $id
            GROUP BY `user`
            UNION DISTINCT
            SELECT `user_id` as `user`, MAX(`created_at`) as `date`,count(`read`) as `read`,count(`id`) as `id` FROM `letters`
            WHERE `to_user_id` = $id
            GROUP BY `user`
          ) AS tmp
          GROUP BY `user`
          ORDER BY `date` DESC
          LIMIT " . $pages . ' OFFSET ' . $offset
        ) );
        //Filter ids
        $userIds = [];
        foreach ($ids as $v) {
          array_push($userIds, $v->user);
        }
        //Get users
        $users = User::whereIn('id',$userIds)->with('girl')->with('man')->get();
        //Get info
        $companions = [];
        foreach ($users as $k => $v) {
          array_push($companions, User::getWithInfo($v->id,$v));
        }      

        //add date/sort
        $out = [];
        foreach ($ids as $id) {
          $read = 1;
          if($id->read < $id->id) $read = 0;
          foreach ($companions as $k => $c) {
            if($c['id'] == $id->user){
              $companions[$k]['date'] = $id->date;
              $companions[$k]['read'] = $read;
              array_push($out,$companions[$k]);
            }
          }  
        }

        return $out;
    }

    public static function getLetters($userId, $companionId){

      //Get letters
      $letters = self::where([
              ['user_id', '=', $userId],
              ['to_user_id', '=', $companionId]
            ])
          ->orWhere([
              ['user_id', '=', $companionId],
              ['to_user_id', '=', $userId]
            ])
          ->with('letterPay')
          ->orderBy('created_at','DESC')
          ->get()->toArray();

      //Get user
      $user = User::getWithInfo($userId);

      //Get man membership
      $man = false;
      if($user['man'] === 1){
        $membership = Membership::getCurrentMembership($userId);
        $man = $userId;
      }else{
        $man = $companionId;
      }
        
      //Set Pay
      foreach ($letters as $k => $letter) {
        // check man reciever
        if($letter['to_user_id'] != $man){
          $letters[$k]['payed'] = 1;
          continue;
        } 

        if(!isset($letter['letter_pay']) || !$letter['letter_pay']){
          if($user['man'] === 1){
            $letters[$k]['body'] = "";
            $letters[$k]['cost'] = self::getLetterCost($letter['body'], $membership);
          }          
          $letters[$k]['payed'] = false;          
        }else{
          $letters[$k]['payed'] = true;
        }
      }
      
        
      return $letters;
    }


    public static function getUserLetters($userId){

        $letters = self::getLetterswithUsers($userId);

        //Set payed Letters
        if(User::getWithInfo($userId)['man']){ //@@@ mb не надо дб запрос

          //Get membership
          $membership = Membership::getCurrentMembership($userId);

          foreach ($letters as $k => $letter){
            //Input letter
            if($letter['to_user_id'] == $userId){

              ///Payed
              $letters[$k]['payed'] = ($letter['letter_pay']) ? true : false;

              //Cost
              $letters[$k]['cost'] = self::getLetterCost($letter['body'], $membership);
            }            
          }
        }


        //Form letters
        $companionWithLetters = [];
        foreach ($letters as $k => $letter){

          //Get companion
          $companion = [];
          if($letter['to_user']['girl'] != null){
            $companion = $letter['to_user']['girl'];            
            $companion['man'] = false;
          }elseif($letter['to_user']['man'] != null){
            $companion['companion'] = $letter['to_user']['man'];
            $companion['man'] = true;
          }

          //Add letter
          $preLetter = [];
          $preLetter['id'] = $letter['id'];
          $preLetter['subject'] = $letter['subject'];          
          $preLetter['user_id'] = $letter['user_id'];
          $preLetter['to_user_id'] = $letter['to_user_id'];
          $preLetter['created_at'] = $letter['created_at'];
          if(isset($letter['payed']) && $letter['payed'] == false){
            $preLetter['body'] = "";
            $preLetter['payed'] = false;
            $preLetter['cost'] = $letter['cost'];
          }else{
            $preLetter['body'] = $letter['body'];
            $preLetter['payed'] = true;
          }
          

          //check if Companion exists
          if($userId == $letter['to_user_id']){
            $writer = 'user_id';
          }else{
            $writer = 'to_user_id';
          }
          $companionKey = array_search($letter[$writer], array_column(array_column($companionWithLetters, 'companion'),'user_id'));

          if($companionKey !== false){
            //Exists
            array_push($companionWithLetters[$companionKey]['letters'], $preLetter);
          }else{
            //New companion
            array_push($companionWithLetters,
              [
                'companion' => $companion ,
                'letters'   => [$preLetter],
              ]
            );            
          }  

        }



        return $companionWithLetters;
    }

    public static function getLetterCost($body, $membership){

      return (strlen($body) < self::getLongLetterLength()) ? $membership->letter_price : $membership->long_letter_price;
    }

    public static function getLongLetterLength(){

      return Settings::g('LetterLongLength');
    }


    public static function setLongLetterLength($letterSize){

      return Settings::s('LetterLongLength', $letterSize);
    }

    protected static function getLetterswithUsers($userId){
      return self::with('user.man','user.girl','toUser.man', 'toUser.girl','letterPay')
                      ->where('user_id','=',$userId)
                      ->orWhere('to_user_id','=',$userId)
                      ->paginate(50);   
    }



    // protected function getDbData(){
    //   if (Auth::user() &&  Auth::user()->role == 4) {
    //      $data = User::has('letter')      
    //                   ->orHas('inLetter')
    //                   ->with(
    //                     'man',
    //                     'girl',
    //                     'agent',
    //                     'letter.user.man',
    //                     'letter.user.girl',
    //                     'letter.letterPay',      
    //                     'inLetter.user.man',
    //                     'inLetter.user.girl',
    //                     'inLetter.letterPay'
    //                   )
    //                   ->paginate(50)->toArray();
    //   }else{
    //       $data = User::
    //               with(
    //                 'man',
    //                 'girl',
    //                 'girl.agent',
    //                 'letter.user.man',
    //                 'letter.user.girl',
    //                 'letter.letterPay',
    //                 'inLetter.user.man',
    //                 'inLetter.user.girl',                    
    //                 'inLetter.user.girl.agent',
    //                 'inLetter.letterPay'
    //               )
    //               ->whereHas('letter.user.girl.agent', function($q){
    //                   $q->where('id', '=', '1');
    //               })
    //               ->orWhereHas('inLetter.user.girl.agent', function($q){
    //                   $q->where('id', '=', '1');
    //               })
    //               ->paginate(50)->toArray();       
    //   }

    //     $correspondences = [];
    //     $letters = [];
    //     $lKeys = ['letter','in_letter'];
    //     //Data
    //     foreach ($data as $d) {          
    //       foreach ($lKeys as $lKey) {            
    //         foreach ($d[$lKey] as $letter) {
    //           //Letters
    //           //skip if letters exists
    //           if(self::letterExists($letters, $letter)) continue;
    //           //add letter
    //           array_push($letters, $letter);

    //           //Correspondences
    //           //get corres
    //           $correspondence = self::correspondenceGet($correspondences, $letter['user_id'],$letter['to_user_id']);
    //           //create if doesnt exist
    //           if(!$correspondence){
    //             $correspondence = self::correspondenceCreate($letter);
    //             array_push($correspondences, $correspondence);
    //           }

    //           //add letter
    //           $correspondences = self::correspondenceAddLetter($correspondences, $letter);
              
    //         }
    //       }
    //     }

    //     //Add users
    //     $correspondences = self::correspondenceAddUser($correspondences, $data);

    //     //Unset trash
    //     foreach ($correspondences as $k => $c) {
    //       unset($correspondences[$k]['girl_id']);
    //       unset($correspondences[$k]['man_id']);


    //       if($c['girl'] == null || $c['man'] == null)
    //         unset($correspondences[$k]);
    //     }

    //     // dd($correspondences);
    //     $this->dbData = $correspondences;        
    // }

    private static function letterExists($letters, $letter){
      foreach ($letters as $value) {
        if($value['id'] == $letter['id']) return true;
      }
      return false;
    }
    private static function correspondenceGet($correspondences, $userId, $userId2){
      foreach ($correspondences as $c) {
        if($c['girl_id'] == $userId && $c['man_id']  == $userId2) return $c;
        if($c['man_id'] == $userId && $c['girl_id']  == $userId2) return $c;
      }
      return false;
    }
    private static function correspondenceCreate($letter){
      $correspondence=['girl_id' => null,'man_id' => null,'girl' => null,'man' => null,'letters' => 0,'pay_count' => 0,'pay_summ' => 0];
      if(isset($letter['user']['girl']['id'])){
        $correspondence['girl_id'] =  $letter['user_id'];
        $correspondence['man_id']  =  $letter['to_user_id'];
      }
      else{
        $correspondence['girl_id'] =  $letter['to_user_id'];
        $correspondence['man_id']  =  $letter['user_id'];        
      }

      return $correspondence;
    }
    private static function correspondenceAddLetter($correspondences,$letter){
      //find correspondence
      $key = false;
      foreach ($correspondences as $k => $c) {
        if($c['girl_id'] == $letter['user_id'] && $c['man_id']  == $letter['to_user_id']) {$key = $k; break;}
        if($c['man_id'] == $letter['to_user_id'] && $c['girl_id']  == $letter['user_id']) {$key = $k; break;}
      }

      //Add pay
      if(isset($letter['letter_pay']['price'])){
        $correspondences[$k]['pay_count'] ++;
        $correspondences[$k]['pay_summ'] += $letter['letter_pay']['price'];
      }

      //Add letter
      unset($letter['user']);
      // array_push($correspondences[$k]['letters'],$letter);
      $correspondences[$k]['letters'] ++;

      return $correspondences;
    }
    private static function correspondenceAddUser($correspondences, $data){
      // dd($data);
      foreach ($correspondences as $k => $c) {
        foreach ($data as $d) {
          if($c['girl_id'] == $d['id'] && $c['girl'] == null){
            $correspondences[$k]['girl']['id']   = $d['girl']['user_id'];
            $correspondences[$k]['girl']['name'] = $d['girl']['name'];
            continue;
          }
          if($c['man_id'] == $d['id'] && $c['man'] == null){
            $correspondences[$k]['man']['id']   = $d['man']['user_id'];
            $correspondences[$k]['man']['name'] = $d['man']['name'];
            continue;
          }          
        }
      }


      return $correspondences;
    }




    public function setColumns($columns){

      $this->columns = $columns;

      //Admin
      if (Auth::user() &&  Auth::user()->role == 4) {
        return;
      } 

      foreach ($this->columns as $k => $c) {
        if($c['name'] == 'pay_summ'){
          unset($this->columns[$k]);
        }
      }

    }

    //Relations
    public function user()
    {
        return $this->belongsTo('App\User');
    }   

    //Relations
    public function toUser()
    {
        return $this->belongsTo('App\User');
    } 

    //Relations
    public function LetterPay()
    {
        return $this->hasOne('App\LetterPay');
    }     
}
