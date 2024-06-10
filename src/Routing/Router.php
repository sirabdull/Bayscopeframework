<?php 

namespace Bframe\Routing;

class Router {

    public static array  $routes = [];

    public static array  $params = [];
    public function __construct()
    {
      
    }


    private static function loadRoutes()
  
      {
         $root = dirname(__DIR__,5);
         $path = $root.'/routes/router.php';
          if(file_exists($path)){
            require_once $path;
          }
          else{
            die('WE COULD NOT DETECT A ROUTE FILE');
          }
        
      }
 


    public static function dispatch()
    {


        self::loadRoutes();
        self::check();

    }

   
    private static function check() 
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
    
        if (array_key_exists($method, self::$routes))
        {
            $methodArray = self::$routes[$method];
    
            foreach ($methodArray as $path => $attributes)
            {
                [$controller] = $attributes;
                $found = false;
    
                // Convert route pattern to a regular expression
                $pattern = preg_replace('#\{[\w]+\}#', '([^/]+)', $path);
                $pattern = "#^" . $pattern . "$#";
    
                // Check if the URL matches the pattern
                if (preg_match($pattern, $url, $matches))
                {
                    array_shift($matches); // Remove the full match from the beginning
                    self::run($controller, $matches); // Pass the matches as parameters
                    $found = true;
                    break; // Exit the loop after finding a match
                }
            }
    
            if(!$found)
            {
                self::notFound();
            }
        }
        else
        {
            throw new \Exception('METHOD NOT ALLOWED');
        }
    }
    
  
  
  // Modify the run method to accept parameters
  private static function run($controller, $params = []) 
  {
      if(is_callable($controller))
      {
          echo call_user_func_array($controller, $params);
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