<?php
if(isset($_SESSION['UserName'])){
    header("Location: /home/");
}
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
                        <div class="text-center">
                        <div id="error"></div>
                        <p>Register With Google</p>
                        <a href="<?= htmlspecialchars($loginURL); ?>"><img width="220px" class="img-fluid" src="<?=htmlspecialchars($dir);?>img/resources/btn_google_signin_dark_pressed_web@2x.png"></a>
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
    <?php if(isset($_GET['e'])){ ?>
        <script>
            var raw = '<?=htmlspecialchars($_GET["e"]);?>'
            //var error = raw.replace("+", " "); 
            $(document).ready(function(){
                BlockError("#error", raw,"Error!");

            })
        </script>
    <?php } ?>
</body>

</html>