<?php
namespace Bframe\HTTP;
 /**
  * 
  * THIS IS A TEST CLASS FOR COMPLILING ASSETS
 * STILL UNDERGOING POSSIBLE USE CASE AND 
 * THE AIM IS TO COMPILE  STATIC ASSETS WITHOUT ANY NPM PACKAGES E.G webpac or laravel-mix
  */
 
 

class compiler {
    protected static $assetBasePath;

    public static $assets = [];
    public static $assetfiles = [];

    public function __construct($assetBasePath)
    {
        $this->assetBasePath = $assetBasePath;
    }


    public static function compile()
    {

        self::fetchAssets();
        self::compileAssets();
        self::loadAssets();


    }

    protected static function fetchAssets()
    {
       self::$assetBasePath = dirname(__DIR__,5).'/assets';

        $files = scandir(self::$assetBasePath);
        foreach ($files as $file) {
            if($file!= '.' && $file!= '..'){
                self::$assets[] = self::$assetBasePath.'/'.$file;
            }
        }
     

    }

    protected static function compileAssets()
    {
        foreach (self::$assets as $asset) {
            if(is_dir($asset)){
               $files = scandir($asset);
                foreach ($files as $file) {
                    if($file!= '.' && $file!= '..'){
                        self::$assetfiles[] = $asset.'/'.$file;
                    }
                }


            }else{
            
               
               die('AN ASSET COULD NOT BE COMPILED');
            }
        }
    }


    public static function loadAssets()
     {

    foreach(self::$assetfiles as $file){
       if(strpos(  $file, '.css')){
      copy($file, dirname(__DIR__, 5).'/public/css');
       }
   
      if(strpos( $file, '.js')){
        copy($file,  dirname(__DIR__, 5).'/public/js');
    }
      
    }

  }



} #}