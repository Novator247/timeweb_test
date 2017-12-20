<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 10.12.17
 * Time: 2:07
 */

chdir(dirname(__DIR__));
define('APPLICATION_PATH', __DIR__.'/../application');
define('APPLICATION_PUBLIC_PATH', __DIR__);
ini_set("error_reporting", E_ALL);
ini_set("display_errors", true);

try {
    include __DIR__ . '/../vendor/autoload.php';
    $config_file_path = __DIR__ . '/../config/main.php';
    $config = null;
    if(file_exists($config_file_path)){
        $config = require $config_file_path;
    }
    (new \Library\Application($config))->run();
} catch (Exception $e){
    echo '<pre>';
    print_r($e->getMessage());
    die('ERROR');
}