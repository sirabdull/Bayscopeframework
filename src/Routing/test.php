<?php 

use Bframe\Routing\Router;

require __DIR__.'/../vendor/autoload.php';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['REQUEST_METHOD'] = 'GET';


 Router::get('/home', 'hello');
 Router::get('/', function(){
    return 'hello';
 });

 Router::dispatch();