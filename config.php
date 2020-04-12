<?php
    session_start();
    $gClient = new Google_Client();
    $gClient->setClientId("1021710291064-pobaft8b9vo4t9ndv164ksv1elunqkrb.apps.googleusercontent.com");
    $gClient->setClientSecret("q6Ax3-pqtSsaPzD29A-xSnpB");
    $gClient->setApplicationName("KMS Cultural Night");
    $gClient->setRedirectUri("https://localhost/callback/");
    $gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
?>