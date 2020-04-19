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
                    if (IsEmpty($_POST['uname'])) {
                        $Msg['unameERR'] = "You must enter a value for the user name!";
                    } else if (strlen($_POST['uname']) > 30) {
                        $Msg['unameERR'] = "Your username must be less than 30 characters!";
                    } else if (strlen($_POST['uname']) < 3) {
                        $Msg['unameERR'] = "Your username must be greater than 3 characters!";
                    }else if(preg_match('[\s]',$_POST['uname'])){
                        $Msg['unameERR'] = "Spaces are not allowe in user names!";
                    } else if (!UserNameReady($_POST['uname'])) {
                        $Msg['unameERR'] = "Sorry, this username is already taken!";
                    }
                    if (IsEmpty($_POST['agree'])) {
                        $Msg['legalErr'] = "You must agree to the privacy policy and terms of service!";
                    } else if (!$_POST['agree']) {
                        $Msg['legalErr'] = "You must agree to the privacy policy and terms of service!";
                    }
                    if (!isset($Msg['legalErr']) && !isset($Msg['unameERR'])) {
                        try {
                            $query = SQLWrapper()->prepare("INSERT INTO Users (Name, Picture, Bio,Email,gid,RealName,Tos) VALUES (?,?,?,?,?,?,?)");
                            $query->execute([$_POST['uname'], $_SESSION['picture'], "This user has no bio, encourage them to make one!", $_SESSION['email'], $_SESSION['gid'], $_SESSION['name'], $_POST['agree']]);
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
