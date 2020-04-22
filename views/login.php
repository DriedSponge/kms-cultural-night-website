<?php
if(isset($_SESSION['UserName'])){
    header("Location: /home/");
}else{
    unset($_SESSION['access_token']);
    $gClient->revokeToken();
    session_destroy();
 $loginURL = $gClient->createAuthUrl();

}

?>
<html>

<body>

    <head>
        <?php include("views/includes/head.php"); ?>
        <title>KMS Cultural Night - Login</title>
        <meta name="description" content="Log into your account">
    </head>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container-fluid" >
            <br>
            <div class="row" style="justify-content:center">

                <div class="content-box"style="max-width: 400px">
                <h1>Login</h1>
                <div  class="text-center">
                    <a href="<?=htmlspecialchars($loginURL);?>"><img width="220px" class="img-fluid" src="<?=htmlspecialchars($dir);?>img/resources/btn_google_signin_dark_pressed_web@2x.png"></a>
                </div>
                </div>
                </div>
                </div>
            </div>
        </div>
    <!--  Modals -->

    <div id="modal"></div>



    <?php include("views/includes/footer.php"); ?>



</body>

</html>