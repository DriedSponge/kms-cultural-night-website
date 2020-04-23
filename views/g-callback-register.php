<?php
if(!isset($_SESSION['UserName'])){
if (isset($_SESSION['access_token'])) {
    $gClient->setAccessToken($_SESSION['access_token']);
} else if (isset($_GET['code'])) {
    $token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token;
} else {
    header("Location: /register/");
    die();
}
$oAuth = new Google_Service_Oauth2($gClient);
$UserData = $oAuth->userinfo_v2_me->get();
if ($UserData["verified_email"]) {
    if(!UserExist($UserData['id'])){
        $_SESSION['email'] = $UserData['email'];
        $_SESSION['gid'] = $UserData['id'];
        $_SESSION['picture'] = $UserData['picture'];
        $_SESSION['name'] = $UserData['givenName'];
    }else{
        $error = NewError("An account already exist with this google account!");
        header("Location: /register/?e=$error");
    }
} else {
    $error = NewError("The account you tried to sign up with does not have a verified email!");
    header("Location: /register/?e=$error");
}
}else{
    header("Location: /home/");
}
?>
<html>

<body>

    <head>
        <?php include("views/includes/head.php"); ?>
        <title>KMS Cultural Night - Welcome <?= htmlspecialchars($_SESSION['name']); ?></title>
        <meta name="description" content="Register With Google">
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

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
                        <h1><img class="round-size-1" src="<?=htmlspecialchars($_SESSION['picture']);?>"></h1>
                        <h2>Welcome <?= htmlspecialchars($_SESSION['name']); ?>!</h2>
                        <br>
                        <p class="text-center">Plese fill out the rest of the form to complete your account setup!</p>
                        <div id="#loading"></div>
                        <script>
                            $(document).ready(function() {
                                $("#legal").click(function() {
                                    var checked = $("#legal").prop("checked");
                                    if (checked) {
                                        document.getElementById("complete").disabled = false;
                                    } else {
                                        document.getElementById("complete").disabled = true;
                                    }
                                })

                                $("#g-register-form").submit(function(e) {
                                    e.preventDefault();
                                    Loading(true, '#loading');
                                    $("#g-register-form").hide();
                                    var uname = $("#uname").val();
                                    var checked = $("#legal").prop("checked");
                                    if (checked) {
                                        var agree = 1;
                                    } else {
                                        var agree = 0;
                                    }
                                    $.post("<?= htmlspecialchars($dir); ?>ajax/g-register.php", {
                                            register: 1,
                                            agree: agree,
                                            uname: uname
                                        })
                                        .done(function(data) {
                                            Loading(false, '#loading');
                                            if (data.success) {
                                                AlertSuccess(data.Msg)
                                                Validate("#uname")
                                                Validate("#legal")
                                                $("#success-message").removeClass("d-none")
                                                setInterval(function(){
                                                    location.href = "<?= htmlspecialchars($dir); ?>login/"
                                                },4000)
                                            } else {
                                                $("#g-register-form").show();
                                                if(data.SysErr){
                                                    AlertError(data.Msg)
                                                }else{
                                                    if(data.unameERR){
                                                        InValidate("#uname",data.unameERR)
                                                    }else{
                                                        Validate("#uname")
                                                    }
                                                    if(data.legalErr){
                                                        InValidate("#legal",data.legalErr)
                                                    }else{
                                                        Validate("#legal")
                                                    }
                                                }
                                            }
                                        });
                                })
                            })
                        </script>
                        <div id="success-message" class="d-none">
                                <div class="alert alert-success text-center"  role="alert">
                                    <span><b>Success!</b><br><span id="success_message_text">Your account has been created! You will now be redirected to the login page so you can get started!</span></span>
                                </div>
                            </div>
                        <div id="loading"></div>
                        <form id="g-register-form">
                            <div class="form-group">
                                <input maxlength="30" feedback="#uname-feedback" minlength="3" id="uname" class="form-control form-control-alternative" type="text" placeholder="Username*">
                                <div id="uname-feedback"></div>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input feedback="#legal-feedback" class="custom-control-input" id="legal" type="checkbox">
                            <div id="legal-feedback"></div>
                                
                                <label class="custom-control-label" for="legal">I have read and agree to the <strong><a href="<?= htmlspecialchars($dir); ?>tos/" target="_blank">Terms Of Service</a></strong> and <strong><a href="<?= htmlspecialchars($dir); ?>privacy/" target="_blank">Privacy Policy</a></strong></label>
                            </div>
                            <br>
                            <div class="text-center" style="justify-content:center">
                                <div class="form-group">
                                    <button type="submit" id="complete" class="btn btn-success" disabled>Complete Registration</button>
                                </div>
                                <div class=" form-group">
                                    <a class="btn btn-danger" href="<?= htmlspecialchars($dir); ?>logout/">Cancel Registration</a>
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