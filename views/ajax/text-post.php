<?php
if (isset($_POST['text'])) {
    header("Content-type: application/json");
    $Msg = array(
        "success" => false,
        "Msg" => "Something went wrong",
        "SysErr" => false
    );
    if (isset($_SESSION['UserName'])) {
        if (!IsBanned($_SESSION['gid'])['banned']) {
            if (isset($_POST['content']) && isset($_POST['category']) && isset($_POST['title']) && isset($_POST['cul'])) {
                    if (!IsValidPostCategory($_POST['category'])) {
                        $Msg['CErr'] = "Invalid category.";
                    }
                    if (IsEmpty($_POST['content'])) {
                        $Msg['ConErr'] = "Please enter some content";
                    } else if (strlen($_POST['content']) > 3500) {
                        $Msg['ConErr'] = "Please keep your content under 3500 characters.";
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
                    if (!isset($Msg['TErr']) && !isset($Msg['CErr']) && !isset($Msg['ConErr'])&& !isset($Msg['CulErr'])) {
                        try {
                            $approvalstatus = array("Status" => 0, "Message" => "Awaiting Approval");
                            $pid = uniqid("TP");
                            $query = SQLWrapper()->prepare("INSERT INTO TextPost (Title,Category,Culture,gid,Content,Approved,PostID) VALUES (?,?,?,?,?,?,?)");
                            $query->execute([$_POST['title'],$_POST['category'],$_POST['cul'],$_SESSION['gid'],$_POST['content'],json_encode($approvalstatus),$pid]);
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
