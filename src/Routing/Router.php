<?php 

namespace Bframe\Routing;

class Router {

    public static array  $routes = [];

    public static array  $params = [];
    public function __construct()
    {
      
    }


    public static function dispatch()
    {
        self::check();
       // self::run();

    }

   
    private static function check() 
     {
         $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
         $method = $_SERVER['REQUEST_METHOD'];

         if (array_key_exists( $method,   self::$routes))
          {

            $methodArray = self::$routes[$method];

        
            foreach ($methodArray as $path => $attributes)
           
             {
                [$controller] = $attributes;
              
                $found = false;

                if ($path == $url)
                    {
                        self::run($controller);
                        $found  = true;
                    }
            }

            if(!$found){

                    self::notFound();
              }

         }
         else{
            throw new \Exception('MEHTOD NOT ALLOWED');
         }
        
     }

    
   private static function run($controller) 
   
   {
        
         if(is_callable($controller))
          {
            call_user_func($controller);
          }

         else if(is_string($controller)) {
             echo $controller;
         }

   }



   public static function get($path,$callback)
   
   {
    self::$routes['GET'][$path] = [$callback];
    return self::class;
   }

   public static function post($path,$callback)
   
   {
    self::$routes['POST'][$path] = [$callback];
    return self::class;
   }


   public  static function notFound()
   {
      die('404  Requested ROUTE NOT FOUND ON THIS SERVER');
      
   }

}