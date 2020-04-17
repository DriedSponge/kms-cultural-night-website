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
if(UserExist($UserData['id'])){
    if(UpdateGInfo($UserData['givenName'],$UserData['picture'],$UserData['email'],$UserData['id'])){
        $OurUserData = UserInfo($UserData['id']);
        $_SESSION['email'] = $UserData['email'];
        $_SESSION['picture'] = $UserData['picture'];
        $_SESSION['gid'] = $UserData['id'];
        $_SESSION['name'] = $UserData['givenName'];   
        $_SESSION['UserName'] = $OurUserData['UserName'];
        $_SESSION['CreationDate'] = $OurUserData['CreationDate'];
        $_SESSION['Bio'] = $OurUserData['Bio'];
        $_SESSION['TOS'] = $OurUserData['TOS'];
        header("Location: /home/");

    }else{
        header("Location: /logout/");
    }  
}else{
    header("Location: /register/?e=The+account+you+tried+to+login+with+is+not+registered.+Please+register+here!");    
}


