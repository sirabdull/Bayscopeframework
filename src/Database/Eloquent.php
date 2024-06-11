<?php

namespace Bframe\Database;
use Illuminate\Database\Capsule\Manager as Capsule;


class Eloquent extends Capsule  {


protected static function getconfig($value)
{
  $config  = require dirname(__DIR__, 5)."/config/database.php" ;
  return $config[$value];
}


public static function boot(){

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => self::getconfig('driver'), // Your DB driver
    'host'      => self::getconfig('host'), // Your DB host
    'database'  => self::getconfig('database'), // Your DB name
    'username'  => self::getconfig('username'), // Your DB username
    'password'  => self::getconfig('password'), // Your DB password
    'charset'   =>  self::getconfig('charset'),
    'collation' => 'utf8_unicode_ci',
    'prefix'    =>  self::getconfig('prefix'),
]);

// Set the event dispatcher used by Eloquent models... (optional)
// $capsule->setEventDispatcher(new \Illuminate\Events\Dispatcher(new \Illuminate\Container\Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (this is where the magic happens) 
$capsule->bootEloquent();
}

}