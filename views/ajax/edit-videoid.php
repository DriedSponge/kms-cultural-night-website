<?php
if (isset($_POST['edit'])) {
    if (isset($_POST['pid'])) {
        if (isset($_SESSION['gid'])) {
            if (IsAdmin($_SESSION['gid'])['admin']) {
                if (PostExist($_POST['pid'])) {
                    $post = SQLWrapper()->prepare("SELECT Title,VideoID FROM " . PostType($_POST['pid']) . " WHERE PostID = :pid");
                    $post->execute([":pid" => $_POST['pid']]);
                    $data = $post->fetch();
?>
                    <script>
                        $("#editvid-modal").modal("show");
                        $("#editvid").submit(function(e) {
                            e.preventDefault()
                            var vid = $("#vid").val()
                            $.post("<?= v($dir) ?>ajax/edit-videoid.php", {
                                    save: 1,
                                    pid: '<?= v($_POST['pid']); ?>',
                                    vid: vid
                                })
                                .done(function(data) {
                                    if (data.success) {
                                        AlertSuccess(data.Msg)
                                        $("#editvid-modal").modal("hide");
                                        <?php if (IsEmpty($_POST['table'])) { ?>
                                            setInterval(function() {
                                                location.reload()
                                            }, 2500)
                                        <?php } else { ?>
                                            Load("<?= v($_POST['table']); ?>");
                                        <?php } ?>
                                    } else {
                                        if (data.SysErr) {
                                            AlertError(data.Msg);
                                        } else {
                                            if (data.vidErr) {
                                                InValidate("#vid", data.vidErr)
                                            } else {
                                                Validate("#vid")
                                            }
                                        }


                                    }
                                })
                        })
                    </script>
                    <div class="modal" tabindex="-1" id="editvid-modal" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit the Video ID</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" style="font-size: 30px">&times;</span>
                                    </button>
                                </div>
                                <div id="editvid-load"></div>
                                <form id="editvid">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Video ID</label>
                                            <input feedback="#vid-f" id="vid" maxlength="50" value="<?= v($data['VideoID']); ?>" placeholder="Enter a Video ID from YouTube" class="form-control form-control-alternative">
                                            <div id="vid-f"></div>
                                        </div>
                                    </div>
                                    <div class="modal-footer text-center" style="justify-content: center">
                                        <button type="submit" class="btn btn-success">Save</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
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

if (isset($_POST['save'])) {
    $Msg = array(
        "success" => false,
        "Msg" => "Something went wrong!",
        "SysErr" => false
    );
    header("Content-type: application/json");
    if (isset($_SESSION['gid'])) {
        if (IsAdmin($_SESSION['gid'])['admin']) {
            if (PostExist($_POST['pid'])) {
                if (PostType($_POST['pid']) == "VideoPost") {
                    if (strlen($_POST['vid']) > 50) {
                        $Msg['vidErr'] = "Please keep it under 50 characters.";
                    }
                    if (!isset($Msg['vidErr'])) {
                        try {
                            $approvalstatus = array("Status" => 1, "Message" => "Post Approved");
                            $query = SQLWrapper()->prepare("UPDATE VideoPost SET VideoID=:id WHERE PostID = :pid");
                            $query->execute([":id" => $_POST['vid'], ":pid" => $_POST['pid']]);
                            $Msg['Msg'] = "The post VideoID has been saved!";
                            $Msg['success'] = true;
                        } catch (PDOException $e) {
                            $Msg['Msg'] = "Something caused this operation to fail. Please try again later.";
                            SendError("MySQL Error", $e->getMessage());
                        }
                    }
                } else {
                    $Msg['Msg'] = "The post must be a video post";
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
