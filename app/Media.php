<?php

namespace App;


use Image;
use Illuminate\Support\Facades\Storage;

class Media
{

    // Zoom sizes
	protected $zoomWidth;
    protected $zoomHeight;

    // Gallerry sizes
    protected $galleryWidth;
    protected $galleryHeight;

    // Path
    protected $zoomPath;
    protected $galleryPath;
    protected $passportPath ;
    protected $videoPath;
    //archive
    protected $zoomArchivePath ;
    protected $galleryArchivePath;
    protected $passportArchivePath ;
    protected $videoArchivePath;

    //Filese
    protected $files;
    //Id
    protected $id;
    //Rollback
    protected $rollback;

    public function __construct($files, $id){
        // Zoom sizes
        $this->zoomWidth              = config('media.zoomWidth');
        $this->zoomHeight             = config('media.zoomHeight');    
        // Gallerry sizes
        $this->galleryWidth           = config('media.galleryWidth');
        $this->galleryHeight          = config('media.galleryHeight');

        // Path
        $this->zoomPath               = config('media.zoomPath');
        $this->galleryPath            = config('media.galleryPath');
        $this->passportPath           = config('media.passportPath');
        $this->videoPath              = config('media.videoPath');
        //archive
        $this->zoomArchivePath       = config('media.zoomArchivePath');
        $this->galleryArchivePath    = config('media.galleryArchivePath');
        $this->passportArchivePath   = config('media.passportArchivePath');
        $this->videoArchivePath      = config('media.videoArchivePath');   

        // Files
        $this->files                 = $files;

        // Id
        $this->id                    = $id;
        //Rollback
        $this->rollback = array();
    }


	public function savePhotos(){

        $this->resetRollback();

        $photos = $this->files;

        foreach ($photos as $k => $photo) { 

            $gallery = $this->id."_".$k.".jpg";
            $zoom    = $this->id."_".$k.".jpg";

            //Archive old Photos
            $this->archiveFile($gallery,$this->galleryPath,$this->galleryArchivePath); //Gallery
            $this->archiveFile($zoom,   $this->zoomPath,   $this->zoomArchivePath); //Zoom    
            //Get img
            $img = Image::make($photo); 
            //Resize image
            $galleryImg  = $this->resizePhoto($img, $this->galleryWidth,  $this->galleryHeight); //Gallery
            $zoomImg     = $this->resizePhoto($img, $this->zoomWidth,     $this->zoomHeight); //Zoom
            //Water mark
            $galleryImg  = $this->watermarkPhoto($galleryImg); //Gallery
            $zoomImg     = $this->watermarkPhoto($zoomImg); //Zoom              
            //Save image
            $galleryImg  = $this->saveFile($galleryImg, $this->galleryPath, $gallery); //Gallery
            $zoomImg     = $this->saveFile($zoomImg, $this->zoomPath, $zoom); //Zoom
        }
    }

    public function savePassport(){

    	$passport = $this->files[0];

        $file   = $this->id."_".rand(999999,9999999).".jpg";

        //Get img
        $img = Image::make($passport);      
        //Save image
        $this->saveFile($img, $this->passportPath, $file);
    }
		
	public function saveVideo(){
		//@@@
	}

    protected function resizePhoto($img, $width, $height){
        //Resize by width
        if($img->width() > $width)
            $img->resize($width, null,  function ($constraint){$constraint->aspectRatio();});
        //Resize by height
        if($img->height() > $height)             
            $img->resize(null, $height, function ($constraint){$constraint->aspectRatio();});

        return $img;
    }

    protected function watermarkPhoto($img){
    	//@@@
    	return $img;
    }
	
	protected function saveFile($file, $path, $name){
		//Save file
        $r = $file->save($path.$name);

        if($r){
            //Add rollback
             $this->addRollback($name, $path);
            return $r;
        }else{
            throw new Exception("Error Saving File");
            return false;
        }
            
		return false;
    }

    protected function archiveFile($file,$path,$archivePath){
        //Files
        $from   = $path.$file;
        $toFile = time()."-".$file;
        $to     = $archivePath.$toFile;

        //Move
        if(file_exists($from)){
            if($this->moveFiles($from, $to)){
                //Add rollback
                $this->addRollback($toFile, $path, $archivePath);   
                return true;     
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    protected function moveFiles($from, $to){
        //Check if file exists
        if(file_exists($from)){
            //Move files
            if(rename($from, $to)){
                return true;
            }else{
                throw new Exception("Error Archiving Old File");
                return false;
            }
        }else{
            return false;
        }
    }


    // Rollback

    public function rollback(){


        //Delete new files
        foreach ($this->rollback as $k => $v) {
            // dd($v);
            if($v['pathFrom'] == false){
                unlink($v['path'] . $v['file']);
            }
        }

        //Restore from archive
        foreach ($this->rollback as $k => $v) {
            if($v['pathFrom'] != false){
                $name = substr($v['file'],strpos($v['file'],'-')+1);
                rename($v['pathFrom'] . $v['file'], $v['path'] . $name);
            }
        }

        $this->resetRollback();
    }

    protected function addRollback($file, $path, $pathFrom = false){
        
        //Add Rollback
        return array_push($this->rollback, [ 
                'file'      => $file,
                'path'      => $path,
                'pathFrom'  => $pathFrom,
            ]);
    }

    protected function resetRollback(){
        $this->rollback = array();
        return true;
    }
} 