<?php

use function GuzzleHttp\Psr7\str;

if (isset($_POST['edit'])) {
    if (isset($_POST['gid'])) {
        if (isset($_SESSION['gid'])) {
            if (IsAdmin($_SESSION['gid'])['admin']) {
                $gid = $_POST['gid'];
                $user = SQLWrapper()->prepare("SELECT Name,Picture,Email,Bio,RealName,UNIX_TIMESTAMP(CreationDate) AS CreationDate FROM Users WHERE gid = :gid");
                $user->execute([":gid" => $gid]);
                $data = $user->fetch();
                if ($data == null) {
                    AlertError("User does not exist!");
                } else {
?>
                    <script>
                        $("#view-modal").modal("show");
                        observer.observe();

                        $("#edit-profile-admin").submit(function(e) {
                            e.preventDefault()
                            var uname = $("#uname-edit-admin").val()
                            var bio = $("#bio-edit-admin").val()
                            var picture = $("#pic-edit-admin").val()
                            $("#edit-profile-admin").hide()
                            Loading(true, "#loading-e");
                            $.post('<?= htmlspecialchars($dir); ?>ajax/admin-edit-profile.php', {
                                    uname: uname,
                                    bio: bio,
                                    picture: picture,
                                    edit2: 1,
                                    gid: '<?= htmlspecialchars($gid); ?>',
                                    ogname: '<?= htmlspecialchars($data['Name']); ?>'
                                })
                                .done(function(data) {
                                    $("#edit-profile-admin").show()
                                    Loading(false, "#loading-e");
                                    if (data.success) {
                                        $("#edit-profile-admin").hide()
                                        $("#success-edit-mesage").removeClass("d-none");

                                        setInterval(function() {
                                            location.href = (`<?= htmlspecialchars($dir); ?>profile/${uname}`)

                                        }, 3000)

                                    } else {
                                        if (data.SysErr) {
                                            AlertError(data.Msg)
                                        } else {
                                            if (data.unameERR) {
                                                InValidate("#uname-edit-admin", data.unameERR)
                                            } else {
                                                Validate("#uname-edit-admin")
                                            }
                                            if (data.bioErr) {
                                                InValidate("#bio-edit-admin", data.bioErr)
                                            } else {
                                                Validate("#bio-edit-admin")
                                            }
                                            if (data.picErr) {
                                                InValidate("#pic-edit-admin", data.picErr)
                                            } else {
                                                Validate("#pic-edit-admin")
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
                                    <h5 class="modal-title">Editing <?= htmlspecialchars($data['Name']); ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" style="font-size: 30px">&times;</span>
                                    </button>

                                </div>
                                <div id="loading-e"></div>
                                <div id="success-edit-mesage" class="d-none">
                                    <div class="modal-body">
                                        <div class="alert alert-success text-center" role="alert">
                                            <span><b>Success!</b><br><span id="success_message_text">Information changed and saved! Refreshing the page in 3 seconds!</span></span>
                                        </div>
                                    </div>
                                </div>
                                <form id="edit-profile-admin">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input id="uname-edit-admin" feedback="#uname-edit-admin-f" maxlength="30" placeholder="Enter a username for the user (optional)" value="<?= htmlspecialchars($data['Name']); ?>" class="form-control  form-control-alternative">
                                            <div id="uname-edit-admin-f"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Bio</label>
                                            <textarea feedback="#bio-edit-admin-f" id="bio-edit-admin" rows="3" placeholder="Enter a bio for the user or leave blank" maxlength="600" class="form-control  form-control-alternative"><?= htmlspecialchars($data['Bio']); ?></textarea>
                                            <div id="bio-edit-admin-f"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Profile Picture URL (Leave blank to remove)</label>
                                            <input id="pic-edit-admin" feedback="#pic-edit-admin-f" value="<?= htmlspecialchars($data['Picture']); ?>" placeholder="Enter an image URL or leave blank" class="form-control  form-control-alternative">
                                            <div id="pic-edit-admin-f"></div>

                                        </div>

                                    </div>
                                    <div class="modal-footer" style="justify-content:center">
                                        <button type="submit" class="btn btn-success">Apply Changes</button>
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

if (isset($_POST['edit2'])) {
    $Msg = array(
        "success" => false,
        "Msg" => "Something went wrong!",
        "SysErr" => false
    );
    header("Content-type: application/json");
    if (isset($_SESSION['gid'])) {
        if (IsAdmin($_SESSION['gid'])['admin']) {
            if (UserExist($_POST['gid'])) {

                if (isset($_POST['uname']) && isset($_POST['bio']) && isset($_POST['picture'])) {
                    if ($_POST['uname'] != $_POST['ogname']) {
                        $NameCheck = UserNameValidate($_POST['uname']);
                        if (!$NameCheck == null) {
                            $Msg['unameERR'] = $NameCheck;
                        } else {
                            $name = $_POST['uname'];
                            $Msg['ChangedName'] = true;
                        }
                    } else {
                        $name = UserInfo($_POST['gid'])['UserName'];
                    }
                    if (IsEmpty($_POST['bio'])) {
                        $bio = NULL;
                    } else if (strlen($_POST['bio']) > 600) {
                        $Msg['bioErr'] = "The bio must be less than 600 characters.";
                    } else {
                        $bio = $_POST['bio'];
                    }
                    if (IsEmpty($_POST['picture'])) {
                        $pic = "https://i.driedsponge.net/images/png/yPR6z.png";
                    } else {
                        $pic = $_POST['picture'];
                    }
                    if (!isset($Msg['bioErr']) && !isset($Msg['unameERR'])) {
                        try {
                            $query = SQLWrapper()->prepare("UPDATE Users SET Name = :uname, Picture = :picture, Bio = :bio WHERE gid = :gid");
                            $query->execute([":bio" => $bio, ":uname" => $name, "picture" => $pic, ":gid" => $_POST['gid']]);
                            $Msg['success'] = true;
                            $Msg['Msg'] = "Information changed and saved! Refreshing the page in 3 seconds!";
                        } catch (PDOException $e) {
                            SendError("MySQL Error (KMS)", $e->getMessage());
                            $Msg['SysErr'] = true;
                            $Msg['Msg'] = "There was an error saving the data to the database! Please try again later";
                        }
                    }
                } else {
                    $Msg['SysErr'] = true;
                    $Msg['Msg'] = "Invalid post values!";
                }
            } else {
                $Msg['SysErr'] = true;
                $Msg['Msg'] = "User does not exist!";
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
