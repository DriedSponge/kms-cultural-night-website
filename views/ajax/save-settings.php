<?php
if(isset($_POST['save'])){
header ("Content-type: application/json");
$Msg = array(
    "success"=>false,
    "Msg"=> "Somthing went wrong."
);
if(isset($_SESSION['UserName'])){
    if(UserExist($_SESSION['gid'])){
        if(!IsBanned($_SESSION['gid'])['banned']){
            $res = FetchRestrictions($_SESSION['gid']);
            if($res['BioChange'] && $res['UserNameChange']){
                $Msg['SysErr'] = true;
                $Msg['Msg'] = "You cannot save the data because of restrictions placed on your account!";   
            }else{
                $UserInfo = UserInfo($_SESSION['gid']);
                if(!$res['UserNameChange']){
                    if ($_POST['uname'] != $_SESSION['UserName']) {
                        $NameCheck = UserNameValidate($_POST['uname']);
                        if (!$NameCheck == null) {
                            $Msg['unameERR'] = $NameCheck;
                        } else {
                            $name = $_POST['uname'];
                        }
                    } else {
                        $name = $_SESSION['UserName'];
                    }
                }else{
                    $name = $UserInfo['UserName'];
                }
                if(!$res['BioChange']){
                    if (IsEmpty($_POST['bio'])) {
                        $bio = NULL;
                    } else if (strlen($_POST['bio']) > 600) {
                        $Msg['bioErr'] = "The bio must be less than 600 characters.";
                    } else {
                        $bio = $_POST['bio'];
                    }
                }else{
                    $bio = $UserInfo['Bio'];
                }
                if(!isset($Msg['bioErr']) && !isset($Msg['unameERR'])){
                    try {
                        $query = SQLWrapper()->prepare("UPDATE Users SET Name = :uname, Bio = :bio WHERE gid = :gid");
                        $query->execute([":bio" => $bio, ":uname" => $name, ":gid" => $_SESSION['gid']]);
                        $Msg = array(
                            "success"=>true,
                            "Msg"=> "Your changes have been saved!"
                        );
                        $_SESSION['UserName'] = $name;
                    } catch (PDOException $e) {
                        SendError("MySQL Error (KMS)", $e->getMessage());
                        $Msg['SysErr'] = true;
                        $Msg['Msg'] = "There was an error saving the data to the database! Please try again later.";
                    }
                }
            }
        }else{
            $Msg['SysErr'] = true;
            $Msg['Msg'] = "Your account is banned!";   
        }
    }else{
        $Msg['SysErr'] = true;
        $Msg['Msg'] = "Your account does not exist!"; 
    }
}else{
    $Msg['SysErr'] = true;
    $Msg['Msg'] = "Not logged in!";
}
die(json_encode($Msg));
}