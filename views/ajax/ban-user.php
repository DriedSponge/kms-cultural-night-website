<?php
if (isset($_POST['ban'])) {
    if (isset($_POST['gid'])) {
        if (isset($_SESSION['gid'])) {
            if (IsAdmin($_SESSION['gid'])['admin']) {
                $gid = $_POST['gid'];
                $user = SQLWrapper()->prepare("SELECT Name FROM Users WHERE gid = :gid");
                $user->execute([":gid" => $gid]);
                $data = $user->fetch();
                $restrictions = json_decode($data['Restrictions'], true);
                if ($data == null) {
                    AlertError("User does not exist!");
                } else {
?>
                    <script>
                        $("#view-modal").modal("show");
                        observer.observe();
                        $("#ban-user").submit(function(e) {
                            e.preventDefault()
                            Loading(true, "#ban-load")
                            $("#ban-user").hide()
                            var rsn = $("#reason").val()
                            $.post('<?= htmlspecialchars($dir); ?>ajax/ban-user.php', {
                                    rsn: rsn,
                                    confirm: 1,
                                    gid: '<?= htmlspecialchars($gid); ?>'
                                })
                                .done(function(data) {
                                    Loading(false, "#ban-load")
                                    if (data.success) {
                                        $("#success-ban-mesage").removeClass("d-none")
                                        setInterval(function() {
                                            location.href = (`<?= htmlspecialchars($dir); ?>profile-id/<?= htmlspecialchars($gid); ?>`)
                                        }, 3000)
                                    } else {
                                        $("#ban-user").show()
                                        if (data.SysErr) {
                                            AlertError(data.Msg);
                                        } else {
                                            if (data.rsnErr) {
                                                InValidate("#reason", data.rsnErr)
                                            } else {
                                                Validate("#reason", data.rsnErr)
                                            }
                                        }
                                    }
                                })
                        })
                    </script>
                    <div class="modal" tabindex="-1" id="view-modal" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Ban <?= htmlspecialchars($data['Name']); ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" style="font-size: 30px">&times;</span>
                                    </button>
                                </div>
                                <div id="ban-load"></div>
                                <div id="success-ban-mesage" class="d-none">
                                    <div class="modal-body">
                                        <div class="alert alert-success text-center" role="alert">
                                            <span><b>Success!</b><br><span id="success_message_text"><?= htmlspecialchars($data['Name']); ?> has been banned! Refreshing page in 3 seconds.</span></span>
                                        </div>
                                    </div>
                                </div>
                                <form id="ban-user">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Reason</label>
                                            <input id="reason" maxlength="100" feedback="#reason-f" class="form-control form-control-alternative" placeholder="Please enter a reason for banning this users">
                                            <div id="reason-f"></div>
                                        </div>
                                    </div>
                                    <div class="modal-footer text-center" style="justify-content: center">
                                        <button type="submit" class="btn btn-success">Confirm Ban</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
<?php
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
                if (!IsBanned($_POST['gid'])['banned']) {
                    if (!IsAdmin($_POST['gid']) or IsSuperAdmin($_SESSION['gid'])){
                        if (isset($_POST['rsn'])) {
                            if (IsEmpty($_POST['rsn'])) {
                                $Msg['rsnErr'] = "Please enter a reason.";
                            } else if (strlen($_POST['rsn']) > 100) {
                                $Msg['rsnErr'] = "Please reduce the reason to under 100 characters";
                            }
                            if (!isset($Msg['rsnErr'])) {
                                if (BanUser($_POST['gid'], $_SESSION['gid'], $_POST['rsn'])) {
                                    $Msg['success'] = true;
                                } else {
                                    $Msg['SysErr'] = true;
                                    $Msg['Msg'] = "Something causes the ban to fail. Please try again later.";
                                }
                            }
                        } else {
                            $Msg['SysErr'] = true;
                            $Msg['Msg'] = "Invalid post values!";
                        }
                    } else {
                        $Msg['SysErr'] = true;
                        $Msg['Msg'] = "You're not allowed to ban other admins!";
                    }
                } else {
                    $Msg['SysErr'] = true;
                    $Msg['Msg'] = "This user is already banned";
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
