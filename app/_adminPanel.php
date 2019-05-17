<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class _adminPanel extends Model
{	
    protected $single;
    protected $multi;
    protected $page;

    protected $edit      = true; 
    protected $delete    = true; 
    protected $add       = true; 

    protected $inputs = [];
    protected $data = [];
    protected $dbData = [];    
    protected $columns = [];    

    public function __construct($single, $multi, $page, $inputs) {
        $this->single   = $single;
        $this->multi    = $multi;
        $this->page     = $page;
        $this->inputs   = $inputs;
    }

    //Validate
    public function validate($request){

        //Some valdiate

        return true;
    }

    //Gets
    public function getNames(){
        //Get names
        return ['s'=>$this->single,'m'=>$this->multi] ;
    }
    public function getRoute(){
        //Get route
        return $this->route;     
    }    
    public function getPage(){
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
        //Set inputs
        $this->setInputs();
        //Set attributes
        $this->setInputAttributes();

        //Return inputs
        return $this->inputs;     
    }
    public function getSettings(){
        return [
          'add'     => $this->add,
          'edit'    => $this->edit,
          'delete'   => $this->delete,
        ];     
    }

    //Data
    private function setData(){
        //Set Colimns
        $this->setDataColumns();
        //Set up relations
        $this->setUpRelations();
        //Get from DB
        $this->getDbData();
        //Form data
        $this->formDatafromDb();
        //Remove relations        
        $this->removeRelations();
        //Set attributes
        $this->setDataAttributes();
   

       
        //Set data
        $this->data['columns'] = $this->columns;
    } 
    private function getDbData(){
        //Add relations
        $getData = $this;
        foreach ($this->columns as $v) {
            if(isset($v['relation']) && gettype ($v['relation']) == 'string'){
                //Set up relation
                $relation =  substr($v['relation'], 0, strpos($v['relation'], '.'));
                //Add relation
                $getData = $getData->with($relation);
            }
        }

        //Set data
        $this->dbData = $getData->get();
    }
    private function setUpRelations(){
        //Set relations
        foreach ($this->columns as $k => $v) {
            // Not pre setup
            if(!isset($v['relation'])){
              if(strpos($v['name'],'_id') > 0){     
                    //Set relation
                    $relationTable = str_replace('_', "", str_replace('_id', '', $this->columns[$k]['name']));
                    $this->columns[$k]['relation'] = $relationTable . '.name';
                    $this->columns[$k]['caption'] = str_replace(' id', '', $this->columns[$k]['caption']);
                }                
            }
        }      
    }
    private function setDataColumns(){
        //Already set
        if(count($this->columns) > 0){
            //Set captions
            foreach ($this->columns as $k => $v) { 
                if(!isset($v['caption']))
                $this->columns[$k]['caption'] = $v['name'];
            }
            return true;
        }

        //Set Captions from data        
        $_columns = Schema::getColumnListing($this->getTable());  
        $columns = [];
        foreach ($_columns as $value) { 
            $k = str_replace('_',' ',$value);
            array_push($columns, ['caption' => $k, 'name' => $value]);
        }

        $this->columns = $columns;

        // return $data;
    }
    private function formDatafromDb(){
        $data = [];
        foreach ($this->dbData as $dbValue) {
            $d = [];
            foreach ($this->columns as $k => $c) {
                if(isset($c['relation']) && $c['relation']){                    
                    //Relation                    
                    $column = substr($c['relation'], 0, strpos($c['relation'], '.'));
                    $name =  substr($c['relation'], strpos($c['relation'], '.') + 1);
                    $val = $dbValue[$column][$name];
                }else{
                    //No relation
                    $val = $dbValue[$c['name']];  
                }

                $d[$c['name']] = $val;              
            }
            
            array_push($data, $d);
        }

        $this->data['data'] = $data;
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
        //Set captions
        foreach ($this->inputs as $key => $input) {
            if(!isset($input['caption'])){
                $this->inputs[$key]['caption'] = $this->inputs[$key]['name'];
            }
        }
    }
    private function setInputAttributes(){
        foreach ($this->inputs as $k => $input) {
            if(isset($input['attributes'])){ //Attributes exists

                // Not set
                if(!is_array($input['attributes'])){                    
                    //Set Attributes
                    if(is_string($input['attributes'])){   


                        $attributes = [];

                        //Add null
                        if(isset($input['nullable']) && $input['nullable']){
                            array_push($attributes, ['id' => '', 'name' => 'none']);
                        }

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

                //Add caption
                foreach ($this->inputs[$k]['attributes'] as $key => $value) {
                    if(!isset($value['caption'])) {
                        $this->inputs[$k]['attributes'][$key]['caption'] = $this->inputs[$k]['attributes'][$key]['name'];
                    }
                }

            }
        }
    }

}
