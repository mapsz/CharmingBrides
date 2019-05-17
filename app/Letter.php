<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Letter extends _adminPanel
{

    public $guarded = [];

    //Data
    protected $route     = 'admin/letter';
    protected $single    = 'letter';
    protected $multi     = 'letters';
    
    protected $edit      = false; 
    protected $delete    = false; 
    protected $add       = false; 

    protected $columns  = [
        ['name' => 'id'],
        ['name' => 'user_id','caption' => 'from', 'relation' => 'user.id'],
        ['name' => 'to_user_id','caption' => 'to', 'relation' => 'toUser.id'],
        ['name' => 'subject'],
        ['name' => 'body'],
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


    public static function getUserLetters($userId){

        $letters = self::with('user.man','user.girl','toUser.man', 'toUser.girl')
                      ->where('user_id','=',$userId)
                      ->orWhere('to_user_id','=',$userId)
                      ->get()
                      ->toArray();

        //Set payed Letters
        if(User::getWithInfo($userId)['man']){ //@@@ mb не надо дб запрос
          foreach ($letters as $k => $letter){
            $letters[$k]['payed'] = true;
            if($letter['to_user_id'] == $userId){
              //Check payed
              if(true){
                $letters[$k]['payed'] = false;
              }
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
          // dd(isset($letter['payed']) , $letter['payed'] == false);
          if(isset($letter['payed']) && $letter['payed'] == false){
            $preLetter['body'] = "";
            $preLetter['payed'] = false;
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
}
