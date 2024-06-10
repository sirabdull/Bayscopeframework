<?php

namespace Bframe\Page\Twig;

use Bframe\HTTP\Response;
use Exception;
use Twig\Environment;
use Twig\TwigFunction;

class Twigloader extends Response
{


/**
 * Initializes and renders a Twig template with the provided data.
 * 
 * This method sets up the Twig environment using a filesystem loader pointing to the directory
 * where Twig templates are stored. It then attempts to render a specified template with the given data.
 * If successful, the rendered template is set as the response body, and the response is sent with
 * an appropriate content type header. Exceptions are caught and rethrown as generic exceptions
 * with a message indicating the nature of the error (template not found, runtime error, or syntax error).
 * 
 * @param string $page The name of the Twig template to render (without the '.html.twig' extension).
 * @param array $data An associative array of data to be passed to the Twig template.
 * @throws Exception If the template cannot be found, or if there is a runtime or syntax error in the template.
 * 
 *
 */


 public static $twig;

protected static function boot()
{
    $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__, 6).'/Resources/pages/twig');
    self::$twig = new Environment($loader, [

        'cache' => parent::$config['twig']['cache'],
        'strict_variables' => parent::$config['twig']['strict_variables'],
        'debug' => parent::$config['twig']['debug']

    ]);

    // self::addFunction('asset',  function ($assetPath) {
    //     // Assuming your assets are in a public directory accessible at the root
    //     return '/assets/' . ltrim($assetPath, '/');
    //  });

    
     

 
}



public static function addFunction($name, $callable)
{
    $twigFunction = new TwigFunction($name, $callable);
    self::$twig->addFunction($twigFunction);
}





protected static function render($page, $data)
{ 
    try {
        parent::$body = self::$twig->render($page . '.html.twig', $data);
    } catch (\Twig\Error\LoaderError $e) {
        throw new Exception('Template not found: ' . $e->getMessage());
    } catch (\Twig\Error\RuntimeError $e) {
        throw new Exception('Runtime error in template: ' . $e->getMessage());
    } catch (\Twig\Error\SyntaxError $e) {
        throw new Exception('Syntax error in template: ' . $e->getMessage());
    }
    parent::$headers = [
        'Content-Type' => 'text/html'
    ];

    parent::send();
    return;
}








}