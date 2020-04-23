<?php
if (isset($_POST['register'])) {
    header("Content-type: application/json");
    $Msg = array(
        "success" => false,
        "SysErr" => false,
        "Msg" => "Something went wrong! Please try again later!"
    );
    if (isset($_POST['agree']) && isset($_POST['uname'])) {
        if (isset($_SESSION['access_token'])) {
            if (!UserExist($_SESSION['gid'])) {
                $banned = IsBanned($_SESSION['gid']);
                if ($banned['banned'] == false) {
                    $NameCheck = UserNameValidate($_POST['uname']);
                    if (!$NameCheck == null) {
                        $Msg['unameERR'] = $NameCheck;
                    }
                    if (IsEmpty($_POST['agree'])) {
                        $Msg['legalErr'] = "You must agree to the privacy policy and terms of service!";
                    } else if (!$_POST['agree']) {
                        $Msg['legalErr'] = "You must agree to the privacy policy and terms of service!";
                    }
                    if (!isset($Msg['legalErr']) && !isset($Msg['unameERR'])) {
                        try {
                            $res = array(
                                "UserNameChange" => false,
                                "BioChange" => false,
                                "PictureChange" => false
                            );

                            $query = SQLWrapper()->prepare("INSERT INTO Users (Name, Picture,Email,gid,RealName,Tos,Restrictions) VALUES (?,?,?,?,?,?,?,?)");
                            $query->execute([$_POST['uname'], $_SESSION['picture'], $_SESSION['email'], $_SESSION['gid'], $_SESSION['name'], $_POST['agree'], json_encode($res)]);
                            $Msg['success'] = true;
                            $Msg['Msg'] = "Account Created";
                            unset($_SESSION['access_token']);
                            $gClient->revokeToken();
                            session_destroy();
                        } catch (PDOException $e) {
                            $Msg['SysErr'] = true;
                            $Msg['Msg'] = "There was an error creating your account. The system has notified of the error and it should be fixed soon. Please try again later.";
                            SendError("MySQL Error", $e->getMessage());
                        }
                    }
                } else {
                    $Msg['SysErr'] = true;
                    $Msg['Msg'] = "This account is banned! Reason: " . $banned['Reason'];
                }
            } else {
                $Msg['SysErr'] = true;
                $Msg['Msg'] = "A user is already registered with that google account!";
            }
        } else {
            $Msg['SysErr'] = true;
            $Msg['Msg'] = "Invalid Session!";
        }
    } else {
        $Msg['SysErr'] = true;
        $Msg['Msg'] = "Invalid post values!";
    }
    die(json_encode($Msg));
}
