<?php

$loginURL = $gClient->createAuthUrl();

?>
<html>

<body>

    <head>
        <?php include("views/includes/head.php"); ?>
        <title>KMS Cultural Night - Register Account</title>
        <meta name="description" content="Log into your account">
    </head>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container-fluid">
                <br>
                <div class="row" style="justify-content:center">

                    <div class="content-box" style="max-width: 500px">
                        <h1>Register</h1>
                        <p class="text-center">When you register an account, you gain the ability to post on the site. Accounts with non NSD emails will have to be manually verified for security reasons.</p>
                        <script>
                            $(document).ready(function() {
                                $("#register-form").submit(function(event) {
                                    event.preventDefault();
                                    $("#register-form").hide()
                                    Loading(true, "#loading")
                                    var fd = new FormData(event.target);
                                    $.ajax({
                                        url: '<?= htmlspecialchars($dir); ?>ajax/reg-register.php',
                                        data: new FormData(this),
                                        processData: false,
                                        contentType: false,
                                        type: 'POST',

                                        success: function(data) {
                                            Loading(false, "#loading")
                                            $("#add-duck").show()
                                            if (data.success) {
                                                LoadTable()
                                                toastr["success"](data.message, "Congratulations!")
                                                Validate("#img", "#img-feedback", false)
                                                Validate("#name", "#name-feedback", false)
                                                Validate("#cod", "#cod-feedback", false)

                                            } else {
                                                if (data.UsrErr) {
                                                    toastr["error"](data.message, "Error:")
                                                } else {
                                                    if (data.NErr) {
                                                        InValidate("#name", "#name-feedback", data.NErr)
                                                    } else {
                                                        Validate("#name", "#name-feedback", false)
                                                    }
                                                    if (data.CODErr) {
                                                        InValidate("#cod", "#cod-feedback", data.CODErr)
                                                    } else {
                                                        Validate("#cod", "#cod-feedback", false)
                                                    }
                                                    if (data.IMGErr) {
                                                        InValidate("#img", "#img-feedback", data.IMGErr)
                                                    } else {
                                                        Validate("#img", "#img-feedback", false)
                                                    }
                                                }
                                            }
                                        }
                                    });
                                })
                            })
                        </script>
                        <div id="#loading"></div>
                        <form id="register-form">
                            <div class="form-group">
                                <input name="email" class="form-control form-control-alternative" type="email" placeholder="Email*">
                            </div>
                            <div class="form-group">
                                <input  name="psw" class="form-control form-control-alternative" type="password" placeholder="Password*">
                            </div>
                            <div class="form-group">
                                <input name="psw2" class="form-control form-control-alternative" type="password" placeholder="Reapeat Password*">
                            </div>
                            <div class="form-group">
                                <input  name="name" maxlength="" class="form-control form-control-alternative" type="text" placeholder="Your Name*">
                            </div>
                            <div class="form-group">
                                <input  name="uname" class="form-control form-control-alternative" type="text" placeholder="Username*">
                            </div>
                            <div class="form-group">
                                <label>Profile Photo</label><br>
                                <input name="pfp" type="file" class="form-control" feedback="#pfp-feedback" placeholder="Last Name">
                                <div id="pfp-feedback"></div>
                            </div>
                            <br>
                            <div class="text-center" style="justify-content:center">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">Register New Account</button>
                                </div>
                                <input name="reg-register" style="display:none;">
                            </div>
                        </form>
                        <br>
                        <div class="text-center">
                        <p>Or Register With Google</p>
                        <a href="<?= htmlspecialchars($loginURL); ?>"><img width="220px" class="img-fluid" src="/img/resources/btn_google_signin_light_normal_web@2x.png"></a>
                        </div>
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