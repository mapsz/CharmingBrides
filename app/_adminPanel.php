<?php
//565689
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use Sopamo\LaravelFilepond\Filepond;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Media;
 
class _adminPanel extends Model
{	
  
    //Names
    protected $single;
    protected $multi;
    protected $page;

    //Settings
    protected $edit             = true; 
    protected $delete           = true; 
    protected $add              = true; 
    protected $link             = false;
    protected $activateSearch   = false;
    protected $route            = [];

    //Data
    protected $inputs    = [];
    protected $data      = [];
    protected $dbData    = []; 
    protected $customQueries = false;
    protected $parentId      = false;
        
    //Data settings
    protected $columns  = [];    
    protected $order    = false;
    protected $count    = false;
    protected $search   = false;
    protected $where    = false;
    protected $singleId = false;
    protected $perPage  = 50;

    public function __construct($single, $multi, $page, $inputs) {
      $this->single   = $single;
      $this->multi    = $multi;
      $this->page     = $page;
      $this->inputs   = $inputs;
      //Set inputs
      $this->setInputs($inputs);
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
    public function setWhere($where){
      $this->where = $where;
    } 
    public function setPerPage($perPage){
      $this->perPage = $perPage;
    }           
    public function setSingleId($singleId){
      $this->singleId = $singleId;
    }
    public function setDbData($dbData){
      $this->dbData = $dbData;
    }
    public function setCustomQueries($queries){
      $this->customQueries = $queries;
    }
    public function setSettings($settings){
      foreach ($settings as $k => $v) {
        $this->$k = $v;
      }
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
    public function getData($dbData = false){
        //Set data
        $this->setData($dbData);
        //Return data
        return $this->data;
    }
    public function getInputs(){
        //Return inputs
        return $this->inputs;     
    }
    public function getSettings(){

        if(count($this->dbData) < 1){
          $pages = 0;
        }else{
          $pages = $this->dbData->lastPage();
        }
        

        return [
          'add'       => $this->add,
          'edit'      => $this->edit,
          'delete'    => $this->delete,
          'link'      => $this->link,
          'search'    => $this->activateSearch,
          'pages'     => $pages,
          'subList'   => false,
        ];     
    }
    public function getEditData(){

      $data = [];
      foreach ($this->inputs as $input) {

        if(isset($input['parent'])){
          //Set with relation
          $data[$input['name']] = $this->dbData[0][$input['parent']][$input['name']];
        }elseif($input['type'] == 'file'){
          //Set files
          $data[$input['name']] = $this->getFiles($this->dbData[0]['id'],$input['name']);

          
          //Remove default file
          foreach ($data[$input['name']] as $k => $file) {
            if(isset($input['default']) && $input['default'] != false){
              $defaultFile = $input['path'] . '/'.$input['default'];
              if($defaultFile == $file)
                unset($data[$input['name']][$k]);
            }
          }
        }else{
          //Set simple
          $data[$input['name']] = $this->dbData[0][$input['name']];          
        }
      }

      $data['_id'] = $this->dbData[0]['id'];



      return $data;
    }
    public function getFiles($id, $inputName, $parentId = false){

      //Check parent
      if(!$parentId){
        foreach ($this->inputs as $input) {
          if(isset($input['parent'])){   
            $parentId = $this->with($input['parent'])->where('id',$id)->first();

            if($parentId == null){
              $parentId = $id;
            }else{
              $parentId = $parentId->toArray()[strtolower($input['parent'])]['id'];
            }
            // 

            // foreach ($this->dbData as $key => $v) {
            //   if($v['id'] == $id) {
            //     $parentId = $v[$input['parent']]['id'];
            //     break;
            //   }
            // }
            break;
          }
        }        
      }

      //Get input
      $input = "";
      foreach ($this->inputs as $v) {
        if($v['name'] == $inputName){
          $input = $v;
        }
      }
      //Get files
      $files = scandir(public_path().'/'.$input['path']);
      // Filter files
      $filtredFiles = [];
      foreach ($files as $file) {
        if($file == '.' || $file == '..') continue;


        $name = $file;

        //Get files by name
        $array = explode('.', $file);
        $extension = end($array);

        $preg_template = '/^' . $input['fileName'] . '\.'.$extension. '$/';

        if(strpos($preg_template,'`parentId`') !== false){
          $preg_template = str_replace ('`parentId`',$parentId,$preg_template);
        }

        if(strpos($preg_template,'`rand`') !== false){
          $preg_template = str_replace ('`rand`','[0-9]+',$preg_template);
        }

        if(strpos($preg_template,'`id`') !== false){
          $preg_template = str_replace ('`id`',$id,$preg_template);
        }

        // dd($preg_template);

        // dd($preg_template,$name);
        if(preg_match($preg_template,$name)){
          array_push($filtredFiles, $name);
        }

      }

      if(count($filtredFiles) == 0){
        if(isset($input['default']))
          $filtredFiles = [$input['default']];
      }
      // Get path
      $path = '';
      foreach ($this->inputs as $input) {
        if($input['name'] == $inputName){
          $path = $input['path'];
          break;
        }
      }
      //Set path
      foreach ($filtredFiles as $k => $file) {
        $filtredFiles[$k] = $path . '/' . $file;
      }
      return $filtredFiles;
    }
    public function getColumns(){
      return $this->columns;
    }



    //Data
    private function setData($dbData){
      //Set Colimns
      $columns = $this->setDataColumns($this->columns);
      $this->setColumns($columns);
      //Set up relations
      $this->setUpDefaultRelations();
      //Get from DB
      if($dbData){
        $this->dbData = $dbData;
      }else{
        $this->getDbData();  
      }      
      //Form data
      $this->data['data'] = $this->formDatafromDb($this->columns, $this->dbData);
      //Remove relations    
      $this->removeRelations();        
      //Set attributes
      $this->setDataAttributes(); 
      //Get files
      $this->setFilesFromColumns();
      //Set data columns
      $this->data['columns'] = $this->columns;
    } 
    protected function getDbData(){
        //Set getData
        $getData = $this;

        //Custom query
        if($this->customQueries)
          $getData = $this->customQueries;

        //Add relations
        $getData = $this->addDbRelations($this->columns, $getData);
       
        //Order
        if($this->order) $getData->orderBy($this->order['row'], $this->order['order']);
        //Count
        if($this->count) $getData->take($this->count);
        //Search
        if($this->search){
          dd('search');
          $quey = [];
          foreach ($this->columns as $k => $v) {
            //File
            if(isset($v['file'])){
              continue;
            }
            //Custom
            if(isset($v['component'])){
              continue;
            }     
            //Relation
            if(isset($v['relation']) && $v['relation']){
              $db = array();
              //get table
              $db['table'] = substr($v['relation'], 0, strpos($v['relation'], '.'));
              //get column
              $db['column'] = substr($v['relation'], strpos($v['relation'], '.')+1);
              //search
              $db['search'] = $this->search;
              $getData = $getData->orWhereHas($db['table'], function ($query)use($db) {
                $query->where($db['column'], 'like', '%'. $db['search'] .'%');
              });

              continue;
            }
            //remove belongs to one @@@
            if(isset($v['relationBelongsToOne'])) continue;

            //add search      
            $getData = $getData->orWhere($v['name'], 'LIKE', '%'. $this->search .'%');
          }
        }       
        //SingleId
        if($this->singleId){
          $getData = $getData->where('id', '=', $this->singleId);
        }       
        //Where
        if($this->where){
          foreach ($this->where as $w) {
            if(isset($w['or']) && $w['or']){
              $getData = $getData->orWhere($w['column'], $w['condition'], $w['value']);
            }else{
              $getData = $getData->where($w['column'], $w['condition'], $w['value']);
            }            
          }
        }


        
        
        //Set data
        $this->dbData = $getData->paginate($this->perPage);


    }
    private function setFilesFromColumns(){
      
      $files = [];
      foreach ($this->columns as $k => $v) {
        if(isset($v['file'])){
          array_push($files, $v['name']);
        }
      }

      foreach ($this->data['data'] as $k => $v) {
        foreach ($files as $file) {
          $this->data['data'][$k][$file] =  $this->getFiles($v['id'], $file);

          //set path
          // foreach ($this->data['data'][$k][$file] as $key => $value) {
          //   $path = "";
          //   foreach ($this->inputs as $input) {
          //     if($input['name'] == $file){
          //       $path = $input['path'];
          //       break;
          //     }

          //   }

          //   $this->data['data'][$k][$file][$key] = $path .'/'. $this->data['data'][$k][$file][$key];
            
          // }
        }        
      }
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
              //Simple data                   
              $val = $dbValue[$c['name']];                       
              
            }

            //Data/time
            if(isset($c['timeFormat'])){

              if(is_object($val)){
                if(isset($val->timestamp)){  
                  $val = $val->format($c['timeFormat']);
                }else{
                  if($c['timeFormat'] == 'age'){
                    $val = Carbon::parse($val)->age;
                  }else{
                    $val = Carbon::parse($val)->format($c['timeFormat']);
                  }                    
                }
              }else{
                if($val == null)
                  $val = "";
                else{
                  if($c['timeFormat'] == 'age'){
                    $val = Carbon::createFromFormat('Y-m-d',$val)->age;
                  }else{
                    $val = Carbon::parse($val)->format($c['timeFormat']);
                  }   
                }
              }
            }

            
            $d[$c['name']] = $val;          
          }

          if(isset($dbValue['id'])) $d['_id'] = $dbValue['id'];            
          array_push($formatedData, $d);

        }

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
    public function setInputs($inputs){
      //Set defaults
      $this->inputs = $this->setInputDefaults($inputs);
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
    public function saveFileFromCache($fileEnc,$path,$name,$watermark = false){
      $filepond = new Filepond();
      //Decode file path
      $chacheFilePath = $filepond->getPathFromServerId($fileEnc);
      //Get File ext
      $ext = pathinfo($chacheFilePath)['extension'];
      // jpeg to jpg
      if($ext == 'jpeg') $ext = 'jpg';

      $fullPath = public_path($path) . '/' . $name . '.' . $ext;
      //Move file
      if(\File::move($chacheFilePath, $fullPath)){
        //Watermark
        if($watermark && count($watermark) > 0){
          foreach ($watermark as $v) {
            Media::watermark($path.'/'.$name.'.'.$ext,$v['mark'],$v['pos']);
          }          
        }
        return true;
      }else
        return false;
    }
    public static function generateFileName($name,$more=false){
      if(is_array($more)){
        if(isset($more['id']))        $name = str_replace('`id`',$more['id'],$name);
        if(isset($more['parentId']))  $name = str_replace('`parentId`',$more['parentId'],$name);
        if(isset($more['i']))         $name = str_replace('`i`',$more['i'],$name);
      }      

      //Random
      $name = str_replace('`rand`',rand(10000,99999),$name);
      
      return $name;
    }
    public function deleteFile($inputName, $fileName){

      //Get path
      foreach ($this->inputs as $k => $v) {
        if($v['name'] == $inputName){
          $path = public_path().'/'.$fileName;
        }        
      }

      return file::delete($path);
    }
    public function mainFile($inputName, $fileName){

      //Get path
      foreach ($this->inputs as $k => $v) {
        if($v['name'] == $inputName){
          $path = public_path().'/'.$v['path'].'/';
          $main = $v['main'];
          $name = $v['fileName'];
        }        
      }



      //@@@
      //get id
      $id = substr($fileName,0,strpos($fileName,'_'));
      $mainFile = $path.self::generateFileName($main,['parentId' => $id]).'.jpg';
      
      //Rename old main
      if(File::isFile($mainFile)){
        $j = 0;
        do{
          $fileName2 = false;
          if($j > 100) break;;
          $fileName2 = $this->generateFileName($name,['parentId'  => $id,]);
          $j++;
        } while (File::isFile($path.'/'.$fileName2.'.jpg'));

        if(!$fileName2) return false;

        File::move(
          $mainFile,
          $path . $fileName2. '.jpg' 
        );
      }


      //rename new main
        File::move(
          $path.$fileName,
          $mainFile
        );

        return true;
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
          if($v['required']){return false;} 
        }        
        //Hash
        if(isset($v['hash']) && $v['hash'])$inputPut[$v['name']] = Hash::make($inputPut[$v['name']]);        
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
        if($this->parentId){
          $inputPut->id = $parentPut->id;
        }
        if(!$inputPut->save()){
          throw new Exception("Error Processing Request", 2);
        }

        //Save files
        foreach ($files as $k => $v) {
          $i = 0;
          if(isset($request[$v['name']]) && is_array($request[$v['name']])){
            foreach ($request[$v['name']] as $fileCache) {
              //Get filename
              if($i == 0 && isset($v['main'])){
                $fileNameTemplate = $v['main'];
              }else{
                $fileNameTemplate = $v['fileName'];
              }
              $j = 0;
              do{
                $fileName = false;
                if($j > 100) break;
                $par = 0;
                if(isset($parentPut->id)) $par = $parentPut->id;
                $fileName = $this->generateFileName($fileNameTemplate,[
                  'id'        => $inputPut->id,
                  'parentId'  => $par,
                  'i'         => $i,
                ]);
                $j++;
              } while (File::isFile(public_path($v['path']).'/'.$fileName.'.jpg'));

              if(!$fileName) throw new Exception("Error Generating name", 4);

              //Save file
              $watermark = false;
              if(isset($v['watermark'])) $watermark = $v['watermark'];
              if(!$this->saveFileFromCache($fileCache,$v['path'], $fileName,$watermark))
                throw new Exception("Error Processing Request", 3);

              $i++;
            }          
          }
        }       
       
        //Store to DB
        DB::commit();        
      } catch (Exception $e) {   
          DB::rollback();
          dd($e);
          return false;
      }

