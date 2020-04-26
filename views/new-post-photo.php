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
                        <h2 class="text-center">Here you can upload a maximum of five images per post to share</h2>
                        <script>
                            $(document).ready(function() {
                                $("#post-image").submit(function(e) {
                                    e.preventDefault()
                                    $.ajax({
                                        url: '<?=v($dir);?>ajax/image-post.php',
                                        data: new FormData(this),
                                        processData: false,
                                        contentType: false,
                                        type: 'POST',
                                        success: function(data) {
                                            Loading(false, "#loading")
                                            $("#add-duck").show()
                                            if (data.success) {
                                                
                                            } else {
                                                if (data.SysErr) {
                                                    AlertError(data.Msg);
                                                } else {
                                                    if(data.FErr){
                                                        InValidate("#files",data.FErr)
                                                    }else{
                                                        Validate("#files")
                                                    }
                                                    if(data.CErr){
                                                        InValidate("#c",data.CErr)
                                                    }else{
                                                        Validate("#c")
                                                    }
                                                    if(data.CapErr){
                                                        InValidate("#caption",data.CapErr)
                                                    }else{
                                                        Validate("#caption")
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
                                <label>Upload Image</label>
                                <input feedback="#files-f" class="form-control" type="file" accept="image/png,image/jpeg" name="files[]" id="files" multiple max="5">
                                <div id="files-f"></div>
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
                                <label>Caption for the images</label>
                                <textarea feedback="#caption-f" name="caption" id="caption" class="form-control form-control-alternative" placeholder="Please enter a caption for the images" rows="5" maxlength="1000"></textarea>
                                <div id="caption-f"></div>
                            
                            </div>
                            <input name="post" class="d-none">
                            <button type="submit" style="width:100%" class="btn btn-success">Submit For Approval</button>
                        </form>
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