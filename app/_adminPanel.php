<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use Sopamo\LaravelFilepond\Filepond;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;

class _adminPanel extends Model
{	
    //Names
    protected $single;
    protected $multi;
    protected $page;

    //Settings
    protected $edit      = true; 
    protected $delete    = true; 
    protected $add       = true; 
    protected $link      = false;
    protected $route     = [];

    //Data
    protected $inputs    = [];
    protected $data      = [];
    protected $dbData    = [];    
        
    //Data settings
    protected $columns  = [];    
    protected $order    = false;
    protected $count    = false;
    protected $search   = false;
    protected $where    = false;
    protected $singleId = false;

    public function __construct($single, $multi, $page, $inputs) {
        $this->single   = $single;
        $this->multi    = $multi;
        $this->page     = $page;
        $this->inputs   = $inputs;
        //Set inputs
        $this->setInputs();
    }

    //Validate
    public function validate($request){
        //Some valdiate
        return true;
    }
    public function attachValidate($request){
        //Some valdiate
        return ['error' => false];      
    }

    //Setters
    public function setColumns($columns){
      $this->columns = $columns;
    }
    public function setOrder($order){
      $this->order = $order;
    }
    public function setCount($count){
      $this->count = $count;
    }
    public function setSearch($search){
      $this->search = $search;
    }
    public function setSingleId($singleId){
      $this->singleId = $singleId;
    }

    //Getters
    public function getNames(){
        //Get names
        return ['s'=>$this->single,'m'=>$this->multi] ;
    }
    public function getRoute(){
        if(!isset($this->route)){
          $this->route['prefix'] = '';
          $this->route['r']      = $this->single;
        }
        if(!isset($this->route['prefix'])){
          $this->route['prefix'] = '';
        }
        if(!isset($this->route['r'])){
          $this->route['r']      = $this->single;
        }                
        //Get route
        return $this->route;     
    }    
    public function getPage(){
        if($this->page == "") $this->page = '_adminPanel.list';
        //Return page
        return $this->page;             
    }
    public function getData(){      
        //Set data
        $this->setData();
        //Return data
        return $this->data;
    }
    public function getInputs(){
        //Return inputs
        return $this->inputs;     

    }
    public function getSettings(){
        return [
          'add'       => $this->add,
          'edit'      => $this->edit,
          'delete'    => $this->delete,
          'link'      => $this->link,
          'subList'   => false,
        ];     
    }