      //@@@
      if(isset($parentPut->id) && $parentPut->id > 0) return $parentPut->id;

      return $inputPut['id'];
    }
    public function editRow($request){
      //Sort inputs
      $inputs = $this->sorInputs();

      //Prepare post
      $post = new $this();
      $post = $post::find($request['id']);
      foreach ($inputs['simple'] as $k => $v) {
        //Skip confirms
        if(isset($v['confirm']) && $v['confirm']) continue;
        //Add post
        if(isset($request[$v['name']])){
          if(isset($v['hash']) && $v['hash']){
            //Add hashed
            $post[$v['name']] = Hash::make($request[$v['name']]);
          }else{
            //Add Simple
            $post[$v['name']] = $request[$v['name']];
          }
        }     
      } 

      // Multiply parents
      if(count($inputs['parent']) > 0) {
         //Prepare parent post 
        foreach ($inputs['parent'] as $k => $v) {
          //Skip confirms
          if(isset($v['confirm']) && $v['confirm']) continue;
          //Add parent post
          if(isset($request[$v['name']])){
            if(isset($v['hash']) && $v['hash']){
              //Add hashed
              $post[$v['parent']][$v['name']] = Hash::make($request[$v['name']]);
            }else{
              //Add Simple
              $post[$v['parent']][$v['name']] = $request[$v['name']];
            }
          }     
        }         
      }

      //Edit
      try {
        $put = [];
        DB::beginTransaction();

        //Edit parents
        $parentId = false;
        foreach ($inputs['parent'] as $k => $v) {          
          if(!$post[$v['parent']]->save()){
            throw new Exception("Error Processing Request", 1);
          }else{ // Multi parent !!@@@??
            $parentId =  $post[$v['parent']]->id; 
            break;
          }
        }

        //Edit simple
        $inputId = false;
        if(!$post->save()){
          throw new Exception("Error Processing Request", 2);
        }else{
          $inputId =  $post->id; 
        }

        //Save files
        foreach ($inputs['files'] as $k => $v) {
          $i = 0;
          if(isset($request[$v['name']]) && is_array($request[$v['name']])){
            foreach ($request[$v['name']] as $fileCache) {
              //Get filename
              $j = 0;
              do{
                $fileName = false;
                if($j > 100) break;;
                $fileName = $this->generateFileName($v['fileName'],[
                  'id'        => $inputId,
                  'parentId'  => $parentId,
                  'i'         => $i,
                ]);
                $j++;
              } while (File::isFile(public_path($v['path']).'/'.$fileName.'.jpg'));

              if(!$fileName) throw new Exception("Error Generating name", 4);

              //Save file
              $watermark = false;
              if(isset($v['watermark'])) $watermark = $v['watermark'];
              if(!$this->saveFileFromCache($fileCache,$v['path'], $fileName,$watermark))
                throw new Exception("Error Processing Request", 3);

              $i++;
            }          
          }
        }    
       
        //Store to DB
        DB::commit();        
      } catch (Exception $e) {   
          DB::rollback();
          //@@ delete photos
          dd($e);
          return false;
      }

      return true;
    }
    private function sorInputs(){
      $inputs = ['simple' => [],'parent' => [],'files' => []];
      foreach ($this->inputs as $key => $v) {
        //Get parent fields
        if(isset($v['parent']) && $v['parent']){
          array_push($inputs['parent'], $v);
          continue;
        }
        //Get file fields
        if($v['type'] == 'file'){
          array_push($inputs['files'], $v);
          continue;
        }
        //Input fields
        array_push($inputs['simple'], $v);
      }

      return $inputs;
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
        $parents[$k]['id'] = $row[strtolower($v['model']).'_id'];
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
