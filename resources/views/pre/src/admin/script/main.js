import * as $ from 'jquery';
import 'drag-drop';
import 'popper.js';
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
// import videojs from 'video.js';
// import 'video.js/dist/video-js.min.css';

var dragDrop = require('drag-drop');

//  EVENTS
$( document ).ready(function() {    

    const Photos = new fileUpload ('photos', 10, 'image');
    const Passport = new fileUpload ('passport', 1, 'image');
    const Video = new videoUpload ('video', 1, 'video');

    //Show listeners
    Photos.showListeners();
    Photos.showFiles();
    Passport.showListeners();
    Passport.showFiles();
    Video.showListeners();
    Video.showFiles();

    // $('#addGirl').click(function(event){
    //     // event.preventDefault();

    //     var data = $('#addGirlForm').serializeArray();

    //     console.log(data);

    // });

    $('button.rand').on('click',function(){
        rand_fiel();
    });

});

class fileUpload {

    constructor(target, maxFiles, type) {
        this.debug = true;
        this.target = target;
        this.maxFiles = maxFiles;
        this.files = [];
        this.type = type;
        this.setFilesFromInput();
        this.defaultText = this.getDefaultText();
        this.showUploadedCount();
    }   

    showListeners(){
        this.dropListener(this);
        this.chooseListener(this);
        this.removeListener(this);       
    }

    getDefaultText(){
        //
        return $(this.target + ' .text').html();
    }

    dropListener(){

        var $this = this;

        if(this.debug)console.log('listner '+ this.target);

        dragDrop('.'+this.target+' .dropArea', {
            onDrop: function (files, pos, fileList, directories) {                
                $this.uploadFile(files);
            },
            // Color drop zone border
            onDragOver: function () {     
                $('.'+$this.target+' .dropArea').css('border','solid 2px limegreen');
            },
            onDragLeave: function () {
                $('.'+$this.target+' .dropArea').css('border','dashed 2px blue');
            }
        })       
    }

    chooseListener(){
        var $this = this;
        $("." + $this.target + " .choose").change(function() { $this.uploadFile(this.files);});
    }

    removeListener(){
        var $this = this;
        $("." + $this.target + ' .preview .item').click(function(event) {
            event.preventDefault();
            $this.removeFile(this);
        })
    }

    changeText(text,status){

        //Get target
        var target = $('.'+this.target+' .text');

        //Change text
        $(target).html(text);

        //Change status
        //get old classes
        var classes = target.attr('class').split(" ");
        var newClasses = '';
        //search status class
        $.each(classes, function(i, v) {
             if(v.includes('alert-')){
                newClasses += 'alert-'+status+' '; //add new class
             }else{            
                newClasses += v + ' '; //add old class
             }
        });
        //add new classes
        target.attr('class', newClasses);
    }   

    uploadFile(files){

        if(this.debug)console.log(files);

        //Check Files
        if(!this.checkFiles(files)){
            this.changeText('file error!', 'danger');  
            if(this.debug)console.log('file error!');
            return false;
        };    

        //Check Max File Count
        if(!this.checkMaxCount(files)){
            this.changeText('No more than '+this.maxFiles+' files available!', 'danger');    
            if(this.debug)console.log('No more than '+this.maxFiles+' files available!', 'danger');
            return false;
        }

        // Check format
        if(!this.checkFormat(files)){
            if(this.debug)console.log('Bad format');
            return false;
        }

        // Max size
        var $this = this;
        $.each(files, function(k, file) {
            if(file.size > 25000000){
                $this.changeText('file to big!', 'danger');  
                return false;
            }
        });

        //Read file        
        $.each(files, function(k, file) {  
            $this.readFile(file);
        }); 

        return true;
    }

    checkFiles(files){
        var error = false;
        // Check files
        $.each(files, function(k, file) {           
            if (!file) {
                //File error 
                error = true; 
                return false;        
            }           
        });
        if(error)   return false;      
        else        return true;         
    }

