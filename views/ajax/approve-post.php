<?php
if (isset($_POST['approve'])) {
    if (isset($_POST['pid'])) {
        if (isset($_SESSION['gid'])) {
            if (IsAdmin($_SESSION['gid'])['admin']) {
                if (PostExist($_POST['pid'])) {
                    $post = SQLWrapper()->prepare("SELECT Title FROM " . PostType($_POST['pid']) . " WHERE PostID = :pid");
                    $post->execute([":pid" => $_POST['pid']]);
                    $data = $post->fetch();
?>
                    <script>
                        $("#approve-modal").modal("show");
                        $("#approve-button").click(function() {
                            $.post("<?= v($dir) ?>ajax/approve-post.php", {
                                    confirm: 1,
                                    pid: '<?= v($_POST['pid']); ?>'
                                })
                                .done(function(data) {
                                    if (data.success) {
                                        AlertSuccess(data.Msg)
                                        $("#approve-modal").modal("hide");
                                        <?php if(IsEmpty($_POST['table'])){ ?>
                                         setInterval(function(){
                                            location.reload()
                                         },2500)
                                        <?php }else{ ?>
                                        Load("<?= v($_POST['table']); ?>");
                                        <?php } ?>
                                    } else {
                                        AlertError(data.Msg);

                                    }
                                })
                        })
                    </script>
                    <div class="modal" tabindex="-1" id="approve-modal" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Approve Post</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" style="font-size: 30px">&times;</span>
                                    </button>
                                </div>
                                <div id="approve-load"></div>
                                <div class="modal-body">
                                    <p>Are you sure you want to approve this post on the site? It will make it public to anyone who visits the site.</p>
                                </div>
                                <div class="modal-footer text-center" style="justify-content: center">
                                    <button type="button" id="approve-button" class="btn btn-success">Approve</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
<?php
                } else {
                    AlertError("The post does not exist");
                }
            } else {
                AlertError("Unauthorized");
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
        if (IsAdmin($_SESSION['gid'])['admin']) {
            if (PostExist($_POST['pid'])) {
                try {
                    $approvalstatus = array("Status" => 1, "Message" => "Post Approved");
                    $query = SQLWrapper()->prepare("UPDATE " . PostType($_POST['pid']) . " SET Approved=:a WHERE PostID = :pid");
                    $query->execute([":a" => json_encode($approvalstatus), ":pid" => $_POST['pid']]);
                    $Msg['Msg'] = "The post has been approved and will now appear on the site!";
                    $Msg['success'] = true;
                } catch (PDOException $e) {
                    $Msg['Msg'] = "Something caused the apporval to fail. Please try again later.";
                    SendError("MySQL Error", $e->getMessage());
                }
            } else {
                $Msg['Msg'] = "The post you are trying to block does not exist";
            }
        } else {
            $Msg['Msg'] = "Unauthorized";
        }
    } else {
        $Msg['Msg'] = "Invalid Session";
    }
    die(json_encode($Msg));
}
