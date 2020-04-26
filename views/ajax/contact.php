<?php 
if(isset($_POST['contact'])){
    header("Content-type: application/json");
    $Msg = array(
        "success"=>false,
        "Msg"=>"Something went wrong",
        "SysErr"=>false
    );
    if(isset($_SESSION['UserName'])){
        if(!IsBanned($_SESSION['gid'])['banned']){
            if(isset($_POST['c'])&&isset($_POST['msg'])){
                $Categories = array("General Contact","Question","Site Feedback","Bug Report","Support");
                if(!in_array($_POST['c'],$Categories)){
                    $Msg['CErr'] = "Invalid category.";
                }
                if(IsEmpty($_POST['msg'])){
                    $Msg['MsgErr'] = "Please enter a message";
                }else if(strlen($_POST['msg'] > 1500)){
                    $Msg['MsgErr'] = "Please keep your message under 1500 characters.";
                }
                if(!isset($Msg['MsgErr'])&&!isset($Msg['CErr'])){
                    try{
                        $query = SQLWrapper()->prepare("INSERT INTO Contact (gid,Message,Category,MID) VALUES (?,?,?,?)");
                        $query->execute([$_SESSION['gid'],$_POST['msg'],$_POST['c'],uniqid('MSG')]);
                        $Msg['success'] = true;
                    } catch (PDOException $e){  
                        $Msg['SysErr'] = true;
                        $Msg['Msg'] = "There was an error sending your message, plese try again later.";
                        SendError("MySQL Error", $e->getMessage());
                    }
                }
            }else{
                $Msg['SysErr'] = true;
                $Msg['Msg'] = "Invalid post values, try refreshing the page!";
            }
        }else{
            $Msg['SysErr'] = true;
            $Msg['Msg'] = "You are banned! Please use the ban appeal form!";
        }
    }else{
        $Msg['SysErr'] = true;
        $Msg['Msg'] = "Not logged in!";
    }
    die(json_encode($Msg));
}