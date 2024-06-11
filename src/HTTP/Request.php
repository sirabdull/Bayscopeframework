<?php

namespace Bframe\HTTP;
class Request

{

    public  static  $method ;
    public  static $uri ;

    public  array $header;

    public static $cookie = [];


    public  static $baseurl ;
    public function __construct( )
    {
        static::$method = $_SERVER['REQUEST_METHOD'] ;
       static::$uri = $_SERVER['REQUEST_URI'] ;

        foreach(self::all() as $key => $value){
           $this->$key = $value;
        }

    }



    public static function capture() {
     
    } 

    public  function method(){
        return $this->method;
    }

    public  function uri(){
        return $this->uri;
    }
    public static function baseurl() : Request
    {
        return self::$baseurl;
    }

   
    public  function all() : array
    {
      return self::$method == 'POST'  ? $_POST : $_GET;
    }


   public function header($header) 
    {
        return $_SERVER[$header];
    }
     



}