    checkMaxCount(files){
        // Check enough free spots
        if(this.$count() + files.length > this.maxFiles){
            return false;
        }
        return true;        
    }

    $count(){

        var count = 0;

        if(!this.files) return 0;
        if(this.files.length == 0) return 0;

        //Count files
        for (var i = 0; i < this.maxFiles; i++) {
            if(this.files[i]){
                count++;
            }
        }

        return count;
    }

    checkFormat(files){
        // Check file format
        var $this = this;
        var error = 0;
        $.each(files, function(index, val) {
                if($this.debug)console.log('format - '+val.type+', req format - '+$this.type);
                if (!val.type.includes($this.type)) { 
                    $this.changeText('file '+ val.name +' is not a '+$this.type+'!', 'danger');         
                    error = true; 
                    return false;
                } 
        });    
        if(error)return false;
        else return true;
    }

    readFile(file){
        if(this.debug)console.log('read - '+file); 
        var reader = new FileReader();

        var $this = this;
        reader.onload = function (e) {     
            if($this.debug)console.log(e.target); 
            $this.onUpload(e.target.result);
        }

        reader.readAsDataURL(file);   
    }

    onUpload(file){
        // Add file
        this.addFile(file);

        // Show file
        this.showFiles();

        // To input
        this.saveToInput()
    }

    addFile(file){
        for (var i = 0; i < this.maxFiles; i++) {
            if(!this.files[i]){
                // Add photo to array
                this.files[i] = file;
                return true;
            }
        }
    }

    showFiles(){
        if(this.debug)console.log(this.files); 
        //Show Photos
        var $this = this;
        $.each(this.files, function(i, val) {   
            if(val){
                //Show photo     
                var item = $('.'+$this.target+' .item[data-id="' + i + '"]');
                item.attr('src', val);
                item.addClass('uploaded');
                //Show delete
                $('.'+$this.target+' .delete[data-id="' + i + '"]').css('display','block');
            }
        });

        //Show text
        this.changeText(this.defaultText, 'primary');
        //Show counts
        this.showUploadedCount();
    }

    removeFile($this){

        // Check if uploaded
        if(!$($this).hasClass('uploaded')){
            return false;
        }

        // Remove image
        var id = $($this).data("id");
        //Remove...
        $($this).attr('src', '/admin/img/preview.png'); //Show preview photo       
        $($this).removeClass('uploaded');               //Remove class
        $('.delete[data-id="' + id + '"]').css('display','none'); // remove cross

        //Edit array
        delete this.files[id];

        // To input
        this.saveToInput()

        //Show counts
        this.showUploadedCount();

        if(this.debug)console.log(this.files);
    }

    saveToInput(){
        var save = JSON.stringify(this.files);
        if(this.debug)console.log('json - '+save);
        var target = $('.'+this.target+' #'+this.target);
        target.val(save);
    }

    setFilesFromInput(){

        var val = $('.'+this.target+' #'+this.target).val();            

        if (function (val) {
            try {
                JSON.parse(val);
            } catch (e) {
                return false;
            }
            return true;
        } && val != ""){
            if(this.debug)console.log('from input val - '+val); 
            var files = JSON.parse(val);
            if(Array.isArray(files) && files.length > 0){            
                if(this.debug)console.log('from input files - '+files); 
                this.files = files;
                this.showFiles();
                return true;
            }  
        }       

        return false;
    }

    showUploadedCount(){

        var $this = this;

        $('.'+this.target + ' .uploadedCount').html($this.$count());
        $('.'+this.target + ' .maxCount').html($this.maxFiles);

    }
}

class videoUpload extends fileUpload{

    removeListener(){
        var $this = this;

        $("." + $this.target + ' .delete').click(function(event) {
            event.preventDefault();
            $this.removeFile();
        })
    }

