<?php
if(isset($_SESSION['access_token'])){
    unset($_SESSION['access_token']);
    $gClient->revokeToken();
    session_destroy();
}
 $loginURL = $gClient->createAuthUrl();
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
                <form>
                    <div class="form-group">
                        <input class="form-control form-control-alternative" type="email" placeholder="Email...">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-alternative" type="password" placeholder="Password...">
                    </div>
                    <br>
                    <div class="text-center" style="justify-content:center">
                    <div class="form-group">
                            <button type="submit" class="btn btn-success">Login</button>
                    </div>
                    
                </form>
                <p>Or</p>
                <a href="<?=htmlspecialchars($loginURL);?>"><img width="220px" class="img-fluid" src="/img/resources/btn_google_signin_light_normal_web@2x.png"></a>

                </div>
                </div>
                </div>
            </div>
        </div>
    <!--  Modals -->

    <div id="modal"></div>



    <script src="<?= htmlspecialchars($dir); ?>js/toastr.min.js"></script>
    <script src="https://unpkg.com/popper.js@1"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="js/argon.js"></script>
    <script src="https://kit.fontawesome.com/0add82e87e.js" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/tippy.js@4"></script>


    <script>
        function CopyURL() {
            var copyText = "test";
            navigator.clipboard.writeText(copyText);
            toastr["success"]("The URL has been copied to your clipboard!", "Congratulations!")
        }
    </script>

</body>

</html>