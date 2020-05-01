<?php
if (isset($_POST['video'])) {
    header("Content-type: application/json");
    $Msg = array(
        "success" => false,
        "Msg" => "Something went wrong",
        "SysErr" => false
    );
    if (isset($_SESSION['UserName'])) {
        if (!IsBanned($_SESSION['gid'])['banned']) {
            if (isset($_POST['caption']) && isset($_POST['category']) && isset($_POST['title']) && isset($_POST['cul'])) {
                    if (!IsValidPostCategory($_POST['category'])) {
                        $Msg['CErr'] = "Invalid category.";
                    }
                    if (IsEmpty($_POST['caption'])) {
                        $Msg['CapErr'] = "Please enter a caption";
                    } else if (strlen($_POST['caption']) > 1000) {
                        $Msg['CapErr'] = "Please keep your caption under 1000 characters.";
                    }      
                    if(IsEmpty($_POST['title'])){
                        $Msg['TErr'] = "A title is required.";
                    }else if (strlen($_POST['title']) > 30) {
                        $Msg['TErr'] = "Please keep your title under 30 characters.";
                    }
                    if(IsEmpty($_POST['cul'])){
                        $Msg['CulErr'] = "Please fillout this field!";
                    }else if (strlen($_POST['cul']) > 40) {
                        $Msg['CulErr'] = "Please keep this under 40 characters.";
                    }                  
                    if (!isset($Msg['TErr']) && !isset($Msg['CErr']) && !isset($Msg['CapErr'])&& !isset($Msg['CulErr'])) {
                        try {
                            $approvalstatus = array("Status" => 0, "Message" => "Awaiting Approval");
                            $pid = uniqid("VP");
                            $query = SQLWrapper()->prepare("INSERT INTO VideoPost (Title,Category,Culture,gid,Caption,Approved,PostID) VALUES (?,?,?,?,?,?,?)");
                            $query->execute([$_POST['title'],$_POST['category'],$_POST['cul'],$_SESSION['gid'],$_POST['caption'],json_encode($approvalstatus),$pid]);
                            $Msg['success'] = true;
                            $Msg['pid'] =  $pid;
                        } catch (PDOException $e) {
                            $Msg['SysErr'] = true;
                            SendError("MySQL Error", $e->getMessage());
                        }
                    }
            } else {
                $Msg['SysErr'] = true;
                $Msg['Msg'] = "Invalid post values, try refreshing the page.";
            }
        } else {
            $Msg['SysErr'] = true;
            $Msg['Msg'] = "You are banned!";
        }
    } else {
        $Msg['SysErr'] = true;
        $Msg['Msg'] = "Not logged in!";
    }
    die(json_encode($Msg));
}
