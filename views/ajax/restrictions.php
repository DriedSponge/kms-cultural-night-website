<?php
if (isset($_POST['restrict'])) {
    if (isset($_POST['gid'])) {
        if (isset($_SESSION['gid'])) {
            if (IsAdmin($_SESSION['gid'])['admin']) {
                $gid = $_POST['gid'];
                $user = SQLWrapper()->prepare("SELECT Name,Restrictions FROM Users WHERE gid = :gid");
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

                        var unamedef = <?php echo $restrictions['UserNameChange'] ? 'true' : 'false'; ?>;
                        document.getElementById("uname-r").checked = unamedef;

                        var biodef = <?php echo $restrictions['BioChange'] ? 'true' : 'false'; ?>;
                        document.getElementById("bio-r").checked = biodef;

                        var picdef = <?php echo $restrictions['PictureChange'] ? 'true' : 'false'; ?>;
                        document.getElementById("pic-r").checked = picdef;

                        $("#apply-restrictions").submit(function(e) {
                            e.preventDefault()
                            var uname = $("#uname-r").prop("checked")
                            if (uname) {
                                var uname = 1
                            } else {
                                var uname = 0
                            }
                            var bio = $("#bio-r").prop("checked")
                            if (bio) {
                                var bio = 1
                            } else {
                                var bio = 0
                            }
                            var pic = $("#pic-r").prop("checked")
                            if (pic) {
                                var pic = 1
                            } else {
                                var pic = 0
                            }
                            $.post('<?= htmlspecialchars($dir); ?>ajax/restrictions.php', {
                                    uname: uname,
                                    bio: bio,
                                    pic: pic,
                                    restrict2: 1,
                                    gid: '<?= htmlspecialchars($gid); ?>'
                                })
                                .done(function(data) {
                                    if (data.success) {
                                        AlertSuccess(data.Msg);
                                        $("#view-modal").modal("hide");
                                    } else {
                                        AlertError(data.Msg)
                                    }
                                })

                        })
                    </script>
                    <div class="modal" tabindex="-1" id="view-modal" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Restrictions</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" style="font-size: 30px">&times;</span>
                                    </button>
                                </div>

                                <form id="apply-restrictions">
                                    <div class="modal-body">
                                        <h1>Modify what <?= htmlspecialchars($data['Name']); ?> can/can't do</h1>
                                        <br>
                                        <div class="form-group text-center">
                                            <label>Restrict User Name Changes</label>
                                            <br>
                                            <label class="custom-toggle">
                                                <input id="uname-r" type="checkbox">
                                                <span class="custom-toggle-slider rounded-circle"></span>
                                            </label>
                                        </div>

                                        <div class="form-group text-center">
                                            <label>Restrict Bio Changes</label>
                                            <br>
                                            <label class="custom-toggle">
                                                <input id="bio-r" type="checkbox">
                                                <span class="custom-toggle-slider rounded-circle"></span>
                                            </label>
                                        </div>

                                        <div class="form-group text-center">
                                            <label>Restrict Profile Image Changes</label>
                                            <br>
                                            <label class="custom-toggle">
                                                <input id="pic-r" type="checkbox">
                                                <span class="custom-toggle-slider rounded-circle"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="modal-footer text-center" style="justify-content: center">
                                        <button type="submit" class="btn btn-success">Apply Restrictions</button>
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

if (isset($_POST['restrict2'])) {
    $Msg = array(
        "success" => false,
        "Msg" => "Something went wrong!"
    );
    header("Content-type: application/json");
    if (isset($_SESSION['gid'])) {
        if (IsAdmin($_SESSION['gid'])['admin']) {
            if (UserExist($_POST['gid'])) {

                if (isset($_POST['uname']) && isset($_POST['bio']) && isset($_POST['pic'])) {
                    $res = array(
                        "UserNameChange" => $_POST['uname'] ? true : false,
                        "BioChange" => $_POST['bio'] ? true : false,
                        "PictureChange" => $_POST['pic'] ? true : false
                    );
                    if (ApplyRestrictions($res, $_POST['gid'])) {
                        $Msg = array(
                            "success" => true,
                            "Msg" => "Restrictions applied!"
                        );
                    } else {
                        $Msg['Msg'] = "There was an error saving the changes to the database!";
                    }
                } else {
                    $Msg['Msg'] = "Invalid post values!";
                }
            } else {
                $Msg['Msg'] = "User does not exist!";
            }
        } else {
            $Msg['Msg'] = "Unauthorized";
        }
    } else {
        $Msg['Msg'] = "Invalid Session";
    }


    die(json_encode($Msg));
}
