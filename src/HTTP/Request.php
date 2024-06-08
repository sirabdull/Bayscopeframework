<?php

namespace Bframe\HTTP;
class Request

{

    public   $method ;
    public  $uri ;

    public  static $baseurl ;
    public function __construct( )
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ;
        $this->uri = $_SERVER['REQUEST_URI'] ;

        foreach($this->all() as $key => $value){
            $this->$key = $value;
        }
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

   
    public function all() : array
    {
      return $this->method == 'POST'  ? $_POST : $_GET;
    }



     



}