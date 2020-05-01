<?php
if (isset($_POST['delete'])) {
    if (isset($_POST['pid'])) {
        if (isset($_SESSION['gid'])) {
            if (PostExist($_POST['pid'])) {
                $post = SQLWrapper()->prepare("SELECT Title,gid FROM " . PostType($_POST['pid']) . " WHERE PostID = :pid");
                $post->execute([":pid" => $_POST['pid']]);
                $data = $post->fetch();
                if ($data['gid'] == $_SESSION['gid'] or IsAdmin($_SESSION['gid'])['admin']) {

?>
                    <script>
                        $("#delete-modal").modal("show");
                        $("#delete-button").click(function() {
                            $.post("<?= v($dir) ?>ajax/delete-post.php", {
                                    confirm: 1,
                                    pid: '<?= v($_POST['pid']); ?>'
                                })
                                .done(function(data) {
                                    if (data.success) {
                                        AlertSuccess(data.Msg)
                                        $("#delete-modal").modal("hide");
                                        <?php if (IsEmpty($_POST['table'])) { ?>
                                            setInterval(function() {
                                                location.reload()
                                            }, 2500)
                                        <?php } else { ?>
                                            Load("<?= v($_POST['table']); ?>");
                                        <?php } ?>
                                    } else {
                                        AlertError(data.Msg);

                                    }
                                })
                        })
                    </script>
                    <div class="modal" tabindex="-1" id="delete-modal" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete Post</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" style="font-size: 30px">&times;</span>
                                    </button>
                                </div>
                                <div id="approve-load"></div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this post? All data associated with it will be permanently deleted.</p>
                                </div>
                                <div class="modal-footer text-center" style="justify-content: center">
                                    <button type="button" id="delete-button" class="btn btn-danger">Delete</button>
                                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
<?php
                } else {
                    AlertError("Unauthorized");
                }
            } else {
                AlertError("The post does not exist");
            }
        } else {
            AlertError("Session Expired");
        }
    } else {
        AlertError("Invalid Post Data");
    }
}

if (isset($_POST['confirm'])) {
    $Msg = array(
        "success" => false,
        "Msg" => "Something went wrong!",
        "SysErr" => false
    );
    header("Content-type: application/json");
    if (isset($_SESSION['gid'])) {
        if (PostExist($_POST['pid'])) {
            $post = SQLWrapper()->prepare("SELECT Title,gid FROM " . PostType($_POST['pid']) . " WHERE PostID = :pid");
            $post->execute([":pid" => $_POST['pid']]);
            $data = $post->fetch();
            if ($data['gid'] == $_SESSION['gid'] or IsAdmin($_SESSION['gid'])['admin']) {
                if(DeletePost($_POST['pid'])){
                    $Msg['Msg'] = "The post has been deleted and will no longer appear on the site.";
                    $Msg['success'] = true;
                }else{
                    $Msg['Msg'] = "Something caused the operation to fail. Please try again later.";
                }
            } else {
                $Msg['Msg'] = "Unauthorized";
            }
        } else {
            $Msg['Msg'] = "The post you are trying to delete does not exist";
        }
    } else {
        $Msg['Msg'] = "Invalid Session";
    }
    die(json_encode($Msg));
}
