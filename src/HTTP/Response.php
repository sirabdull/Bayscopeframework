<?php

namespace Bframe\HTTP;

class Response

{

public  static $type;

public static $status;

public static $body;

public static ?array $headers = [];

public function __construct()
  {
      
  }

    protected static  function send()
    {
        http_response_code(self::$status);
        foreach (self::$headers as $key => $value) {
            header($key.': '.$value);
        }
        echo self::$body;
    }

    public static function  json( array $data, $code = null) 
    {
        self::$type = 'application/json';
        self::$body = json_encode($data);
        self::$headers = [
            'Content-Type' => 'application/json'
        ];

      self::send();
    }

    public static function page( string $page, array $data = null)
    {
       $page  = str_replace('.', '/', $page);

        self::$type = 'text/html';
        self::$body = require __DIR__ .'../../views/'.$page;
        self::$headers = [
            'Content-Type' => 'text/html'
        ];

        self::send();

        return Self::class;
    }

    public function redirect($url)
    {
        $this->type = 'text/html';
        $this->body = '<meta http-equiv="refresh" content="0; url='.$url.'">';
        $this->headers = [
            'Content-Type' => 'text/html'
        ];
    }

   


}