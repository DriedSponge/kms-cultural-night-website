<?php

if(isset($_SESSION['access_token'])){
    $gClient->setAccessToken($_SESSION['access_token']);
}else if(isset($_GET['code'])){
    $token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token;
}else{
    header("Location: /login/");
    die();
}

$oAuth = new Google_Service_Oauth2($gClient);
$UserData = $oAuth->userinfo_v2_me->get();

$_SESSION['email'] = $UserData['email'];
$_SESSION['picture'] = $UserData['picture'];
$_SESSION['gender'] = $UserData['gender'];
$_SESSION['name'] = $UserData['givenName'];
header("Location: /home/");