    //Data
    private function setData(){      
        //Set Colimns
        $columns = $this->setDataColumns($this->columns);
        $this->setColumns($columns);
        //Set up relations
        $this->setUpDefaultRelations();
        //Get from DB
        $this->getDbData();        
        //Form data
        $this->data['data'] = $this->formDatafromDb($this->columns, $this->dbData);
        //Remove relations        
        $this->removeRelations();
        //Set attributes
        $this->setDataAttributes(); 
        //Set data
        $this->data['columns'] = $this->columns;
    } 
    protected function getDbData(){
        //Set getData
        $getData = $this;
        //Add relations
        $getData = $this->addDbRelations($this->columns, $getData);
        //Order
        if($this->order) $getData->orderBy($this->order['row'], $this->order['order']);
        //Count
        if($this->count) $getData->take($this->count);
        //Search
        if($this->search){
          foreach ($this->columns as $k => $v) {
            //@@@ не ищет по связям
            //remove belongs to one @@@
            if(isset($v['relationBelongsToOne'])) continue;
            //add search
            $getData->orWhere($v['name'], 'LIKE', '%'. $this->search .'%');
          }
        }
        //SingleId
        if($this->singleId){
          $getData->Where('id', '=', $this->singleId);
        }
        //Where
        if($this->where){
          foreach ($this->where as $w) {
            $getData->Where($w['column'], $w['condition'], $w['value']);
          }
        }
        //Set data
        $this->dbData = $getData->get();
    }
    private function addDbRelations($columns, $getData, $listRelation = false){

        if($listRelation) $list = [];

        foreach ($columns as $v) {   
          //Relation one
          if(isset($v['relation'])){ 
            if(gettype ($v['relation']) == 'string'){
              //Get table
              $table =  substr($v['relation'], 0, strpos($v['relation'], '.'));
              if(!$listRelation){
                //Add relation
                $getData = $getData->with($table);
              }else{                
                //Add list              
                array_push($list, $listRelation.'.'.$table);
              }
            }    
          }  
          //Relation Belong to One
          elseif(isset($v['relationBelongsToOne'])){ 
            //Get table
            $table = substr($v['relationBelongsToOne'], 0, strpos($v['relationBelongsToOne'], '.'));
            if(!$listRelation){
              //Add relation
              $getData = $getData->with($table);
            }else{                
              //Add list              
              array_push($list, $listRelation.'.'.$table);
            }
          }
          //Relation Has Many
          if(isset($v['relationMany'])){ 
            if(isset($v['list'])){
              $listRelations = $this->addDbRelations($v['list'], $getData,$v['relationMany']);
              $getData = $getData->with($listRelations);
            }
          }    
        }      

        // dd($getData);

        if($listRelation)  return $list; else return $getData;
    }
    private function setUpDefaultRelations(){
        //Set relations
        foreach ($this->columns as $k => $v) {
            // Not pre setup
            if(!isset($v['relation']) && !isset($v['relationBelongsToOne'])){
              if(strpos($v['name'],'_id') > 0){     
                    //Set relation
                    $relationTable = str_replace('_', "", str_replace('_id', '', $this->columns[$k]['name']));
                    $this->columns[$k]['relation'] = $relationTable . '.name';
                    $this->columns[$k]['caption'] = str_replace(' id', '', $this->columns[$k]['caption']);
                }                
            }
        }      
    }
    private function setDataColumns($columns,$db = true){

        //Already set
        if(count($columns) > 0){
            $fColumns = [];
            $fColumn = [];
            foreach ($columns as $k => $v) {
              $fColumn = $v;
              //Set up list
              if(isset($fColumn['list'])){
                $fColumn['list'] = $this->setDataColumns($fColumn['list'], false);
              }

              //Set caption
              if(!isset($fColumn['caption'])){
                $fColumn['caption'] = $fColumn['name'];
              }  
              
              array_push($fColumns, $fColumn);
            }

            return $fColumns;
        }

        //Set Captions from data     
        if($db){   
          $_columns = Schema::getColumnListing($this->getTable());  
          $columns = [];
          foreach ($_columns as $value) { 
              $k = str_replace('_',' ',$value);
              array_push($columns, ['caption' => $k, 'name' => $value]);
          }
        }

        return $columns;

        // return $data;
    }
    private function formDatafromDb($columns, $dbData){

        $formatedData = [];

        foreach ($dbData as $dbValue) {
            $d = [];
            foreach ($columns as $k => $c) {
                //Relation One
                if (isset($c['relation'])) {
                  if(gettype ($c['relation']) == 'string'){
                    //Has one              
                    $column = substr($c['relation'], 0, strpos($c['relation'], '.'));
                    $name =  substr($c['relation'], strpos($c['relation'], '.') + 1);

                    $val = $dbValue[$column][$name];
                  }
                }
                //Relation Belongs to one
                elseif(isset($c['relationBelongsToOne'])){ 

                  $column = substr($c['relationBelongsToOne'], 0, strpos($c['relationBelongsToOne'], '.'));
                  $name =  substr($c['relationBelongsToOne'], strpos($c['relationBelongsToOne'], '.') + 1);

                  if(isset($dbValue[$column][0])){
                    $val = $dbValue[$column][0][$name];
                  }else{
                    $val = "";
                  }

                }             
                //Relation Many
                elseif(isset($c['relationMany'])){
                  if(isset($c['list'])){     
                    //Has many                 
                    $val = $this->formDatafromDb($c['list'],$dbValue[$c['relationMany']]);
                  }    

                }
                //No Relations
                else{               
                  //Data/time
                  if(isset($c['timeFormat'])){
                    if(is_object($dbValue[$c['name']])){
                      if(isset($dbValue[$c['name']]->timestamp)){  
                        $val = $dbValue[$c['name']]->format($c['timeFormat']);
                      }else{
                        $val = Carbon::createFromFormat('Y-m-d',$dbValue[$c['name']])->format($c['timeFormat']);
                      }
                    }else{
                      if($dbValue[$c['name']] == null)
                        $val = "";
                      else
                        $val = Carbon::createFromFormat('Y-m-d',$dbValue[$c['name']])->format($c['timeFormat']);
                    }
                  }
                  //Simple data
                  else{                     
                    $val = $dbValue[$c['name']];                       
                  }
                }

                $d[$c['name']] = $val;              
            }
            
            array_push($formatedData, $d);

        }

        // dd($formatedData);

        return $formatedData;
    }
    private function removeRelations(){
        foreach ($this->columns as $k => $c) {
            if(isset($c['relation'])){
                unset($this->columns[$k]['relation']);
            }
        }      
    }
    private function setDataAttributes(){
      foreach ($this->columns as $cK => $cV) {
        if(isset($cV['attributes'])){
          foreach ($this->data['data'] as $dK => $dV) {
              foreach ($cV['attributes'] as $aK => $aV) {

                if($aV['id'] == $dV[$cV['name']]){
                  $this->data['data'] [$dK] [$cV['name']] = $aV['name'];
                  continue;
                }
              }
          }  
        }          
      }
    }

