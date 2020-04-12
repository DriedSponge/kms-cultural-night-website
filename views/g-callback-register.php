<?php
if (isset($_SESSION['access_token'])) {
    $gClient->setAccessToken($_SESSION['access_token']);
} else if (isset($_GET['code'])) {
    $token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token;
} else {
    header("Location: /login/");
    die();
}
$oAuth = new Google_Service_Oauth2($gClient);
$UserData = $oAuth->userinfo_v2_me->get();
if ($UserData["verified_email"]) {
    $_SESSION['email'] = $UserData['email'];
    $_SESSION['picture'] = $UserData['picture'];
    $_SESSION['gender'] = $UserData['gender'];
    $_SESSION['name'] = $UserData['givenName'];
} else {
    die("poop");
}
?>
<html>

<body>

    <head>
        <?php include("views/includes/head.php"); ?>
        <title>KMS Cultural Night - Welcome <?= htmlspecialchars($_SESSION['name']); ?></title>
        <meta name="description" content="Log into your account">
    </head>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container-fluid">
                <br>
                <script>
                    $(document).ready(function() {
                        $("#g-register-form").submit(function(e) {
                            e.preventDefault();
                            var username = $("#uname").val();
                            var psw = $("#psw").val();
                            var psw2 = $("#psw2").val();
                            $.post("/ajax/g-register.php", {
                                username: username,
                                psw: psw,
                                psw2: psw2
                            })
                        })
                    })
                </script>
                <div class="row" style="justify-content:center">
                    <div class="content-box" style="max-width: 500px">
                        <h2>Welcome, <?= htmlspecialchars($_SESSION['name']); ?></h2>
                        <p class="text-center">Plese fill out the rest of the form to complete your account setup!</p>
                        <div id="#loading"></div>
                        <script>
                            $(document).ready(function(){
                                $("#legal").click(function(){
                                var checked =  $("#legal").prop("checked");

                                if(checked){
                                    document.getElementById("complete").disabled = false;
                                }else{
                                    document.getElementById("complete").disabled = true;
                                }
                            })
                            })
                            
                        </script>
                        <form id="g-register-form">
                            <div class="form-group">
                                <input id="psw" class="form-control form-control-alternative" type="password" placeholder="Set Password*">
                            </div>
                            <div class="form-group">
                                <input id="psw2" class="form-control form-control-alternative" type="password" placeholder="Reapeat Password*">
                            </div>
                            <div class="form-group">
                                <input id="uname" class="form-control form-control-alternative" type="text" placeholder="Username*">
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input class="custom-control-input" id="legal" type="checkbox" >
                                <label class="custom-control-label" for="legal">I have read and agree to the <strong><a href="<?=htmlspecialchars($dir);?>tos/" target="_blank">Terms Of Service</a></strong> and <strong><a href="<?=htmlspecialchars($dir);?>privacy/" target="_blank">Privacy Policy</a></strong></label>
                            </div>
                            <br>
                            <div class="text-center" style="justify-content:center">
                                <div class="form-group">
                                    <button type="submit" id="complete" class="btn btn-success" disabled>Complete Regisstration</button>
                                </div>
                            </div>
                        </form>
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
    <script src="<?= htmlspecialchars($dir); ?>js/argon.js"></script>
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