<?php
$user = SQLWrapper()->prepare("SELECT Name,Picture,Bio,RealName FROM Users WHERE gid = :gid");
$user->execute([":gid" => $_SESSION['gid']]);
$data = $user->fetch();
$restrictions = FetchRestrictions($_SESSION['gid']);
if (isset($_SESSION['postimgid'])) {
    $idir = 'img/post/' . $_SESSION['postimgid'];
    if (file_exists($idir)) {
        if (dir_is_empty($idir)) {
            rmdir($idir);
            unset($_SESSION['postimgid']);
            $upload = true;
        } else {
            $upload = false;
        }
    } else {
        $upload = true;
        unset($_SESSION['postimgid']);
    }
} else {
    $upload = true;
}
?>
<html>

<body>

    <head>
        <?php include("views/includes/head.php"); ?>
        <title>KMS Cultural Night - New Photo Post</title>
        <meta name="description" content="Decription">
    </head>
    <?php include("views/includes/navbar.php"); ?>
    <div class="app">
        <div class="container-fluid-lg">
            <div class="container-fluid">
                <?php
                $banned = IsBanned($_SESSION['gid']);
                if ($banned['banned']) {
                ?>
                    <div class="content-box">
                        <h1>Your account is banned!</h1>
                        <h2>Reason<br><?= htmlspecialchars($banned['reason']) ?></h2>
                        <p class="text-center">As a result, you are no longer allowed to post!</p>
                    </div>
                <?php
                } else {
                ?>
                    <div class="content-box">
                        <h1>New Image Post</h1>
                        <?php if ($upload) { ?>
                            <h2 class="text-center">Here you can upload a maximum of five images per post.</h2>
                            <script>
                                $(document).ready(function() {
                                    $("#post-image").submit(function(e) {
                                        e.preventDefault()
                                        $.ajax({
                                            url: '<?= v($dir); ?>ajax/image-post.php',
                                            data: new FormData(this),
                                            processData: false,
                                            contentType: false,
                                            type: 'POST',
                                            success: function(data) {
                                                Loading(false, "#loading")
                                                $("#add-duck").show()
                                                if (data.success) {
                                                    AlertSuccess("dust");
                                                } else {
                                                    if (data.SysErr) {
                                                        AlertError(data.Msg);
                                                    } else {
                                                        if (data.FErr) {
                                                            InValidate("#files", data.FErr)
                                                        } else {
                                                            Validate("#files")
                                                        }

                                                    }
                                                }
                                            }
                                        });
                                    })
                                })
                            </script>
                            <form id="post-image">
                                <div class="form-group">
                                    <label>Upload Image(s)</label>
                                    <input feedback="#files-f" class="form-control" type="file" accept="image/png,image/jpeg" name="files[]" id="files" multiple max="5">
                                    <div id="files-f"></div>
                                </div>
                                <input name="upload" class="d-none">
                                <button type="submit" style="width:100%" class="btn btn-info">Upload Image(s)</button>
                            </form>
                        <?php } ?>
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