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
                        <?php if (CanPostImage($_SESSION['gid'])) { ?>
                            <p class="text-center">Here you can upload a maximum of five images per post or a max of 100MB.</p>
                            <script>
                                $(document).ready(function() {
                                    $("#post-image").submit(function(e) {
                                        e.preventDefault()
                                        $("#post-image").hide();
                                        $.ajax({
                                            url: '<?= v($dir); ?>ajax/image-post.php',
                                            data: new FormData(this),
                                            processData: false,
                                            contentType: false,
                                            type: 'POST',
                                            success: function(data) {
                                                Loading(false, "#loading")
                                                if (data.success) {
                                                    $("#success-mesage").removeClass("d-none")
                                                    setInterval(() => {
                                                        location.reload();
                                                    }, 3500);
                                                } else {
                                                    $("#post-image").show();
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
                            <div id="success-mesage" class="d-none">
                                <div class="alert alert-success text-center" role="alert">
                                    <span><b>Success!</b><br><span id="success_message_text">Your images have been uploaded. The page will now refresh so you can complete your post!</span></span>
                                </div>
                            </div>
                            <div id="loading"></div>
                            <form id="post-image">
                                <div class="form-group">
                                    <label>Upload Image(s)</label>
                                    <input feedback="#files-f" class="form-control" type="file" accept="image/png,image/jpeg" name="files[]" id="files" multiple max="5">
                                    <div id="files-f"></div>
                                </div>
                                <input name="upload" class="d-none">
                                <button type="submit" style="width:100%" class="btn btn-info">Upload Image(s)</button>
                            </form>
                        <?php } else { ?>
                            <p class="text-center">Please fill in the rest of the details to complete your post!</p>

                            <div class="container">
                                <div class="row display-flex">
                                    <?php
                                    $query = SQLWrapper()->prepare("SELECT Images,PostID FROM ImagePost WHERE gid = :gid AND  Title  IS NULL AND  Category  IS NULL AND  Caption  IS NULL");
                                    $query->execute([":gid" => $_SESSION['gid']]);
                                    $data = $query->fetch();                                    
                                    $numOfCols = 3;
                                    $rowCount = 0;
                                    $images = json_decode($data['Images'], true);
                                    $i = 1;
                                    foreach ($images as $image) {
                                    ?>
                                        <div class="col indexcol">
                                            <div class="card card-photo">
                                                    <a target="_blank" href="<?= v($image); ?>"><img data-src="<?= v($image); ?>" alt="Photo Post" class="img-fluid lozad" style="max-height:300px;" /></a>
                                            </div>
                                        </div>
                                    <?php
                                        $rowCount++;
                                        if ($rowCount % $numOfCols == 0) echo '</div><br><div class="row display-flex">';
                                    }
                                    ?>
                                </div>
                            </div>
                            <br>
                            <div class="container">
                                <div id="success-mesage" class="d-none">
                                    <div class="alert alert-success text-center" role="alert">
                                        <span><b>Success!</b><br><span id="success_message_text">Your images have been added to the approval queue! If your post is approved it will appear on the site. Thank you for particiapting!</span></span>
                                    </div>
                                </div>
                                <div id="loading"></div>
                                <script>
                                    $(document).ready(function() {
                                        $("#complete-image").submit(function(e) {
                                            e.preventDefault()
                                             $("#complete-image").hide()
                                             Loading(true,"#loading");
                                            var caption = $("#cap").val()
                                            var category = $("#c").val()
                                            var title = $("#t").val()
                                            var cul = $("#cul").val()
                                            $.post('<?= v($dir); ?>ajax/image-post.php', {
                                                complete: 1,
                                                caption: caption,
                                                category: category,
                                                title: title,
                                                cul: cul,
                                                pid: '<?=v($data['PostID']);?>'
                                            })
                                            .done(function(data){
                                                Loading(false,"#loading");
                                                if(data.success){
                                                    $("#success-mesage").removeClass("d-none")
                                                    setInterval(() => {
                                                        location.href='<?=v($dir);?>photos/<?=v($data['PostID']);?>';
                                                    }, 3500);
                                                }else{
                                                    $("#complete-image").show()
                                                    if(data.SysErr){
                                                        AlertError(data.Msg)
                                                    }else{
                                                        if(data.TErr){
                                                            InValidate("#t",data.TErr)
                                                        }else{
                                                            Validate("#t",data.TErr)
                                                        }
                                                        if(data.CapErr){
                                                            InValidate("#cap",data.CapErr)
                                                        }else{
                                                            Validate("#cap",data.CapErr)
                                                        }
                                                        if(data.CErr){
                                                            InValidate("#c",data.CErr)
                                                        }else{
                                                            Validate("#c",data.CErr)
                                                        }
                                                        if(data.CulErr){
                                                            InValidate("#cul",data.CulErr)
                                                        }else{
                                                            Validate("#cul",data.CulErr)
                                                        }
                                                    }
                                                }
                                            })
                                        })
                                        $("#reset").click(function(){
                                            $("#complete-image").hide()
                                            Loading(true,"#loading");
                                            $.post('<?= v($dir); ?>ajax/image-post.php', {
                                                cancel: 1,
                                                pid: '<?=v($data['PostID']);?>'
                                            })
                                            .done(function(data){
                                                Loading(false,"#loading");
                                                if(data.success){
                                                    $("#success-mesage").removeClass("d-none")
                                                    $("#success_message_text").text(data.Msg);
                                                    setInterval(() => {
                                                        location.reload();
                                                    }, 3500);
                                                }else{
                                                    $("#complete-image").show()
                                                    AlertError(data.Msg);
                                                }
                                            })
                                        })
                                    })
                                </script>
                                <form id="complete-image">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select class="form-control form-control-alternative" feedback="#c-f" id="c" name="c">
                                            <option value="Food">Food</option>
                                            <option value="Music">Music</option>
                                            <option value="Sports">Sports</option>
                                            <option value="Gatherings">Gatherings</option>
                                            <option value="Other" selected>Other</option>
                                        </select>
                                        <div id="c-f"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input feedback="#t-f" id="t" maxlength="30" placeholder="Enter a title for this post" class="form-control form-control-alternative">
                                        <div id="t-f"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Culture/Region</label>
                                        <input feedback="#cul-f" id="cul" maxlength="40" placeholder="What Culture/Region/Area is this from?" class="form-control form-control-alternative">
                                        <div id="cul-f"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Caption</label>
                                        <textarea rows="5" feedback="#cap-f" id="cap" maxlength="1000" placeholder="Enter a breif caption for this post." class="form-control form-control-alternative"></textarea>
                                        <div id="cap-f"></div>
                                    </div>
                                    <br>
                                    <div class="row" style="justify-content: center">
                                        <div class="col">
                                            <button id="reset"type="reset" style="width:100%" class="btn btn-danger">Cancel This Post</button>
                                        </div>
                                        <div class="col">
                                            <button type="submit" style="width:100%" class="btn btn-success">Submit for approval</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
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