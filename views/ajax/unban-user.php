<?php
if (isset($_POST['unban'])) {
    if (isset($_POST['gid'])) {
        if (isset($_SESSION['gid'])) {
            if (IsAdmin($_SESSION['gid'])['admin']) {
                $baninfo = IsBanned($_POST['gid']);
                if ($baninfo['banned']) {
                    $gid = $_POST['gid'];
                    $user = SQLWrapper()->prepare("SELECT Name FROM Users WHERE gid = :gid");
                    $user->execute([":gid" => $gid]);
                    $data = $user->fetch();
                    if ($data == null) {
                        AlertError("User does not exist!");
                    } else {
?>
                        <script>
                            $("#view-modal").modal("show");
                            observer.observe();
                            $("#unban-<?= htmlspecialchars($gid); ?>").click(function() {
                                Loading(true, "#ujban-load")
                                $("#modal-content").hide()
                                $.post('<?= htmlspecialchars($dir); ?>ajax/unban-user.php', {
                                        confirm: 1,
                                        gid: '<?= htmlspecialchars($gid); ?>'
                                    })
                                    .done(function(data) {
                                        Loading(false, "#unban-load")
                                        if (data.success) {
                                            $("#success-unban-mesage").removeClass("d-none")
                                            setInterval(function() {
                                                location.href = (`<?= htmlspecialchars($dir); ?>profile-id/<?= htmlspecialchars($gid); ?>`)
                                            }, 3000)
                                        } else {
                                            $("#modal-content").show()
                                            if (data.SysErr) {
                                                AlertError(data.Msg);
                                            }
                                        }
                                    })
                            })
                        </script>
                        <div class="modal" tabindex="-1" id="view-modal" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Unban <?= htmlspecialchars($data['Name']); ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" style="font-size: 30px">&times;</span>
                                        </button>
                                    </div>
                                    <div id="unban-load"></div>
                                    <div id="success-unban-mesage" class="d-none">
                                        <div class="modal-body">
                                            <div class="alert alert-success text-center" role="alert">
                                                <span><b>Success!</b><br><span id="success_message_text"><?= htmlspecialchars($data['Name']); ?> has been unbanned! Refreshing page in 3 seconds.</span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="modal-content">
                                        <div class="modal-body">
                                            <p class="paragraph">Are you sure you want to unban <?= htmlspecialchars($data['Name']); ?>? This user was banned for: <?= htmlspecialchars($baninfo['Reason']) ?></p>
                                        </div>
                                        <div class="modal-footer text-center" style="justify-content: center">
                                            <button id="unban-<?= htmlspecialchars($gid); ?>" type="button" class="btn btn-success">Unban</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
<?php
                    }
                } else {
                    AlertError("The user you're trying to unban is not banned!");
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
            if (UserExist($_POST['gid'])) {
                if (IsBanned($_POST['gid'])['banned']){
                    if (UnBanUser($_POST['gid'])){
                        $Msg['success'] = true;
                    } else {
                        $Msg['SysErr'] = true;
                        $Msg['Msg'] = "Something causes the ban to fail. Please try again later.";
                    }
                } else {
                    $Msg['SysErr'] = true;
                    $Msg['Msg'] = "This user is not banned!";
                }
            } else {
                $Msg['SysErr'] = true;
                $Msg['Msg'] = "The user you are trying does not exist!";
            }
        } else {
            $Msg['SysErr'] = true;
            $Msg['Msg'] = "Unauthorized";
        }
    } else {
        $Msg['SysErr'] = true;
        $Msg['Msg'] = "Invalid Session";
    }
    die(json_encode($Msg));
}