    //Inputs
    private function setInputs(){
      //Set defaults
      $this->inputs = $this->setInputDefaults($this->inputs);
      //Set attributes
      $this->setInputAttributes();
    }
    private function setInputDefaults($inputs){
      foreach ($inputs as $key => $input) {
        //Caption defaults
        if(!isset($input['caption'])){
          $inputs[$key]['caption'] = $inputs[$key]['name'];
        }
        //Type defaults
        if(!isset($input['type'])){
          $inputs[$key]['type'] = "text";
        }        
        //example defaults
        if(!isset($input['example'])){
          $inputs[$key]['example'] = "";
        }
        //required defaults
        if(!isset($input['required'])){
          $inputs[$key]['required'] = true;
        }
        //filename defaults       
        if($input['type'] == 'file' && !isset($input['fileName'])){
          $inputs[$key]['fileName'] = '`id`_`i`';    
        }
        //password defaults
        if($input['type'] == 'password'){
          //hash
          if(!isset($input['hash']))
            $inputs[$key]['hash'] = true;
        }
        //textarea defaults
        if($input['type'] == 'textarea'){
          //hash
          if(!isset($input['row']))
            $inputs[$key]['row'] = 3;
        }        
      }
      return $inputs;
    }
    private function setInputAttributes(){
      foreach ($this->inputs as $k => $input) {
        //Attributes exists
        if(isset($input['attributes'])){ 

          //Attributes from model
          if(!is_array($input['attributes'])){                    
            //Set Attributes
            if(is_string($input['attributes'])){
              
              $attributes = [];

              // Get attributes
              $_attributes = $input['attributes']::all()->toArray();

              //Delete bad data
              foreach ($_attributes as $value) {
                  $key = isset($input['attributeKey']) ? $input['attributeKey'] : 'name';

                  array_push($attributes, ['id' => $value['id'], 'name' => $value[$key]]);
              }

              //Set new attributes
              $this->inputs[$k]['attributes'] = $attributes;
            } 
          }
          //Add null
          if(isset($input['nullable']) && $input['nullable']){
              array_unshift ($this->inputs[$k]['attributes'], ['id' => '', 'name' => 'none']);
          }          
          //Add defaults and more
          foreach ($this->inputs[$k]['attributes'] as $key => $value) {
            //Caption default
            if(!isset($value['caption'])) {
                $this->inputs[$k]['attributes'][$key]['caption'] = $this->inputs[$k]['attributes'][$key]['name'];
            }           
          }
        }
      }
    }

    //Files
    public static function cacheFile($file){
      //     
      $filepond = new Filepond();
      $tempPath = config('filepond.temporary_files_path');      
      $filePath = tempnam($tempPath, 'laravel-filepond');
      $filePath .= '.' . $file->extension();      
      $filePathParts = pathinfo($filePath);
      if (!$file->move($filePathParts['dirname'], $filePathParts['basename'])) {
          return false;
      }
      $serverEnc = $filepond->getServerIdFromPath($filePath);

      return $serverEnc;
    }
    public static function saveFileFromCache($fileEnc,$path,$name){
      $filepond = new Filepond();

      //Decode file path
      $chacheFilePath = $filepond->getPathFromServerId($fileEnc);
      //Get File ext
      $ext = pathinfo($chacheFilePath)['extension'];
      // jpeg to jpg
      if($ext == 'jpeg') $ext = 'jpg';
      //Move file
      if(\File::move($chacheFilePath, $path . '/' . $name . '.' . $ext))
        return true;
      else
        return false;
    }
    public static function generateFileName($name,$more=false){
      if(is_array($more)){
        if(isset($more['id']))        $name = str_replace('`id`',$more['id'],$name);
        if(isset($more['parentId']))  $name = str_replace('`parentId`',$more['parentId'],$name);
        if(isset($more['i']))         $name = str_replace('`i`',$more['i'],$name);
      }

      return $name;
    }

