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
        <title>KMS Cultural Night - New Video Post</title>
        <meta name="description" content="Post a new video">
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
                        <h1>New Video Post</h1>
                            <p class="text-center">Please fill in the rest of the details to complete your post!</p>
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
                                        $("#post-video").submit(function(e) {
                                            e.preventDefault()
                                             $("#post-video").hide()
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
                                                    $("#post-video").show()
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
                                        
                                    })
                                </script>
                                <form id="post-video">
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
                                        <input feedback="#t-f" id="t" maxlength="50" placeholder="Enter a title for this post" class="form-control form-control-alternative">
                                        <div id="t-f"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Culture/Region</label>
                                        <input feedback="#cul-f" id="cul" maxlength="50" placeholder="What Culture/Region/Area is this from?" class="form-control form-control-alternative">
                                        <div id="cul-f"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Caption</label>
                                        <textarea rows="5" feedback="#cap-f" id="cap" maxlength="1000" placeholder="Enter a breif caption for this post." class="form-control form-control-alternative"></textarea>
                                        <div id="cap-f"></div>
                                    </div>
                                    <br>
                                        <div class="col">
                                            <button type="submit" style="width:100%" class="btn btn-success">Submit for approval</button>
                                        </div>

                                </form>
                            </div>
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