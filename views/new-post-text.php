<?php
$user = SQLWrapper()->prepare("SELECT Name,Picture,Bio,RealName FROM Users WHERE gid = :gid");
$user->execute([":gid" => $_SESSION['gid']]);
$data = $user->fetch();
$restrictions = FetchRestrictions($_SESSION['gid']);
?>
<!DOCTYPE html>
<html>

<body>

    <head>
        <?php include("views/includes/head.php"); ?>
        <title>KMS Cultural Night - New Text Post</title>
        <meta name="description" content="Post a new text post">
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
                        <h1>New Text Post</h1>
                        <p class="text-center">Please fill out your post details here!</p>
                        <br>
                        <div class="container">
                            <div id="success-mesage" class="d-none">
                                <div class="alert alert-success text-center" role="alert">
                                    <span><b>Success!</b><br><span id="success_message_text">Your post has been added to the approval queue</span></span>
                                </div>
                            </div>
                            <div id="loading"></div>
                            <script>
                                $(document).ready(function() {
                                    $("#post-text").submit(function(e) {
                                        e.preventDefault()
                                        $("#post-text").hide()
                                        Loading(true, "#loading");
                                        var content = $("#con").val()
                                        var category = $("#c").val()
                                        var title = $("#t").val()
                                        var cul = $("#cul").val()
                                        $.post('<?= v($dir); ?>ajax/text-post.php', {
                                                text: 1,
                                                content: content,
                                                category: category,
                                                title: title,
                                                cul: cul
                                            })
                                            .done(function(data) {
                                                Loading(false, "#loading");
                                                if (data.success) {
                                                    $("#success-mesage").removeClass("d-none")
                                                    setInterval(() => {
                                                        location.href = `<?= v($dir); ?>text-post/${data.pid}`;
                                                    }, 3500);
                                                } else {
                                                    $("#post-text").show()
                                                    if (data.SysErr) {
                                                        AlertError(data.Msg)
                                                    } else {
                                                        if (data.TErr) {
                                                            InValidate("#t", data.TErr)
                                                        } else {
                                                            Validate("#t", data.TErr)
                                                        }
                                                        if (data.ConErr) {
                                                            InValidate("#con", data.ConErr)
                                                        } else {
                                                            Validate("#con", data.ConErr)
                                                        }
                                                        if (data.CErr) {
                                                            InValidate("#c", data.CErr)
                                                        } else {
                                                            Validate("#c", data.CErr)
                                                        }
                                                        if (data.CulErr) {
                                                            InValidate("#cul", data.CulErr)
                                                        } else {
                                                            Validate("#cul", data.CulErr)
                                                        }
                                                    }
                                                }
                                            })
                                    })

                                })
                            </script>
                            <form id="post-text">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input feedback="#t-f" id="t" maxlength="50" placeholder="Enter a title for this post" class="form-control form-control-alternative">
                                    <div id="t-f"></div>
                                </div>
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
                                    <label>Culture/Region</label>
                                    <input feedback="#cul-f" id="cul" maxlength="50" placeholder="What Culture/Region/Area is this from?" class="form-control form-control-alternative">
                                    <div id="cul-f"></div>
                                </div>
                                <div class="form-group">
                                    <label>Content</label>
                                    <textarea rows="15" feedback="#con-f" id="con" maxlength="3500" placeholder="Write here! (Max: 3500 charcaters)" class="form-control form-control-alternative"></textarea>
                                    <div id="con-f"></div>
                                </div>
                                <br>
                                <button type="submit" style="width:100%" class="btn btn-success">Submit for approval</button>
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