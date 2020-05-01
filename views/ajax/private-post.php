<?php
if (isset($_POST['private'])) {
    if (isset($_POST['pid'])) {
        if (isset($_SESSION['gid'])) {
            if (PostExist($_POST['pid'])) {
                $post = SQLWrapper()->prepare("SELECT Title,Private,gid FROM " . PostType($_POST['pid']) . " WHERE PostID = :pid");
                $post->execute([":pid" => $_POST['pid']]);
                $data = $post->fetch();
                if ($data['Private'] == 0) {
                    if ($data['gid'] == $_SESSION['gid'] or IsAdmin($_SESSION['gid'])['admin']) {

?>
                        <script>
                            $("#private-modal").modal("show");
                            $("#private-button").click(function() {
                                $.post("<?= v($dir) ?>ajax/private-post.php", {
                                        confirm: 1,
                                        pid: '<?= v($_POST['pid']); ?>'
                                    })
                                    .done(function(data) {
                                        if (data.success) {
                                            AlertSuccess(data.Msg)
                                            $("#private-modal").modal("hide");
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
                        <div class="modal" tabindex="-1" id="private-modal" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Private Post</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" style="font-size: 30px">&times;</span>
                                        </button>
                                    </div>
                                    <div id="approve-load"></div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to private this post?</p>
                                    </div>
                                    <div class="modal-footer text-center" style="justify-content: center">
                                        <button type="button" id="private-button" class="btn btn-warning">Private</button>
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
                    AlertError("This post is already private.");
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
            $post = SQLWrapper()->prepare("SELECT Title,gid,Private FROM " . PostType($_POST['pid']) . " WHERE PostID = :pid");
            $post->execute([":pid" => $_POST['pid']]);
            $data = $post->fetch();
            if ($data['gid'] == $_SESSION['gid'] or IsAdmin($_SESSION['gid'])['admin']) {
                if ($data['Private'] == 0) {
                    try {
                        $query = SQLWrapper()->prepare("UPDATE " . PostType($_POST['pid']) . " SET Private=:p WHERE PostID = :pid");
                        $query->execute([":p" => 1, ":pid" => $_POST['pid']]);
                        $Msg['Msg'] = "The post has been privated and will now only be visible to you.";
                        $Msg['success'] = true;
                    } catch (PDOException $e) {
                        $Msg['Msg'] = "Something caused the operation to fail. Please try again later.";
                        SendError("MySQL Error", $e->getMessage());
                    }
                } else {
                    $Msg['Msg'] = "The post is already private.";
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

//Public

if (isset($_POST['public'])) {
    if (isset($_POST['pid'])) {
        if (isset($_SESSION['gid'])) {
            if (PostExist($_POST['pid'])) {
                $post = SQLWrapper()->prepare("SELECT Title,Private,gid FROM " . PostType($_POST['pid']) . " WHERE PostID = :pid");
                $post->execute([":pid" => $_POST['pid']]);
                $data = $post->fetch();
                if ($data['Private'] == 1) {
                    if ($data['gid'] == $_SESSION['gid'] or IsAdmin($_SESSION['gid'])['admin']) {

?>
                        <script>
                            $("#public-modal").modal("show");
                            $("#public-button").click(function() {
                                $.post("<?= v($dir) ?>ajax/private-post.php", {
                                        confirm2: 1,
                                        pid: '<?= v($_POST['pid']); ?>'
                                    })
                                    .done(function(data) {
                                        if (data.success) {
                                            AlertSuccess(data.Msg)
                                            $("#public-modal").modal("hide");
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
                        <div class="modal" tabindex="-1" id="public-modal" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Make Post Public</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" style="font-size: 30px">&times;</span>
                                        </button>
                                    </div>
                                    <div id="approve-load"></div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to make this post public? It will appear to anyone who uses the site.</p>
                                    </div>
                                    <div class="modal-footer text-center" style="justify-content: center">
                                        <button type="button" id="public-button" class="btn btn-success">Make Public</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
<?php
                    } else {
                        AlertError("Unauthorized");
                    }
                } else {
                    AlertError("This post is already public.");
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

if (isset($_POST['confirm2'])) {
    $Msg = array(
        "success" => false,
        "Msg" => "Something went wrong!",
        "SysErr" => false
    );
    header("Content-type: application/json");
    if (isset($_SESSION['gid'])) {
        if (PostExist($_POST['pid'])) {
            $post = SQLWrapper()->prepare("SELECT Title,gid,Private FROM " . PostType($_POST['pid']) . " WHERE PostID = :pid");
            $post->execute([":pid" => $_POST['pid']]);
            $data = $post->fetch();
            if ($data['gid'] == $_SESSION['gid'] or IsAdmin($_SESSION['gid'])['admin']) {
                if ($data['Private'] == 1) {
                    try {
                        $query = SQLWrapper()->prepare("UPDATE " . PostType($_POST['pid']) . " SET Private=:p WHERE PostID = :pid");
                        $query->execute([":p" => 0, ":pid" => $_POST['pid']]);
                        $Msg['Msg'] = "This post is now public and everyone who uses the site can see it!";
                        $Msg['success'] = true;
                    } catch (PDOException $e) {
                        $Msg['Msg'] = "Something caused the operation to fail. Please try again later.";
                        SendError("MySQL Error", $e->getMessage());
                    }
                } else {
                    $Msg['Msg'] = "The post is already public.";
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