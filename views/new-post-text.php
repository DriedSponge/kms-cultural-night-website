<?php
$user = SQLWrapper()->prepare("SELECT Name,Picture,Bio,RealName FROM Users WHERE gid = :gid");
$user->execute([":gid" => $_SESSION['gid']]);
$data = $user->fetch();
$restrictions = FetchRestrictions($_SESSION['gid']);
?>
<html>

<body>

    <head>
        <?php include("views/includes/head.php"); ?>
        <title>KMS Cultural Night - New Text Post</title>
        <meta name="description" content="Decription">
    </head>
    <?php include("views/includes/navbar.php"); ?>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container-fluid">
                <?php 
                    $banned = IsBanned($_SESSION['gid']);
                    if($banned['banned']){
                ?>
                <div class="content-box">
                    <h1>Your account is banned!</h1>
                    <h2>Reason<br><?=htmlspecialchars($banned['reason'])?></h2>
                    <p class="text-center">As a result, you are no longer allowed to post!</p>
                </div>
                <?php
                    }else{
                ?>
                <div class="content-box">
                    
                    </div>
                <?php 
                    }
                ?>
                <br>
            </div>
        </div>
    </div>
    <!--  Modals -->

    <div id="modal"></div>



    <?php include("views/includes/footer.php"); ?>
    <script>
        const observer = lozad(); // lazy loads elements with default selector as '.lozad'
        observer.observe();
    </script>




</body>

</html>