    //CRUD
    public function saveRow($request){

      //Sort inputs
      $parent = [];$files  = [];$inputs = [];
      foreach ($this->inputs as $key => $v) {
        //Get parent fields
        if(isset($v['parent']) && $v['parent']){
          array_push($parent, $v);
          continue;
        }
        //Get file fields
        if($v['type'] == 'file'){
          array_push($files, $v);
          continue;
        }
        //Input fields
        array_push($inputs, $v);
      }

      //Prepare parents
      if(count($parent) > 0){
        $parentModel = 'App\\'.$parent[0]['parent']; //@@@ [0]
        $parentPut = new $parentModel();  
        foreach ($parent as $k => $v) {
          //Skip confirms
          if(isset($v['confirm']) && $v['confirm']) continue;
          //Add input
          $parentPut[$v['name']] = $request[$v['name']];
          //Hash
          if(isset($v['hash']) && $v['hash'])$parentPut[$v['name']] = Hash::make($parentPut[$v['name']]);
        }
      }
      //Prepare inputs
      $inputPut = new $this();
      foreach ($inputs as $k => $v) {
        //Skip confirms
        if(isset($v['confirm']) && $v['confirm']) continue;
        //Add input
        if(isset($request[$v['name']])){
          $inputPut[$v['name']] = $request[$v['name']];          
        }else{
          if($v['required']) return false;
        }
        
        //Hash
        if(isset($v['hash']) && $v['hash'])$parentPut[$v['name']] = Hash::make($parentPut[$v['name']]);        
      } 

      //Save
      try {
        $put = [];
        DB::beginTransaction();
        //Put Parents
        if(count($parent) > 0){
          //Save parent
          if(!$parentPut->save()){
            throw new Exception("Error Processing Request", 1);
          }
          //Add parent id
          $inputPut[$parent[0]['parent'].'_id'] = $parentPut->id;  //@@@ [0]
        }
        //Put Input        
        if(!$inputPut->save()){
          throw new Exception("Error Processing Request", 2);
        }

        //Save files
        foreach ($files as $k => $v) {
          $i = 0;
          if(is_array($request[$v['name']])){
            foreach ($request[$v['name']] as $fileCache) {
              if(!$this->saveFileFromCache(
                  $fileCache,
                  public_path($v['path']),
                  $this->generateFileName($v['fileName'],[
                    'id'        => $inputPut->id, 
                    'parentId'  => $parentPut->id,
                    'i'         => $i,
                  ])
                ))
              {
                throw new Exception("Error Processing Request", 3);
              }                
              $i++;
            }          
          }
        }       
       
        //Store to DB
        DB::commit();        
      } catch (Exception $e) {   
          DB::rollback();
          // dd($e);
          return false;
      }

      return $inputPut['id'];
    }
    public function deleteRow($id){

      //Get row
      $row = $this->find($id);

      //Get parent
      $parents =[];
      //get parent models
      foreach ($this->inputs as $v) {
        if(isset($v['parent'])){
          array_push($parents, ['model' => $v['parent'], 'id' => false]);
        }
      }
      //get parent ids
      foreach ($parents as $k => $v) {
        $parents[$k]['id'] = $row[$v['model'].'_id'];
      }
      //get parent rows
      $parentRows = [];
      foreach ($parents as $v) {
        $model = 'App\\'.$v['model'];
        $model = new $model();
        $parentRow = $model->find($v['id']);
        array_push($parentRows,$parentRow);
      }

      try {
        DB::beginTransaction();         

        //Delete row        
        if(!$row->delete()){
          throw new Exception("Error Processing Request", 1);
        }

        //Delete parents
        foreach ($parentRows as $v) {
          if(!$v->delete()){
            throw new Exception("Error Processing Request", 1);
          }
        }

        DB::commit();     
      } catch (Exception $e) { 
        DB::rollback();
        // dd($e);
        return false;       
      }

      return true;
    }

}
