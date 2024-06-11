<?php

namespace Bframe\HTTP;

interface MiddlewareInterface {
    public static function handle(Request $request);
   
}

class Middleware  implements MiddlewareInterface

{

    public static function handle(Request $request)
    {
        // Perform some action before the request is processed
       
        // Perform some action after the request is processed
        return 'hello';
    }





    
}