<?php


require __DIR__ . "/Bramus/Router/Router.php";
require_once __DIR__ . "/src/libs/SteamID.php";
require_once __DIR__ . "/src/libs/functions.php";

$router = new \Bramus\Router\Router();

$router->get('pattern', function () { /* ... */
});
$router->post('pattern', function () { /* ... */
});
$router->put('pattern', function () { /* ... */
});
$router->delete('pattern', function () { /* ... */
});
$router->options('pattern', function () { /* ... */
});
$router->patch('pattern', function () { /* ... */
});




// Define routes
// Errors

$router->set404(function () {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    include('views/404.php');
});
//Indexs
$router->all('/', function () {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    $header = "/home";
    include(__DIR__ . '/views/index.php');
});

$router->all('/index.php', function () {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    $header = "/home";
    include(__DIR__ . '/views/index.php');
});
$router->all('/home/', function () {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    $header = "/home";
    include(__DIR__ . '/views/index.php');
});
;



$router->all('/ajax/{ajax}', function ($ajax) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    require(__DIR__ . '/steamauth/steamauth.php');
    include(__DIR__ . '/steamauth/userInfo.php');
    include(__DIR__ . '/views/ajax/' . $ajax);
});
$router->run();