    showFiles(){
        if(this.debug)console.log(this.files); 
        //Show Video
        var $this = this;
        $.each(this.files, function(i, val) {   
            if(val){
                //remove preview
                $('.video .preview').hide();
                //show video
                var video = $('#girlVideo');
                video.show();
                $('.video .item').attr('src',val);
                video.get(0).load();
                //show delete button
                $('.video .delete').show();            
            }
        });

        //Show text
        this.changeText(this.defaultText, 'primary');
        //Show counts
        this.showUploadedCount();
    }

    removeFile(){

        var video = $('#girlVideo');

        // Remove video
        video.hide();
        video.get(0).pause();
        $('.video .item').attr('src',"");        

        // Remove delete button
        $('.video .delete').hide();

        // Show preview
        $('.video .preview').show(); 

        //Remove file
        delete this.files[0];

        //Show counts
        this.showUploadedCount();

        // To input
        this.saveToInput();        

    }   
}


function rand_fiel(){

    //name
    let key = 'name';
    let min,max;
    let r = rand_str(8);
    $('#'+key).val(r);

    //day
    key = 'day';
    min = 1;
    max = 31;
    r = parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);

    //month
    key = 'month';
    min = 1;
    max = 12;
    r = parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);

    //year
    key = 'year';
    min = 1960;
    max = 2000;
    r = parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);

    //location
    key = 'location';
    r = rand_str(12);
    $('#'+key).val(r);    

    //Weight
    key = 'weight';
    min = 40;
    max = 120;
    r = parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);  

    //Height
    key = 'height';
    min = 150;
    max = 220;
    r = parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);

    //hair
    key = 'hair';
    min = 1;
    max = 6;
    r = parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);

    //eyes
    key = 'eyes';
    min = 1;
    max = 5;
    r = parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);

    //religion
    key = 'religion';
    min = 1;
    max = 4;
    r = parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);

    //education
    key = 'education';
    min = 1;
    max = 6;
    r = parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);

    //profession
    key = 'profession';
    r = rand_str(12);
    
    $('#'+key).val(r);

    //maritial
    key = 'maritial';
    min = 1;
    max = 3;
    r = parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);

    //children
    key = 'children';
    min = 0;
    max = 10;
    r = parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);

    //smoking
    key = 'smoking';
    min = 1;
    max = 2;
    r = parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);

    //alcohol
    key = 'alcohol';
    min = 1;
    max = 2;
    r = parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);

    //english
    key = 'english';
    min = 1;
    max = 5;
    r = parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);

    //otherLanguages
    key = 'otherLanguages';
    r = rand_str(8) +'('+rand_str(4)+'), '+rand_str(6)+'('+rand_str(4)+')';
    
    $('#'+key).val(r);

    //prefferfrom
    key = 'prefferfrom';
    min = 18;
    max = 30;
    r = parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);

    //prefferTo
    key = 'prefferTo';
    min = 31;
    max = 60;
    r = parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);

    //email
    key = 'email';
    r = rand_str(5)+'@'+rand_str(6)+'.'+rand_str(2);
    
    $('#'+key).val(r);

    //info
    key = 'info';
    r = rand_str(550);
    
    $('#'+key).val(r);

    //firstLetterSubject
    key = 'firstLetterSubject';
    r = rand_str(60);
    
    $('#'+key).val(r);

    //firstLetter
    key = 'firstLetter';
    r = rand_str(1000);
    
    $('#'+key).val(r);

    //forAdminName
    key = 'forAdminName';
    r = rand_str(15);
    
    $('#'+key).val(r);

    //forAdminSurname
    key = 'forAdminSurname';
    r = rand_str(20);
    
    $('#'+key).val(r);

    //forAdminFathersName
    key = 'forAdminFathersName';
    r = rand_str(20);
    
    $('#'+key).val(r);

    //forAdminPhoneNumber
    key = 'forAdminPhoneNumber';
    min = 111111111;
    max = 999999999;
    r = '0' + parseInt(Math.random() * (max - min) + min);
    
    $('#'+key).val(r);
}

function rand_str(count){
    let str = "";
    do{
        str += Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, count);
    }while(str.length < count);
    return str;
}