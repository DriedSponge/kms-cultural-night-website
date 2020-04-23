<?php

require __DIR__ . "/Bramus/Router/Router.php";
require_once __DIR__ . "/databases/connect.php";
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
    require_once "GoogleAPI/vendor/autoload.php";
    require_once "config.php";

    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    include('views/404.php');
});
//Indexs
$router->all('/', function () {
    require_once "GoogleAPI/vendor/autoload.php";
    require_once "config.php";

    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    $header = "/home";
    include(__DIR__ . '/views/index.php');
});

$router->all('/index.php', function () {
    require_once "GoogleAPI/vendor/autoload.php";
    require_once "config.php";

    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    $header = "/home";
    include(__DIR__ . '/views/index.php');
});

$router->all('/home/', function () {
    require_once "GoogleAPI/vendor/autoload.php";
    require_once "config.php";

    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    $header = "/home";
    include(__DIR__ . '/views/index.php');
});
$router->all('/register/', function () {
    require_once "GoogleAPI/vendor/autoload.php";
    require_once "g-register-config.php";
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    include(__DIR__ . '/views/register.php');
});
$router->all('/login/', function () {
    require_once "GoogleAPI/vendor/autoload.php";
    require_once "config.php";

    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    $header = "/home";
    include(__DIR__ . '/views/login.php');
});
$router->all('/logout/', function () {
    require_once "GoogleAPI/vendor/autoload.php";
    require_once "config.php";
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    $header = "/home";
    include(__DIR__ . '/views/logout.php');
});
$router->all('/callback/', function () {
    require_once "GoogleAPI/vendor/autoload.php";
    require_once "config.php";
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    $header = "/home";
    include(__DIR__ . '/views/g-callback.php');
});

$router->all('/google-register/', function () {
    require_once "GoogleAPI/vendor/autoload.php";
    require_once "g-register-config.php";
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    include(__DIR__ . '/views/g-callback-register.php');
});
$router->all('/profile/{name}', function ($name) {
    require_once "GoogleAPI/vendor/autoload.php";
    require_once "config.php";
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    include(__DIR__ . '/views/profile.php');
});
$router->all('/account-settings/', function () {
    require_once "GoogleAPI/vendor/autoload.php";
    require_once "config.php";
    if(isset($_SESSION['UserName'])){
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
        $host = $_SERVER['SERVER_NAME'];
        $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
        include(__DIR__ . '/views/account-settings.php');
    }else{
        header("Location: /login/");
    }
});
$router->all('/ajax/{ajax}', function ($ajax) {
    require_once "GoogleAPI/vendor/autoload.php";
    require_once "config.php";
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    include(__DIR__ . '/views/ajax/' . $ajax);
});
$router->all('/credits/', function () {
    require_once "GoogleAPI/vendor/autoload.php";
    require_once "g-register-config.php";
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    include(__DIR__ . '/views/credits.php');
});
$router->all('/admin-scripts/{script}', function ($script) {
    require_once "GoogleAPI/vendor/autoload.php";
    require_once "config.php";
    if (isset($_SESSION['gid']) && IsAdmin($_SESSION['gid'])['admin']) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    header("Content-type:  text/javascript");
    include(__DIR__ . '/js/protected/' . $script);
    }else{
        echo "Unauthorized";
    }
});
$router->run();
