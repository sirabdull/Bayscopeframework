<?php 

namespace Bframe\Routing;
use Bframe\HTTP\Request ;
use App\Middleware\HelloMidleware;

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
                [$controller, $middleware] = $attributes;
                $found = false;
                
                
                // Convert route pattern to a regular expression
                $pattern = preg_replace('#\{[\w]+\}#', '([^/]+)', $path);
                $pattern = "#^" . $pattern . "$#";
    
                // Check if the URL matches the pattern
                if (preg_match($pattern, $url, $matches))
                {
                    array_shift($matches); // Remove the full match from the beginning
                    self::run($controller, $matches, $middleware); // Pass the matches as parameters
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
  private static function run($controller, $params = [], $middleware) 
  {
     if(is_array($middleware) && count($middleware) > 0)
     {

        $request = new Request();

        foreach($middleware as $m){
        
            if(class_exists($m)){
              $middlewareReturn =  $m::handle($request);

              if($middlewareReturn !== true){
               return ;
              }

            }
            else{
               throw new \Exception($m .' is not a valid middleware');
            }
        }
     }

      if(is_callable($controller))
      {
       call_user_func_array($controller, $params);
      }
      else if(is_string($controller)) {
          echo $controller;
      }
  }

   public static function get($path,$callback, $middleware = [])
   
   {
    self::$routes['GET'][$path] = [$callback, $middleware];
    return self::class;
   }

   public static function post($path,$callback, $middleware = [])
   
   {
    self::$routes['POST'][$path] = [$callback, $middleware];
    return self::class;
   }


   public  static function notFound()
   {
      die('404  Requested ROUTE NOT FOUND ON THIS SERVER');
      
   }

  


}