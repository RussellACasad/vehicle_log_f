<?php
/**
 * This kills the session, and logs the user out. 
 */
session_start();

$_SESSION = [];
session_destroy();

$name = session_name();
$expire = -1;
$params = session_get_cookie_params();
$path = $params['path'];
$domain = $params['domain'];
$secure = $params['secure'];
$httponly = $params['httponly'];
setcookie($name, '', $expire, $path, $domain, $secure, $httponly);

header("Location: ./");
die;
