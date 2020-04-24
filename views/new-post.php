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
        <title>KMS Cultural Night - New Post</title>
        <meta name="description" content="Post something new to the site">
    </head>
    <?php include("views/includes/navbar.php"); ?>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container-fluid">
                <div class="content-box">
                    <h1>Select Post Type</h1>
                    <div class="row display-flex">
                        <div class="col indexcol">
                            <div class="card card-border">
                                <div class="card-body">
                                    <img data-src="<?= htmlspecialchars($dir); ?>img/resources/icons/png/document.png" alt="Text Post" class="img-fluid lozad" style="max-height:100px;" />
                                    <br>
                                    <br>
                                    <button onclick="location.href = '<?=htmlspecialchars($dir);?>new-post/text'" class="btn btn-primary">Text Post</button>
                                </div>
                            </div>
                        </div>
                        <div class="col indexcol">
                            <div class="card card-border">
                                <div class="card-body">
                                    <img data-src="<?= htmlspecialchars($dir); ?>img/resources/icons/png/video-camera.png" alt="Video Post" class="img-fluid lozad" style="max-height:100px;" />
                                    <br>
                                    <br>
                                    <button onclick="location.href = '<?=htmlspecialchars($dir);?>new-post/video'" class="btn btn-primary">Video Post</button>
                                </div>
                            </div>
                        </div>
                        <div class="col indexcol">

                            <div class="card card-border">
                                <div class="card-body">
                                    <img data-src="<?= htmlspecialchars($dir); ?>img/resources/icons/png/gallery.png" alt="Photo Post" class="img-fluid lozad" style="max-height:100px;" />
                                    <br>
                                    <br>
                                    <button onclick="location.href = '<?=htmlspecialchars($dir);?>new-post/photo'" class="btn btn-primary">Photo Post</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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