<?php

namespace Bframe\HTTP;

use Exception;

class Response
{
    public static $type;
    public static $status;
    public static $body;
    public static ?array $headers = [];
    
   
    public static  $config;


    public function __construct( )
    {
     
    }

  public static function getconfig(){ 
    self::$config  = require dirname(__DIR__, 5)."/config/pages.php" ;
  }



 /**
 *
 * 
 * 
 * 
 */

    protected static function send()
    {
        http_response_code(self::$status);
        foreach (self::$headers as $key => $value) {
            header($key.': '.$value);
        }
        echo self::$body; // Make sure to echo the body for HTML responses
    }





    public static function json(array $data, $code = null)
    {
        self::$type = 'application/json';
        self::$body = json_encode($data);
        self::$headers = [
            'Content-Type' => 'application/json'
        ];

        self::send();
    }

     /**
      * Renders a web page based on the provided page identifier and optional data.
      * This method first retrieves the application's page configurations. If the application
      * is configured to use the Twig templating engine, it will render the page using Twig.
      * Otherwise, it falls back to using a standard PHP templating approach. The method
      * sets the response type, prepares the body of the response by rendering the specified
      * page, sets the necessary headers, and sends the response.
      *
      * @param string $page The identifier of the page to render. This can be a path or a name
      *                     that is converted into a path within the method.
      * @param array|null $data Optional associative array of data to be passed to the page. This
      *                         data is made available to the page template when rendered.
      */
     public static function page(string $page, array $data = null)
     {
         // Retrieve the pages configurations
         self::getconfig();
     
         // Check if the application is configured to use the Twig templating engine
         if(self::$config['twig']['use'])
         {
             // Render the page using Twig
             \App\pages\Twig::class($page, $data);
         }


         else
         {
             // Convert the page identifier to a file path for PHP templating
             $page = str_replace('.', '/', $page);
     
             // Set the response type to HTML
             self::$type = 'text/html';
     
             // Render the page using PHP templating and set the response body
             self::$body = self::getpage($page, $data);
     
             // Set the Content-Type header for the response
             self::$headers = [
                 'Content-Type' => 'text/html'
             ];
     
             // Send the response
             self::send();
         }
     }

    /**
     * Renders a PHP page based on the provided path and optional data.
     * 
     * This method locates a PHP file based on the given page path, extracts the provided data
     * into variables (if any), and captures the output of the included PHP file. This output
     * is then returned as a string. If the specified file does not exist, an exception is thrown.
     * 
     * @param string $page The path to the page file, relative to the Resources/pages/php directory.
     * @param array|null $data Optional associative array of data to be extracted into variables within the page.
     * @return string The captured output of the PHP page.
     * @throws Exception If the specified page file does not exist.
     */
    protected static function getpage($page, array $data = null)
    {
        $filePath = dirname(__DIR__, 5)."/Resources/pages/php/$page.php";
        if (file_exists($filePath)) {
            // Extract $data array to variables
            if (is_array($data)) {
                extract($data);
            }
          
            ob_start();
            require $filePath;
            return ob_get_clean();
    
        } else {
            throw new Exception('REQUESTED PAGE NOT FOUND IN RESOURCES/PAGES/');
        }
    
    }

       public function redirect($url)
    {
        self::$type = 'text/html';
        self::$body = '<meta http-equiv="refresh" content="0; url='.$url.'">';
        self::$headers = [
            'Content-Type' => 'text/html'
        ];
        self::send();
    }
}